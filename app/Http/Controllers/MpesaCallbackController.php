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
    public function callback(Request $request)
    {
        $data = $request->all();
        Log::info('M-Pesa Callback Received', $data);

        try {
            $stkCallback = $data['Body']['stkCallback'];
            $checkoutRequestId = $stkCallback['CheckoutRequestID'];
            $resultCode = $stkCallback['ResultCode'];

            // Find the pending payment
            $payment = Payment::where('reference_number', $checkoutRequestId)
                             ->where('payment_method', 'mpesa')
                             ->first();

            if (!$payment) {
                Log::error('Payment not found for CheckoutRequestID: ' . $checkoutRequestId);
                return response()->json(['status' => 'error', 'message' => 'Payment not found']);
            }

            if ($resultCode == 0) {
                // Payment successful
                $callbackMetadata = $stkCallback['CallbackMetadata']['Item'];
                $mpesaReceiptNumber = '';
                $phoneNumber = '';

                foreach ($callbackMetadata as $item) {
                    if ($item['Name'] == 'MpesaReceiptNumber') {
                        $mpesaReceiptNumber = $item['Value'];
                    }
                    if ($item['Name'] == 'PhoneNumber') {
                        $phoneNumber = $item['Value'];
                    }
                }

                DB::transaction(function () use ($payment, $mpesaReceiptNumber) {
                    // Update payment with M-Pesa receipt number
                    $payment->update([
                        'reference_number' => $mpesaReceiptNumber,
                        'notes' => ($payment->notes ?? '') . ' | M-Pesa Receipt: ' . $mpesaReceiptNumber
                    ]);

                    // Apply payment using FIFO logic
                    $customer = $payment->customer;
                    $ledgerService = new LedgerService();
                    $ledgerService->applyPaymentFIFO($customer, $payment);
                });

                Log::info('M-Pesa payment processed successfully', [
                    'payment_id' => $payment->id,
                    'receipt' => $mpesaReceiptNumber
                ]);

            } else {
                // Payment failed
                $payment->delete(); // Remove the pending payment
                Log::info('M-Pesa payment failed', [
                    'checkout_request_id' => $checkoutRequestId,
                    'result_code' => $resultCode
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('M-Pesa Callback Error: ' . $e->getMessage(), $data);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
