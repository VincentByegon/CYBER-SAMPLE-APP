<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;

class MpesaTransactions extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // or tailwind

    public $search = '';

    protected $listeners = ['transactionUpdated' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $transactions = Payment::where('payment_method', 'mpesa')
            ->when($this->search, function ($query) {
                $query->where('reference_number', 'like', "%{$this->search}%")
                      ->orWhere('amount', 'like', "%{$this->search}%")
                      ->orWhere('customer_name', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.mpesa-transactions', [
            'transactions' => $transactions,
        ]);
    }
}
