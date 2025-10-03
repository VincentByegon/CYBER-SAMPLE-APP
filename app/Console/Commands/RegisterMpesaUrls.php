<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RegisterMpesaUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpesa:register-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register M-Pesa confirmation & validation URLs';

    /**
     * Execute the console command.
     */
  
    public function handle()
    {
          $this->info('Registering M-Pesa URLs...');

        $consumerKey = config('mpesa.consumer_key');
        $consumerSecret = config('mpesa.consumer_secret');
        $shortcode = config('mpesa.shortcode');
        $validationUrl = config('mpesa.validation_url');
        $confirmationUrl = config('mpesa.confirmation_url');
        $baseUrl = config('mpesa.base_url');

        // Get Access Token
        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->get($baseUrl . '/oauth/v1/generate?grant_type=client_credentials');

        if (!$response->successful()) {
            $this->error('Failed to get access token: ' . $response->body());
            return;
        }

        $accessToken = $response->json()['access_token'];

        // Register URLs
        $registerResponse = Http::withToken($accessToken)
            ->post($baseUrl . '/mpesa/c2b/v1/registerurl', [
                'ShortCode' => $shortcode,
                'ResponseType' => 'Completed',
                'ConfirmationURL' => $confirmationUrl,
                'ValidationURL' => $validationUrl,
            ]);

        if ($registerResponse->successful()) {
            $this->info('M-Pesa URLs registered successfully!');
            $this->line(json_encode($registerResponse->json(), JSON_PRETTY_PRINT));
        } else {
            $this->error('Failed to register URLs: ' . $registerResponse->body());
        }
    }
}
