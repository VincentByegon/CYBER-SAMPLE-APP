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
     * Validation URL (called BEFORE money is deducted).
     */
    public function validation(Request $request)
    {
        $data = $request->all();
        Log::info('📥 M-Pesa Validation Callback:', $data);

        try {
            $account =  substr('REF-' . strtoupper(uniqid()), 0, 20);

            // Always accept the payment (unless you want to reject certain accounts)
            return response()->json([
                'ResultCode'       => 0,
                'ResultDesc'       => 'Accepted',
                'ThirdPartyTransID'=> $account, // REQUIRED by Safaricom
            ]);
        } catch (\Exception $e) {
            Log::error('❌ M-Pesa Validation Error: ' . $e->getMessage(), $data);

            return response()->json([
                'ResultCode'       => 1,
                'ResultDesc'       => 'Rejected',
                'ThirdPartyTransID'=> 'FAILED'
            ]);
        }
    }

    /**
     * Confirmation URL (called AFTER money is deducted).
     */
    public function confirmation(Request $request)
    {
        $data = $request->all();
        Log::info('📥 M-Pesa Confirmation Callback:', $data);

        try {
            DB::transaction(function () use ($data) {
                $transactionId = $data['TransID'] ?? null;
                $amount        = $data['TransAmount'] ?? 0;
                $phone         = $data['MSISDN'] ?? null;
                $firstName     = $data['FirstName'] ?? '';
                $middleName    = $data['MiddleName'] ?? '';
                $lastName      = $data['LastName'] ?? '';
                $account       = substr('REF-' . strtoupper(uniqid()), 0, 20);

                // Build customer full name
                $fullName = trim(implode(' ', array_filter([$firstName, $middleName, $lastName])));

                // 1. Find or create customer by phone number
                $customer = Customer::firstOrCreate(
                    ['phone' => $phone],
                    ['name' => $fullName]
                );

                // 2. Ensure this transaction is unique (idempotent)
                if (!Payment::where('reference_number', $transactionId)->exists()) {
                    $payment = Payment::create([
                        'customer_id'      => $customer->id,
                        'amount'           => $amount,
                        'payment_method'   => 'mpesa',
                        'reference_number' => $transactionId,
                        'notes'            => "M-Pesa Till Payment (BillRef: {$account})"
                    ]);

                    // 3. Apply FIFO payment to pending orders
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
