<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;

class CustomerDetails extends Component
{
    public Customer $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load(['orders', 'payments', 'invoices', 'ledgerEntries']);
    }

    public function render()
    {
        return view('livewire.customers.customer-details');
    }
}
