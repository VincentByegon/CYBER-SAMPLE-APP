<div class="p-6 bg-white dark:bg-gray-900 min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Credit Applications</h1>
    </div>

    <form wire:submit="approveCredit">
        <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Customer Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Customer *</label>
                    <select wire:model.live="customer_id" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }} (Credit Limit: KSh {{ number_format($customer->credit_limit, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">ID Number *</label>
                    <input type="text" wire:model="id_number" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    @error('id_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Credit Amount *</label>
                    <input type="number" step="0.01" wire:model="credit_amount" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    @error('credit_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Reason for Credit *</label>
                    <textarea wire:model="reason" rows="3" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white"></textarea>
                    @error('reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Guarantor Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Guarantor Name *</label>
                    <input type="text" wire:model="guarantor_name" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    @error('guarantor_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Guarantor Phone *</label>
                    <input type="text" wire:model="guarantor_phone" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    @error('guarantor_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Guarantor ID Number *</label>
                    <input type="text" wire:model="guarantor_id" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    @error('guarantor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        @if($selectedCustomer)
            <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Customer Credit Summary</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Current Balance</h4>
                        <p class="text-lg font-semibold {{ $selectedCustomer->current_balance > 0 ? 'text-red-600 dark:text-red-300' : 'text-green-600 dark:text-green-300' }}">
                            KSh {{ number_format($selectedCustomer->current_balance, 2) }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Credit Limit</h4>
                        <p class="text-lg font-semibold text-blue-600 dark:text-blue-300">
                            KSh {{ number_format($selectedCustomer->credit_limit, 2) }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Available Credit</h4>
                        <p class="text-lg font-semibold text-green-600 dark:text-green-300">
                            KSh {{ number_format($selectedCustomer->available_credit, 2) }}
                        </p>
                    </div>
                </div>

                @if($credit_amount && !$selectedCustomer->canTakeCredit($credit_amount))
                    <div class="mt-4 p-3 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded">
                        <strong>Warning:</strong> Credit amount exceeds available credit limit.
                    </div>
                @endif
            </div>
        @endif

        <div class="flex justify-end space-x-3">
            <button type="button" onclick="this.form.reset()" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700">
                Reset
            </button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                Approve Credit
            </button>
        </div>
    </form>
</div>
