<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\MonthlyInvoice;
use App\Models\Order;

class ShowMonthlyInvoice extends Component
{
    public $monthlyInvoice;
    public $orders;

    public function mount($monthlyInvoice)
    {
        $this->monthlyInvoice = MonthlyInvoice::with('customer')->findOrFail($monthlyInvoice);

        $ordersQuery = Order::whereYear('created_at', $this->monthlyInvoice->year)
            ->whereMonth('created_at', $this->monthlyInvoice->month);

        if ($this->monthlyInvoice->customer_id) {
            $ordersQuery->where('customer_id', $this->monthlyInvoice->customer_id);
        }

        $this->orders = $ordersQuery->get();
    }

    public function render()
    {
        return view('livewire.invoices.show-monthly-invoice');
    }
}
