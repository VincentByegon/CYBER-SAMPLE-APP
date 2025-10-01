<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Component;

class ServiceForm extends Component
{
    public $service;
    public $name = '';
    public $description = '';
    public $price = 0;
    public $category = '';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'category' => 'nullable|string|max:100',
        'is_active' => 'boolean',
    ];

    public function mount($service = null)
    {
        if ($service) {
            $this->service = $service;
            $this->name = $service->name;
            $this->description = $service->description;
            $this->price = $service->price;
            $this->category = $service->category;
            $this->is_active = $service->is_active;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
            'is_active' => $this->is_active,
        ];

        if ($this->service) {
            $this->service->update($data);
            $message = 'Service updated successfully.';
        } else {
            Service::create($data);
            $message = 'Service created successfully.';
        }

        session()->flash('message', $message);
        $this->dispatch('service-saved');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.services.service-form');
    }
}
