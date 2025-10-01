<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $payment_method = '';
    public $date_from = '';
    public $date_to = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPaymentMethod()
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
        $payments = Payment::with(['customer', 'order'])
            ->when($this->search, function ($query) {
                $query->where('payment_number', 'like', '%' . $this->search . '%')
                      ->orWhere('reference_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->payment_method, function ($query) {
                $query->where('payment_method', $this->payment_method);
            })
            ->when($this->date_from, function ($query) {
                $query->whereDate('payment_date', '>=', $this->date_from);
            })
            ->when($this->date_to, function ($query) {
                $query->whereDate('payment_date', '<=', $this->date_to);
            })
            ->orderBy('payment_date', 'desc')
            ->paginate(15);

        return view('livewire.payments.payment-index', compact('payments'));
    }
}
