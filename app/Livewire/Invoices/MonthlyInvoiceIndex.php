<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\MonthlyInvoice;

class MonthlyInvoiceIndex extends Component
{
    public function render()
    {
        $monthlyInvoices = MonthlyInvoice::with('customer')->orderByDesc('year')->orderByDesc('month')->get();
        return view('livewire.invoices.monthly-invoice-index', compact('monthlyInvoices'));
    }
}