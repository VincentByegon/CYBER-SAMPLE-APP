<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Component;

class OrderDetails extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order->load(['customer', 'orderItems.service', 'payments']);
    }

    public function updateStatus($status)
    {
        $this->order->update(['status' => $status]);
        session()->flash('message', 'Order status updated successfully.');
    }

    public function render()
    {
        return view('livewire.orders.order-details');
    }
}
