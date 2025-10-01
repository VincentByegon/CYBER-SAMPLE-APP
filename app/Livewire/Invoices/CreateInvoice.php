<?php

namespace App\Livewire\Invoices;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreateInvoice extends Component
{
    public $customer_id = '';
    public $order_id = '';
    public $due_date;
    public $notes = '';
    
    public $customers;
    public $orders = [];
    public $selectedOrder = null;

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'order_id' => 'required|exists:orders,id',
        'due_date' => 'required|date|after:today',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->customers = Customer::where('is_active', true)
                                 ->where('type', 'company')
                                 ->orderBy('name')
                                 ->get();
        $this->due_date = now()->addDays(30)->format('Y-m-d');
    }

    public function updatedCustomerId()
    {
        if ($this->customer_id) {
            $this->orders = Order::where('customer_id', $this->customer_id)
                ->whereDoesntHave('invoice')
                ->where('status', '!=', 'cancelled')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->orders = [];
        }
        $this->order_id = '';
        $this->selectedOrder = null;
    }

    public function updatedOrderId()
    {
        if ($this->order_id) {
            $this->selectedOrder = Order::with(['orderItems.service'])->find($this->order_id);
        } else {
            $this->selectedOrder = null;
        }
    }

    public function createInvoice()
    {
        $this->validate();

        DB::transaction(function () {
            $order = Order::find($this->order_id);
            
            $invoice = Invoice::create([
                'customer_id' => $this->customer_id,
                'order_id' => $this->order_id,
                'amount' => $order->total_amount,
                'balance' => $order->total_amount,
                'due_date' => $this->due_date,
                'notes' => $this->notes,
                'status' => 'sent',
            ]);

            session()->flash('message', 'Invoice created successfully.');
        });

        return redirect()->route('invoices.index');
    }

    public function render()
    {
        return view('livewire.invoices.create-invoice');
    }
}
