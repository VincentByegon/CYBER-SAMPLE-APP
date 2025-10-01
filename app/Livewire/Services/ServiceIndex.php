<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $showModal = false;
    public $editingService = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function createService()
    {
        $this->editingService = null;
        $this->showModal = true;
    }

    public function editService(Service $service)
    {
        $this->editingService = $service;
        $this->showModal = true;
    }

    public function deleteService(Service $service)
    {
        $service->delete();
        session()->flash('message', 'Service deleted successfully.');
    }

    public function render()
    {
        $services = Service::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Service::distinct()->pluck('category')->filter();

        return view('livewire.services.service-index', compact('services', 'categories'));
    }
}
