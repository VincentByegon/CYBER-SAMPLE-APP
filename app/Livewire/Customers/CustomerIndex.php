<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $showModal = false;
    public $editingCustomer = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function createCustomer()
    {
        $this->editingCustomer = null;
        $this->showModal = true;
    }

    public function editCustomer(Customer $customer)
    {
        $this->editingCustomer = $customer;
        $this->showModal = true;
    }

    public function deleteCustomer(Customer $customer)
    {
        $customer->delete();
        session()->flash('message', 'Customer deleted successfully.');
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.customers.customer-index', compact('customers'));
    }
}
