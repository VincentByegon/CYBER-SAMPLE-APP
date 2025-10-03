<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Customer;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MpesaCallbackController extends Controller
{
    /**
     * Handle validation request from Safaricom (Buy Goods/PayBill).
     */
    public function validation(Request $request)
    {
        Log::info('M-Pesa Validation Callback:', $request->all());

        // Always accept validation for Buy Goods/Till payments
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted'
        ]);
    }

    /**
     * Handle confirmation request from Safaricom (Buy Goods/PayBill).
     */
    public function confirmation(Request $request)
    {
        $data = $request->all();
        Log::info('M-Pesa Confirmation Callback:', $data);

        try {
            DB::transaction(function () use ($data) {
                $transactionId = $data['TransID'] ?? null;
                $amount        = $data['TransAmount'] ?? 0;
                $phone         = $data['MSISDN'] ?? null;
                $firstName     = $data['FirstName'] ?? '';
                $middleName    = $data['MiddleName'] ?? '';
                $lastName      = $data['LastName'] ?? '';
                $account       = $data['BillRefNumber'] ?? 'N/A';

                // Build customer full name
                $fullName = trim(implode(' ', array_filter([$firstName, $middleName, $lastName])));

                // 1. Find or create customer by phone number
                $customer = Customer::firstOrCreate(
                    ['phone' => $phone],
                    ['name' => $fullName]
                );

                // 2. Check if this transaction already exists (idempotency)
                if (!Payment::where('reference_number', $transactionId)->exists()) {
                    $payment = Payment::create([
                        'customer_id'      => $customer->id,
                        'amount'           => $amount,
                        'payment_method'   => 'mpesa',
                        'reference_number' => $transactionId,
                        'notes'            => "M-Pesa Till Payment (BillRef: {$account})"
                    ]);

                    // 3. Apply payment FIFO to unpaid orders
                    $ledgerService = new LedgerService();
                    $ledgerService->applyPaymentFIFO($customer, $payment);

                    Log::info('✅ M-Pesa Payment recorded successfully', [
                        'payment_id' => $payment->id,
                        'receipt'    => $transactionId,
                        'customer'   => $customer->id,
                        'amount'     => $amount
                    ]);
                } else {
                    Log::warning("⚠️ Duplicate M-Pesa Transaction ignored", [
                        'TransID' => $transactionId
                    ]);
                }
            });

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ M-Pesa Confirmation Error: ' . $e->getMessage(), $data);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Failed: ' . $e->getMessage()
            ]);
        }
    }
}
