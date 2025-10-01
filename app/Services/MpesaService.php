<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    private $consumerKey;
    private $consumerSecret;
    private $businessShortCode;
    private $passkey;
    private $callbackUrl;
    private $baseUrl;

    public function __construct()
    {
        $this->consumerKey = config('services.mpesa.consumer_key');
        $this->consumerSecret = config('services.mpesa.consumer_secret');
        $this->businessShortCode = config('services.mpesa.business_short_code');
        $this->passkey = config('services.mpesa.passkey');
        $this->callbackUrl = config('services.mpesa.callback_url');
        $this->baseUrl = config('services.mpesa.base_url', 'https://sandbox.safaricom.co.ke');
    }

    public function getAccessToken()
    {
        $credentials = base64_encode($this->consumerKey . ':' . $this->consumerSecret);
        
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials');

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Failed to get M-Pesa access token');
    }

    public function initiateSTKPush($phoneNumber, $amount, $description)
    {
        try {
            $accessToken = $this->getAccessToken();
            $timestamp = date('YmdHis');
            $password = base64_encode($this->businessShortCode . $this->passkey . $timestamp);

            // Format phone number
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $this->businessShortCode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $phoneNumber,
                'PartyB' => $this->businessShortCode,
                'PhoneNumber' => $phoneNumber,
                'CallBackURL' => $this->callbackUrl,
                'AccountReference' => 'CyberManagement',
                'TransactionDesc' => $description,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['ResponseCode'] == '0') {
                    return [
                        'success' => true,
                        'CheckoutRequestID' => $data['CheckoutRequestID'],
                        'message' => $data['ResponseDescription']
                    ];
                }
            }

            return [
                'success' => false,
                'message' => $response->json()['errorMessage'] ?? 'STK Push failed'
            ];

        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Convert to international format
        if (substr($phoneNumber, 0, 1) == '0') {
            $phoneNumber = '254' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 3) != '254') {
            $phoneNumber = '254' . $phoneNumber;
        }
        
        return $phoneNumber;
    }

    public function querySTKStatus($checkoutRequestId)
    {
        try {
            $accessToken = $this->getAccessToken();
            $timestamp = date('YmdHis');
            $password = base64_encode($this->businessShortCode . $this->passkey . $timestamp);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/mpesa/stkpushquery/v1/query', [
                'BusinessShortCode' => $this->businessShortCode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId,
            ]);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('M-Pesa STK Query Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
