<?php

namespace App\Livewire\Payments;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Services\LedgerService;
use App\Services\MpesaService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatePayment extends Component
{
    public $customer_id = '';
    public $order_id = '';
    public $amount = '';
    public $payment_method = 'cash';
    public $reference_number='';
    public $phone_number = '';
    public $notes = '';
    public $payment_date;

    public $customers;
    public $orders = [];
    public $selectedCustomer = null;

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'amount' => 'required|numeric|min:0.01',
        'payment_method' => 'required|in:cash,mpesa,bank_transfer,credit',
        'reference_number' => 'nullable|string|max:100',
        'phone_number' => 'required_if:payment_method,mpesa|nullable|string|max:15',
        'notes' => 'nullable|string',
        'payment_date' => 'required|date',
    ];

    public function mount()
    {
        $this->customers = Customer::where('is_active', true)->orderBy('name')->get();
        $this->payment_date = now()->format('Y-m-d\TH:i');
         // Set reference number only for cash or mpesa
        if ($this->payment_method === 'cash') {
            $this->reference_number = 'CASH-' . strtoupper(Str::random(6));
        } elseif ($this->payment_method === 'mpesa') {
            $this->reference_number = 'MPESA-' . strtoupper(Str::random(6));
        } else {
            $this->reference_number = '';
        }
    }
    
    public function updatedPaymentMethod($value)
    {
        if ($value === 'cash') {
            $this->reference_number = 'CASH-' . strtoupper(Str::random(6));
        } elseif ($value === 'mpesa') {
            $this->reference_number = 'MPESA-' . strtoupper(Str::random(6));
        } else {
            $this->reference_number = '';
        }
    }

    public function updatedCustomerId()
    {
        if ($this->customer_id) {
            $this->selectedCustomer = Customer::find($this->customer_id);
            $this->orders = Order::where('customer_id', $this->customer_id)
                ->where('payment_status', '!=', 'paid')
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $this->selectedCustomer = null;
            $this->orders = [];
        }
        $this->order_id = '';
    }

    public function updatedOrderId()
    {
        if ($this->order_id) {
            $order = Order::find($this->order_id);
            if ($order) {
                $this->amount = $order->balance;
            }
        }
    }

    public function processPayment()
    {
        $this->validate();

       /*  if ($this->payment_method === 'mpesa') {
            return $this->processMpesaPayment();
        } */

        return $this->processDirectPayment();
    }

    /* private function processMpesaPayment()
    {
        try {
            $mpesaService = new MpesaService();
            $response = $mpesaService->initiateSTKPush(
                $this->phone_number,
                $this->amount,
                "Payment for services"
            );

            if ($response['success']) {
                // Store pending payment
                $payment = Payment::create([
                    'customer_id' => $this->customer_id,
                    'order_id' => $this->order_id ?: null,
                    'amount' => $this->amount,
                    'payment_method' => 'mpesa',
                    'reference_number' => $response['CheckoutRequestID'],
                    'notes' => $this->notes,
                    'payment_date' => $this->payment_date,
                ]);

                session()->flash('message', 'M-Pesa payment initiated. Please complete the payment on your phone.');
                return redirect()->route('payments.index');
            } else {
                session()->flash('error', 'Failed to initiate M-Pesa payment: ' . $response['message']);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'M-Pesa payment failed: ' . $e->getMessage());
        }
    }
 */
    private function processDirectPayment()
    {
        DB::transaction(function () {
           
            $payment = Payment::create([
                'customer_id' => $this->customer_id,
                'order_id' => $this->order_id ?: null,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'reference_number' => $this->reference_number ?? 'CASH-' . strtoupper(Str::random(6)),
                'notes' => $this->notes,
                'payment_date' => $this->payment_date,
            ]);
 //dd($this->payment_method);
            // Apply payment using FIFO logic
            $customer = Customer::find($this->customer_id);
            $ledgerService = new LedgerService();
            $ledgerService->applyPaymentFIFO($customer, $payment);

            session()->flash('message', 'Payment recorded successfully.');
        });

        return redirect()->route('payments.index');
    }

    public function render()
    {
        return view('livewire.payments.create-payment');
    }
}
