<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    private $consumerKey;
    private $consumerSecret;
    private $shortcode;
    private $validationUrl;
    private $confirmationUrl;
    private $baseUrl;

    public function __construct()
    {
        $this->consumerKey = config('services.mpesa.consumer_key');
        $this->consumerSecret = config('services.mpesa.consumer_secret');
        $this->shortcode = config('services.mpesa.shortcode');
        $this->validationUrl = config('services.mpesa.validation_url');
        $this->confirmationUrl = config('services.mpesa.confirmation_url');
        $this->baseUrl = config('services.mpesa.base_url');
    }

    public function getAccessToken()
    {
        $credentials = base64_encode($this->consumerKey . ':' . $this->consumerSecret);

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
        ])->get($this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials');

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Failed to get M-Pesa access token');
    }

    public function registerUrls()
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->post($this->baseUrl . '/mpesa/c2b/v1/registerurl', [
            'ShortCode' => $this->shortcode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $this->confirmationUrl,
            'ValidationURL' => $this->validationUrl,
        ]);

        Log::info('M-Pesa URL Registration Response:', $response->json());

        return $response->json();
    }
}