<div class="p-6 bg-white dark:bg-gray-900 dark:text-white">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-[#EDEDEC] text-gray-900">Services</h1>
        <button wire:click="createService" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Add Service
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
          <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Search</label>
          <input type="text" wire:model.live="search" placeholder="Search services..." 
              class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Category</label>
                <select wire:model.live="category" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Services Table -->
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 dark:text-white divide-y divide-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-800 dark:text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 dark:text-white divide-y divide-gray-200">
                @forelse($services as $service)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->name }}</div>
                                @if($service->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($service->description, 50) }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $service->category ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            KSh {{ number_format($service->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $service->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button wire:click="editService({{ $service->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button wire:click="deleteService({{ $service->id }})" 
                                        onclick="return confirm('Are you sure?')" 
                                        class="text-red-600 hover:text-red-900">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No services found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $services->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">{{ $editingService ? 'Edit Service' : 'Add Service' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <livewire:services.service-form :service="$editingService" :key="$editingService?->id ?? 'new'" />
            </div>
        </div>
    @endif

    @script
    <script>
        $wire.on('service-saved', () => {
            $wire.set('showModal', false);
            $wire.$refresh();
        });
    </script>
    @endscript
</div>
