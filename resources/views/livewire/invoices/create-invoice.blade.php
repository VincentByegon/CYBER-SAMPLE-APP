<div class="p-6 bg-white dark:bg-gray-900 min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create Invoice</h1>
        <div class="flex gap-2">
            <a href="{{ route('invoices.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Invoices</a>
            <a href="{{ route('monthly-invoices.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Create Monthly Invoice</a>
        </div>
    </div>

    <form wire:submit="createInvoice">
        <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Invoice Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Company Customer *</label>
                    <select wire:model.live="customer_id" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">Select Company</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">Only company customers can receive invoices</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Order *</label>
                    <select wire:model.live="order_id" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">Select Order</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}">
                                {{ $order->order_number }} (KSh {{ number_format($order->total_amount, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('order_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">Only orders without existing invoices are shown</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Due Date *</label>
                    <input type="date" wire:model="due_date" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Notes</label>
                    <input type="text" wire:model="notes" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                    @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        @if($selectedOrder)
            <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Preview</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Order Number</h4>
                        <p class="text-gray-600 dark:text-gray-300">{{ $selectedOrder->order_number }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Order Date</h4>
                        <p class="text-gray-600 dark:text-gray-300">{{ $selectedOrder->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Total Amount</h4>
                        <p class="text-lg font-semibold text-blue-600">KSh {{ number_format($selectedOrder->total_amount, 2) }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:text-white">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($selectedOrder->orderItems as $item)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ $item->service->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">KSh {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-white">KSh {{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="flex justify-end space-x-3">
            <a href="{{ route('invoices.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Create Invoice
            </button>
        </div>
    </form>

   
</div>
