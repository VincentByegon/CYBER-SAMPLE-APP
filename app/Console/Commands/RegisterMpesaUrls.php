<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RegisterMpesaUrls extends Command
{
       protected $signature = 'mpesa:simulate-c2b 
        {amount=10} 
        {phone=254700000000} 
        {billRef?}';

    protected $description = 'Simulate a C2B transaction in M-Pesa sandbox';

    public function handle()
    {
        $this->info('Simulating C2B transaction...');

        $consumerKey    = config('mpesa.consumer_key');
        $consumerSecret = config('mpesa.consumer_secret');
        $shortcode      = config('mpesa.shortcode');
        $baseUrl        = config('mpesa.base_url', 'https://sandbox.safaricom.co.ke');

        // Access token
        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->get($baseUrl . '/oauth/v1/generate?grant_type=client_credentials');

        if (!$response->successful()) {
            $this->error('❌ Failed to get access token: ' . $response->body());
            return;
        }

        $accessToken = $response->json()['access_token'];

        // Decide CommandID
        $commandId = (strlen($shortcode) == 6 && str_starts_with($shortcode, '600'))
            ? 'CustomerPayBillOnline'   // PayBill shortcode
            : 'CustomerBuyGoodsOnline'; // Till number

        $simulateData = [
            "ShortCode"     => $shortcode,
            "CommandID"     => $commandId,
            "Amount"        => $this->argument('amount'),
            "Msisdn"        => $this->argument('phone'),
            "BillRefNumber" => $this->argument('billRef') ?? 'ACC' . rand(1000, 9999),
        ];

        // Post to Safaricom
        $simulateResponse = Http::withToken($accessToken)
            ->post($baseUrl . '/mpesa/c2b/v1/simulate', $simulateData);

        if ($simulateResponse->successful()) {
            $this->info('✅ C2B Simulation Successful!');
            $this->line(json_encode($simulateResponse->json(), JSON_PRETTY_PRINT));
        } else {
            $this->error('❌ Failed: ' . $simulateResponse->body());
        }
    }

}
