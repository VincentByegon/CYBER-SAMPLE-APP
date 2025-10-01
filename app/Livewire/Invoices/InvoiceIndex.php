<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $customer_type = '';
    public $date_from = '';
    public $date_to = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingCustomerType()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $invoices = Invoice::with(['customer', 'order'])
            ->when($this->search, function ($query) {
                $query->where('invoice_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('order', function ($q) {
                          $q->where('order_number', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->customer_type, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('type', $this->customer_type);
                });
            })
            ->when($this->date_from, function ($query) {
                $query->whereDate('created_at', '>=', $this->date_from);
            })
            ->when($this->date_to, function ($query) {
                $query->whereDate('created_at', '<=', $this->date_to);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.invoices.invoice-index', compact('invoices'));
    }
}
