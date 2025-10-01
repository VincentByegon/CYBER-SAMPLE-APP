<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class InvoiceDetails extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice->load(['customer', 'order.orderItems.service', 'payments']);
    }

    public function updateStatus($status)
    {
        $this->invoice->update(['status' => $status]);
        session()->flash('message', 'Invoice status updated successfully.');
    }

    public function markAsPaid()
    {
        $this->invoice->update([
            'status' => 'paid',
            'paid_amount' => $this->invoice->amount,
            'balance' => 0,
        ]);
        
        // Update the related order
        $this->invoice->order->update([
            'paid_amount' => $this->invoice->order->total_amount,
            'payment_status' => 'paid',
            'balance' => 0,
        ]);

        session()->flash('message', 'Invoice marked as paid successfully.');
        $this->invoice->refresh();
    }

    public function render()
    {
        return view('livewire.invoices.invoice-details');
    }
}
