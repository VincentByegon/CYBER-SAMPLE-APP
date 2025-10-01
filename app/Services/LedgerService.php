<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\LedgerEntry;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LedgerService
{
 /*    public function recordDebit(Customer $customer, $reference, float $amount, string $description): LedgerEntry
    {
        return DB::transaction(function () use ($customer, $reference, $amount, $description) {
            $newBalance = $customer->current_balance + $amount;
            
            $entry = LedgerEntry::create([
                'customer_id' => $customer->id,
                'reference_type' => get_class($reference),
                'reference_id' => $reference->id,
                'type' => 'debit',
                'amount' => $amount,
                'balance' => $newBalance,
                'description' => $description,
                'transaction_date' => now(),
            ]);
           
            $customer->update(['current_balance' => $newBalance]);
            
            return $entry;
        });
    } */
  public function recordDebit(Customer $customer, $order, float $amount, string $description): LedgerEntry
{
    return DB::transaction(function () use ($customer, $order, $amount, $description) {
        // Debit = customer owes more → increase balance
        $newBalance = $customer->current_balance + $amount;
 //dd($customer->current_balance, $order->balance);
        $entry = LedgerEntry::create([
            'customer_id'    => $customer->id,
            'reference_type' => get_class($order),
            'reference_id'   => $order->id,
            'type'           => 'debit',
            'amount'         => $amount,
            'balance'        => $newBalance,
            'description'    => $description,
            'transaction_date' => now(),
        ]);

        //$customer->update(['current_balance' => $newBalance]);

        /**
         * 🔹 Auto-apply credit if the customer’s balance is positive
         * (business owes the customer money)
         */
       if ($customer->current_balance < 0 && $order->balance > 0) {
    // Convert negative balance to positive credit value
    $availableCredit = abs($customer->current_balance);

    // How much can we apply?
    $creditToApply = min($availableCredit, $order->balance);

    if ($creditToApply > 0) {
        // Apply to order
        $order->paid_amount += $creditToApply;
        $order->balance -= $creditToApply;
       // dd($order->paid_amount, $order->balance,$order->total_amount);
        $order->updatePaymentStatus();

        // Update customer balance (since we consumed some credit)
        $customer->current_balance += $creditToApply; // because balance was negative
        $customer->save();

        // Record a credit ledger entry
        LedgerEntry::create([
            'customer_id'    => $customer->id,
            'reference_type' => get_class($order),
            'reference_id'   => $order->id,
            'type'           => 'credit',
            'amount'         => $creditToApply,
            'balance'        => $customer->current_balance,
            'description'    => "Auto-applied credit to Order #{$order->order_number}",
            'transaction_date' => now(),
        ]);
        
             Payment::create([
            'customer_id'      => $customer->id,
            'order_id'         => $order->id,
            'amount'           => $creditToApply,
            'payment_method'   => $this->payment_method ?? 'credit', // fallback
            'reference_number' => $this->reference_number ?? 'AUTO-' . strtoupper(Str::random(6)),
            'notes'            => $this->notes ?? 'Auto-applied credit',
            'payment_date'     => $this->payment_date ?? now(),
        ]);
    }
}

        $customer->update(['current_balance' => $newBalance]);

        return $entry;

    });
}
 

    public function recordCredit(Customer $customer, $reference, float $amount, string $description): LedgerEntry
    {
        return DB::transaction(function () use ($customer, $reference, $amount, $description) {
          // $amount = abs($amount);
            $newBalance = $customer->current_balance - $amount;
           //dd($customer->current_balance, $amount, $newBalance);
           
            $entry = LedgerEntry::create([
                'customer_id' => $customer->id,
                'reference_type' => get_class($reference),
                'reference_id' => $reference->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance' => $newBalance,
                'description' => $description,
                'transaction_date' => now(),
            ]);
            
            $customer->update(['current_balance' => $newBalance]);
            
            return $entry;
        });
    }

   /*   public function applyPaymentFIFO(Customer $customer, Payment $payment): void
    {
        DB::transaction(function () use ($customer, $payment) {
            $remainingAmount = $payment->amount;
            
            // Get unpaid orders in FIFO order (oldest first)
            $unpaidOrders = $customer->orders()
                ->where('payment_status', '!=', 'paid')
                ->orderBy('created_at', 'asc')
                ->get();
            
            foreach ($unpaidOrders as $order) {
                if ($remainingAmount <= 0) break;
                
                $orderBalance = $order->balance;
                $paymentAmount = min($remainingAmount, $orderBalance);
                
                // Update order payment
                $order->paid_amount += $paymentAmount;
                $order->updatePaymentStatus();
                
                // Update invoice if exists
                if ($order->invoice) {
                    $order->invoice->paid_amount += $paymentAmount;
                    $order->invoice->updatePaymentStatus();
                }
                
                $remainingAmount -= $paymentAmount;
                
                // Record ledger entry for this specific allocation
                $this->recordCredit(
                    $customer,
                    $order,
                    $paymentAmount,
                    "Payment allocation to Order #{$order->order_number}"
                );
            }
        });
    } */ 
  public function applyPaymentFIFO(Customer $customer, Payment $payment): void
{
    DB::transaction(function () use ($customer, $payment) {
        $remainingAmount = $payment->amount;
        
        // Get unpaid orders in FIFO order (oldest first)
        $unpaidOrders = $customer->orders()
            ->where('payment_status', '!=', 'paid')
            ->orderBy('created_at', 'asc')
            ->get();
       //dd($unpaidOrders);
        foreach ($unpaidOrders as $order) {
            if ($remainingAmount <= 0) break;
            
            $orderBalance = $order->balance;
            $paymentAmount = min($remainingAmount, $orderBalance);
          // dd($orderBalance, $paymentAmount);
            // Update order payment
            $order->paid_amount += $paymentAmount;
            $order->updatePaymentStatus();
          //  dd($order->paid_amount);
            // Update invoice if exists
            if ($order->invoice) {
                $order->invoice->paid_amount += $paymentAmount;
                $order->invoice->updatePaymentStatus();
            }
          
            $remainingAmount -= $paymentAmount;
         //  dd($remainingAmount,$paymentAmount);
            // Record ledger entry for this specific allocation
            $this->recordCredit(
                $customer,
                $order,
                $paymentAmount,
                "Payment allocation to Order #{$order->order_number}"
            );
        }
//dd($remainingAmount);
        // If extra payment remains after covering all orders, carry it forward
        if ($remainingAmount > 0) {
            $this->recordCredit(
                $customer,
                $payment, // reference is payment itself
                $remainingAmount,
                "Advance payment (carried forward)"
            );
        } 
    });
} /*
public function applyPaymentFIFO(Customer $customer, Payment $payment): void
{
    DB::transaction(function () use ($customer, $payment) {
        $remainingAmount = $payment->amount;

        // Get unpaid orders in FIFO order (oldest first)
        $unpaidOrders = $customer->orders()
            ->where('payment_status', '!=', 'paid')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($unpaidOrders as $order) {
            if ($remainingAmount <= 0) break;

            $orderBalance = $order->balance;
            $paymentAmount = min($remainingAmount, $orderBalance);

            // Update order payment
            $order->paid_amount += $paymentAmount;
            $order->updatePaymentStatus();

            // Update invoice if exists
            if ($order->invoice) {
                $order->invoice->paid_amount += $paymentAmount;
                $order->invoice->updatePaymentStatus();
            }

            $remainingAmount -= $paymentAmount;

            // Record ledger entry for this allocation
            $this->recordCredit(
                $customer,
                $order,
                $paymentAmount,
                "Payment allocation to Order #{$order->order_number}"
            );
        }

        // If extra payment remains after covering all orders → store as available credit
        if ($remainingAmount > 0) {
            LedgerEntry::create([
                'customer_id'      => $customer->id,
                'reference_type'   => get_class($payment),
                'reference_id'     => $payment->id,
                'type'             => 'credit',
                'amount'           => $remainingAmount,
                'balance'          => $customer->current_balance, // balance owed stays same
                'description'      => "Advance payment (carried forward)",
                'transaction_date' => now(),
            ]);

            // Update available credit (customer is owed this money)
            $customer->available_credit += $remainingAmount;
            $customer->save();
        }
    });
}
*/
  
}
