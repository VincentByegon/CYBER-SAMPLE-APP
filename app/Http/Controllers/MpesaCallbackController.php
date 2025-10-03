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
     * Handle validation request from Safaricom
     */
    public function validation(Request $request)
    {
        Log::info('M-Pesa Validation Callback:', $request->all());

        // Always accept validation for Buy Goods
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted'
        ]);
    }

    /**
     * Handle confirmation request from Safaricom
     */
    public function confirmation(Request $request)
    {
        $data = $request->all();
        Log::info('M-Pesa Confirmation Callback:', $data);

        try {
            DB::transaction(function () use ($data) {
                $transactionId = $data['TransID'];
                $amount        = $data['TransAmount'];
                $phone         = $data['MSISDN'];
                $firstName     = $data['FirstName'] ?? '';
                $middleName    = $data['MiddleName'] ?? '';
                $lastName      = $data['LastName'] ?? '';
                $account       = $data['BillRefNumber'] ?? null;

                // 1. Find or create customer
                $customer = Customer::firstOrCreate(
                    ['phone' => $phone],
                    ['name' => trim($firstName . ' ' . $middleName . ' ' . $lastName)]
                );

                // 2. Create a payment record
                $payment = Payment::create([
                    'customer_id'      => $customer->id,
                    'amount'           => $amount,
                    'payment_method'   => 'mpesa',
                    'reference_number' => $transactionId,
                    'notes'            => 'Payment via M-Pesa Till (' . $account . ')'
                ]);

                // 3. Apply payment to customer’s pending orders (FIFO)
                $ledgerService = new LedgerService();
                $ledgerService->applyPaymentFIFO($customer, $payment);

                Log::info('M-Pesa Payment recorded successfully', [
                    'payment_id' => $payment->id,
                    'receipt'    => $transactionId,
                    'customer'   => $customer->id
                ]);
            });

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Accepted'
            ]);

        } catch (\Exception $e) {
            Log::error('M-Pesa Confirmation Error: ' . $e->getMessage(), $data);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Failed: ' . $e->getMessage()
            ]);
        }
    }
}
