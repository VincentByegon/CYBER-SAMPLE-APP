<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $payment_status = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::with(['customer', 'orderItems.service'])
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->payment_status, function ($query) {
                $query->where('payment_status', $this->payment_status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.orders.order-index', compact('orders'));
    }
}
