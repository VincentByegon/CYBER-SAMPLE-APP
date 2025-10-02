<?php

namespace App\Livewire\Orders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Services\LedgerService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateOrder extends Component
{
    public $customer_id = '';
    public $notes = '';
    public $orderItems = [];
    public $discount = 0;          // discount amount (KES)
    public $payment_mode = '';     // cash, mpesa, card, etc.
    public $payment_amount = 0;    // amount paid now
    public $customers;
    public $services;
     public $reference_number='';
    public  $reference = null;

    protected $rules = [
        //'customer_id' => 'required|exists:customers,id',
        'notes' => 'nullable|string',
        'orderItems.*.service_id' => 'required|exists:services,id',
        'orderItems.*.quantity' => 'required|integer|min:1',
        'discount' => 'nullable|numeric|min:0',
        'payment_mode' => 'string|in:cash,mpesa,card,bank',
        'payment_amount' => 'nullable|numeric|min:0',
    ];

    public function mount()
    {
        $this->customers = Customer::where('is_active', true)->orderBy('name')->get();
        $this->services = Service::where('is_active', true)->orderBy('name')->get();
        $this->addOrderItem();
    }

    public function addOrderItem()
    {
        $this->orderItems[] = [
            'service_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'total_price' => 0,
        ];
    }

    public function removeOrderItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
    }

    public function updatedOrderItems($value, $key)
    {
        // safer explode (limit 2)
        [$index, $field] = array_pad(explode('.', $key, 2), 2, null);

        if ($field === 'service_id') {
            $service = Service::find($value);
            if ($service) {
                $this->orderItems[$index]['unit_price'] = $service->price;
                $this->calculateItemTotal($index);
            }
        } elseif ($field === 'quantity' || $field === 'unit_price') {
            $this->calculateItemTotal($index);
        }
    }

    private function calculateItemTotal($index)
    {
        $quantity = $this->orderItems[$index]['quantity'] ?? 0;
        $unitPrice = $this->orderItems[$index]['unit_price'] ?? 0;
        $this->orderItems[$index]['total_price'] = ($quantity * $unitPrice);
    }

    // computed properties (still useful / kept)
    public function getSubtotalProperty()
    {
        return collect($this->orderItems)->sum('total_price');
    }

    public function getTotalAmountProperty()
    {
        $subtotal = (float) $this->subtotal;           // uses getSubtotalProperty
        $discount = max(0, (float) $this->discount);
        $applied = min($discount, $subtotal);          // prevent discount > subtotal
        return round(max(0, $subtotal - $applied), 2);
    }

    public function getBalanceProperty()
    {
        $total = (float) $this->totalAmount;          // uses getTotalAmountProperty
        $paid  = max(0, (float) ($this->payment_amount ?? 0));
        return round(max(0, $total - $paid), 2);
    }

    public function getChangeProperty()
    {
        $total = (float) $this->totalAmount;
        $paid  = max(0, (float) ($this->payment_amount ?? 0));
        return round(max(0, $paid - $total), 2);
    }

    public function createOrder()
    {
        $this->validate();

        // must have at least one valid service
        if (empty($this->orderItems) || collect($this->orderItems)->where('service_id', '')->count() > 0) {
            session()->flash('error', 'Please add at least one valid service to the order.');
            return;
        }

        // calculate values explicitly here
        $subtotal = $this->subtotal;
    $discountApplied = min(max(0, (float) $this->discount), $subtotal);
    $total = round(max(0, $subtotal - $discountApplied), 2);
    $paid = round((float) $this->payment_amount, 2);
    $balance = round($total - $paid, 2);
   // dd($total,$paid,$balance);
    $change = round(max(0, $paid - $total), 2);
       // dd($subtotal, $discountApplied, $total, $paid, $balance, $change);
         // wrap in transaction to ensure atomicity
        DB::transaction(function () use ($total, $balance, $discountApplied, $paid) {
            $order = Order::create([
                 'customer_id'   => $this->customer_id ?: null, 
                'total_amount' => $total,
                'balance' => $balance,
                'notes' => $this->notes,
                'subtotal' => $this->subtotal,
                'change' => $this->change,
                'paid_amount' => $paid,
                'discount' => $discountApplied,
                'payment_mode' => $this->payment_mode,
            ]);

            foreach ($this->orderItems as $item) {
                if (!empty($item['service_id'])) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'service_id' => $item['service_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total_price'],
                    ]);
                }
            }

            if ($order->total_amount <= 0) {
                session()->flash('error', 'Order total must be greater than zero.');
                return;
            }

            // If some amount remains unpaid, record debit in ledger for that outstanding balance
            if ($order->balance > 0) {
                $customer = Customer::find($this->customer_id);
                if ($customer) {
                    $ledgerService = new LedgerService();
                    $ledgerService->recordDebit(
                        $customer,
                        $order,
                        $order->balance,
                        "Order #{$order->order_number} - Services provided"
                    );
                }
            }

            // Optionally: if a payment was made now, you can create a Payment record here
            // if you have a Payment model. (left commented to avoid breaking if not present)
            
           /*  if ($paid > 0 && class_exists(\App\Models\Payment::class)) {
              // dd('CASH-' . strtoupper(Str::random(6)),);
              $payment=  \App\Models\Payment::create([
                    'order_id' => $order->id,
                    'customer_id' => $this->customer_id,
                    'amount' => $paid,
                    'reference_number' => 'CASH-' . strtoupper(Str::random(6)),
                    'payment_method' => $this->payment_mode,
                    'notes' => 'Payment on order creation',
                ]);
                  $customer = Customer::find($this->customer_id);
            $ledgerService = new LedgerService();
            $ledgerService->applyPaymentFIFO($customer, $payment);
            }  */
            

