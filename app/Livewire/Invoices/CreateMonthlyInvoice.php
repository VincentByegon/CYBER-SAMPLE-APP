<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\MonthlyInvoice;
use App\Models\Customer;
use App\Models\Order;

class CreateMonthlyInvoice extends Component
{
    public $month, $year, $customer_id;
    public $total_amount = 0;

    public function updated($property)
    {
        if (in_array($property, ['month', 'year', 'customer_id'])) {
            $this->calculateTotal();
        }
    }

    protected function calculateTotal()
    {
        if ($this->month && $this->year) {
            $query = Order::whereYear('created_at', $this->year)
                ->whereMonth('created_at', $this->month);

            if ($this->customer_id) {
                $query->where('customer_id', $this->customer_id);
            }

            $this->total_amount = $query->sum('total_amount');
        } else {
            $this->total_amount = 0;
        }
    }

    public function createMonthlyInvoice()
    {
        $this->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        // Recalculate before saving
        $this->calculateTotal();

        if ($this->total_amount <= 0) {
            session()->flash('error', 'No orders found for this month/year.');
            return;
        }

        MonthlyInvoice::create([
            'month'        => $this->month,
            'year'         => $this->year,
            'customer_id'  => $this->customer_id,
            'total_amount' => $this->total_amount,
            'generated_at' => now(),
        ]);

        session()->flash('success', 'Monthly Invoice created successfully.');
        return redirect()->route('monthly-invoices.index');
    }

    public function render()
    {
        $customers = Customer::orderBy('name')->get();
        return view('livewire.invoices.create-monthly-invoice', compact('customers'));
    }
}
