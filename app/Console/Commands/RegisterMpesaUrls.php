<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MpesaService;

class RegisterMpesaUrls extends Command
{
    protected $signature = 'mpesa:register-urls';
    protected $description = 'Register M-Pesa C2B Validation and Confirmation URLs with Safaricom';

    public function handle()
    {
        $this->info('🚀 Registering M-Pesa URLs...');
        try {
            $mpesaService = new MpesaService();
            $response = $mpesaService->registerUrls();

            $this->info('✅ Registration response:');
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Registration failed: ' . $e->getMessage());
            return 1;
        }
    }
}