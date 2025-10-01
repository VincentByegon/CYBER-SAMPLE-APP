<form wire:submit="save" class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Name *</label>
            <input type="text" wire:model="name" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Email *</label>
            <input type="email" wire:model="email" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Phone *</label>
            <input type="text" wire:model="phone" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">ID Number</label>
            <input type="text" wire:model="id_number" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
            @error('id_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Type *</label>
            <select wire:model="type" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
                <option value="individual">Individual</option>
                <option value="company">Company</option>
            </select>
            @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Credit Limit *</label>
            <input type="number" step="0.01" wire:model="credit_limit" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
            @error('credit_limit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Address</label>
            <textarea wire:model="address" rows="3" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2"></textarea>
            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="flex justify-end space-x-3 mt-6">
    <button type="button" wire:click="$dispatch('customer-saved')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-black">
            Cancel
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            {{ $customer ? 'Update' : 'Create' }} Customer
        </button>
    </div>
</form>
