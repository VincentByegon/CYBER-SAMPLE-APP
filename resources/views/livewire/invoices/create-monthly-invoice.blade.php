

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Monthly Invoice</h1>
        <a href="{{ route('monthly-invoices.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Monthly Invoices</a>
    </div>
     @if(session('error'))
        <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
    @endif
    <form wire:submit.prevent="createMonthlyInvoice">
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Monthly Invoice Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Month *</label>
                <select id="month" wire:model="month" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
                </select>
                @error('month') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="year" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Year *</label>
                <select id="year" wire:model="year" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    @for($y = date('Y')-2; $y <= date('Y'); $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
                @error('year') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Customer</label>
                <select id="customer_id" wire:model="customer_id" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                @error('customer_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
               <div>
        <label>Total Amount</label>
        <input type="text" value="KSh {{ number_format($total_amount, 2) }}" readonly>
    </div>

        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('monthly-invoices.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Create Monthly Invoice
        </button>
    </div>
</form>

</div>
