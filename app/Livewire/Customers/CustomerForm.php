<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;

class CustomerForm extends Component
{
    public $customer;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $id_number = '';
    public $address = '';
    public $type = 'individual';
    public $credit_limit = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'phone' => 'required|string|max:20',
        'id_number' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'type' => 'required|in:individual,company',
        'credit_limit' => 'required|numeric|min:0',
    ];

    public function mount($customer = null)
    {
        if ($customer) {
            $this->customer = $customer;
            $this->name = $customer->name;
            $this->email = $customer->email;
            $this->phone = $customer->phone;
            $this->id_number = $customer->id_number;
            $this->address = $customer->address;
            $this->type = $customer->type;
            $this->credit_limit = $customer->credit_limit;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $rules = $this->rules;
        
        if ($this->customer) {
            $rules['email'] = 'required|email|unique:customers,email,' . $this->customer->id;
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'id_number' => $this->id_number,
            'address' => $this->address,
            'type' => $this->type,
            'credit_limit' => $this->credit_limit,
        ];

        if ($this->customer) {
            $this->customer->update($data);
            $message = 'Customer updated successfully.';
        } else {
            Customer::create($data);
            $message = 'Customer created successfully.';
        }

        session()->flash('message', $message);
        $this->dispatch('customer-saved');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.customers.customer-form');
    }
}