if ($this->payment_mode === 'cash') {
    $reference = 'CASH-' . strtoupper(Str::random(6));
} elseif ($this->payment_mode === 'mpesa') {
    // Example: generate M-Pesa style reference
    $reference = 'MPESA-' . strtoupper(Str::random(8));
} else {
    // fallback if other payment methods in future
    $reference = strtoupper(Str::random(10));
}
            if ($paid > 0 && class_exists(\App\Models\Payment::class)) {
    $payment = \App\Models\Payment::create([
        'order_id' => $order->id,
       'customer_id'   => $this->customer_id ?: null, // null for walk-ins
        'amount' => $paid,
        'reference_number' =>$reference,
        'payment_method' => $this->payment_mode,
        'payment_date'=> now(),
        'notes' => 'Payment on order creation',
    ]);

    if ($this->customer_id) {
        // ✅ Credit customer → go through ledger
        $customer = Customer::find($this->customer_id);
        $ledgerService = new LedgerService();
        $ledgerService->applyPaymentFIFO($customer, $payment);
    } else {
        // ✅ Walk-in → no ledger
        // Just connect payment to order, balance already handled
        $order->payment_status=$balance <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid');
        $order->balance = max(0, $order->total_amount - $paid);
        $order->change  = max(0, $paid - $order->total_amount);
        $order->save();
    }
}


            session()->flash('message', 'Order created successfully.');
            // redirect after transaction (return inside transaction is ignored)
            redirect()->route('orders.show', $order);
        });
    }

    public function render()
    {
        // compute and pass variables explicitly so Blade sees them as $subtotal, $totalAmount, $balance, $change
        $subtotal = collect($this->orderItems)->sum('total_price');
        $discount = max(0, (float) $this->discount);
        $appliedDiscount = min($discount, $subtotal);
        $totalAmount = round(max(0, $subtotal - $appliedDiscount), 2);
        $payment = max(0, (float) ($this->payment_amount ?? 0));
        $balance = round($totalAmount - $payment, 2);
        $change = round(max(0, $payment - $totalAmount), 2);

        return view('livewire.orders.create-order', compact('subtotal', 'totalAmount', 'balance', 'change'));
    }
}
