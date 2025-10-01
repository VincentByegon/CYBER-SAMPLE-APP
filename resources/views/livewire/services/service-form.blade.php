<form wire:submit="save" class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Service Name *</label>
            <input type="text" wire:model="name" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Price *</label>
            <input type="number" step="0.01" wire:model="price" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
            @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Category</label>
            <input type="text" wire:model="category" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
            @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Description</label>
            <textarea wire:model="description" rows="3" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2"></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="flex items-center">
                <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700 dark:text-white">Active</span>
            </label>
        </div>
    </div>

    <div class="flex justify-end space-x-3 mt-6">
    <button type="button" wire:click="$dispatch('service-saved')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 dark:text-white dark:hover:bg-black hover:bg-gray-50">
            Cancel
        </button>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white dark:text-white rounded-lg hover:bg-blue-700">
            {{ $service ? 'Update' : 'Create' }} Service
        </button>
    </div>
</form>
