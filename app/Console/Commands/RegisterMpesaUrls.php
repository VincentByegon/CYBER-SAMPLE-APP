<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RegisterMpesaUrls extends Command
{
      protected $signature = 'mpesa:simulate-c2b
        {amount=10 : Amount to simulate}
        {phone=254708374149 : Test phone number}
        {billRef? : Optional Bill Reference}';

    protected $description = 'Simulate a C2B transaction in M-Pesa sandbox';

    public function handle()
    {
        $this->info('🚀 Simulating C2B transaction...');

        $consumerKey    = config('mpesa.consumer_key');
        $consumerSecret = config('mpesa.consumer_secret');
        $shortcode      = config('mpesa.shortcode', '600000');
        $baseUrl        = config('mpesa.base_url', 'https://sandbox.safaricom.co.ke');

        // 1. Get access token
        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->get($baseUrl . '/oauth/v1/generate?grant_type=client_credentials');

        if (!$response->successful()) {
            $this->error('❌ Failed to get access token: ' . $response->body());
            return 1;
        }

        $accessToken = $response->json()['access_token'];

        // 2. Choose CommandID
        $commandId = (str_starts_with($shortcode, '600'))
            ? 'CustomerBuyGoodsOnline'   // Till number
            : 'CustomerPayBillOnline';   // Paybill shortcode

        // 3. Prepare simulation payload
        $simulateData = [
            "ShortCode"     => $shortcode,
            "CommandID"     => $commandId,
            "Amount"        => $this->argument('amount'),
            "Msisdn"        => $this->argument('phone'),
            "BillRefNumber" => $this->argument('billRef') ?? ('ACC' . rand(1000, 9999)),
        ];

        // 4. Send request
        $simulateResponse = Http::withToken($accessToken)
            ->post($baseUrl . '/mpesa/c2b/v1/simulate', $simulateData);

        if ($simulateResponse->successful()) {
            $this->info('✅ C2B Simulation Successful!');
            $this->line(json_encode($simulateResponse->json(), JSON_PRETTY_PRINT));
            return 0;
        } else {
            $this->error('❌ Simulation Failed: ' . $simulateResponse->body());
            return 1;
        }
    }

}
