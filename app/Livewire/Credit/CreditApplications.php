<?php

namespace App\Livewire\Credit;

use App\Models\Customer;
use App\Models\Order;
use App\Services\LedgerService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreditApplications extends Component
{
    public $customer_id = '';
    public $credit_amount = '';
    public $reason = '';
    public $id_number = '';
    public $guarantor_name = '';
    public $guarantor_phone = '';
    public $guarantor_id = '';
    
    public $customers;
    public $selectedCustomer = null;

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'credit_amount' => 'required|numeric|min:1',
        'reason' => 'required|string|max:500',
        'id_number' => 'required|string|max:50',
        'guarantor_name' => 'required|string|max:255',
        'guarantor_phone' => 'required|string|max:20',
        'guarantor_id' => 'required|string|max:50',
    ];

    public function mount()
    {
        $this->customers = Customer::where('is_active', true)->orderBy('name')->get();
    }

    public function updatedCustomerId()
    {
        if ($this->customer_id) {
            $this->selectedCustomer = Customer::find($this->customer_id);
            $this->id_number = $this->selectedCustomer->id_number ?? '';
        } else {
            $this->selectedCustomer = null;
            $this->id_number = '';
        }
    }

    public function approveCredit()
    {
        $this->validate();

        $customer = Customer::find($this->customer_id);
        
        if (!$customer->canTakeCredit($this->credit_amount)) {
            session()->flash('error', 'Credit amount exceeds customer credit limit.');
            return;
        }

        DB::transaction(function () use ($customer) {
            // Create a credit order
            $order = Order::create([
                'customer_id' => $this->customer_id,
                'total_amount' => $this->credit_amount,
                'balance' => $this->credit_amount,
                'status' => 'completed',
                'payment_status' => 'unpaid',
                'notes' => "Credit Application: {$this->reason}",
            ]);

            // Record in ledger as debit (customer owes money)
            $ledgerService = new LedgerService();
            $ledgerService->recordDebit(
                $customer,
                $order,
                $this->credit_amount,
                "Credit approved: {$this->reason}"
            );

            // Store guarantor information in notes
            $guarantorInfo = "Guarantor: {$this->guarantor_name}, Phone: {$this->guarantor_phone}, ID: {$this->guarantor_id}";
            $order->update(['notes' => $order->notes . " | " . $guarantorInfo]);

            session()->flash('message', 'Credit application approved successfully.');
        });

        $this->reset(['credit_amount', 'reason', 'guarantor_name', 'guarantor_phone', 'guarantor_id']);
    }

    public function render()
    {
        return view('livewire.credit.credit-applications');
    }
}
