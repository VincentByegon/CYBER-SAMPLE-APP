<div class="p-6 bg-white dark:bg-gray-900 dark:text-white">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-[#EDEDEC]">Record Payment</h1>
        <a href="{{ route('payments.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Payments</a>
    </div>

    <form wire:submit="processPayment">
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-[#EDEDEC] mb-4">Payment Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Customer *</label>
                    <select wire:model.live="customer_id" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }} (Balance: KSh {{ number_format($customer->current_balance, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Specific Order (Optional)</label>
                    <select wire:model.live="order_id" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">General Payment</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}">
                                {{ $order->order_number }} (Balance: KSh {{ number_format($order->balance, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Amount *</label>
                    <input type="number" step="0.01" wire:model="amount" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
                    @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Payment Method *</label>
                    <select wire:model.live="payment_method" class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 rounded-lg px-3 py-2">
                        <option value="cash">Cash</option>
                        <option value="mpesa">M-Pesa</option>
                    </select>
                    @error('payment_method') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                 @if($payment_method === 'mpesa' || $payment_method === 'cash')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-[#EDEDEC] mb-1">
                            @if($payment_method === 'mpesa')
                                M-Pesa Reference Number
                            @else
                                Cash Reference Number
                            @endif
                        </label>
                        <input type="text" wire:model="reference_number" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('reference_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-[#EDEDEC] mb-1">Payment Date *</label>
                    <input type="datetime-local" wire:model="payment_date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @error('payment_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-[#EDEDEC] mb-1">Notes</label>
                    <textarea wire:model="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                    @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        @if($selectedCustomer)
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-[#EDEDEC] mb-4">Customer Summary</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-[#EDEDEC]">Current Balance</h4>
                        <p class="text-lg font-semibold {{ $selectedCustomer->current_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                            KSh {{ number_format($selectedCustomer->current_balance, 2) }}
                            <span class="text-sm font-normal text-gray-500 dark:text-[#EDEDEC]">
                                ({{ $selectedCustomer->current_balance > 0 ? 'Owes You' : 'You Owe' }})
                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-[#EDEDEC]">Credit Limit</h4>
                        <p class="text-lg font-semibold text-blue-600">
                            KSh {{ number_format($selectedCustomer->credit_limit, 2) }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-[#EDEDEC]">Available Credit</h4>
                        <p class="text-lg font-semibold text-green-600">
                            KSh {{ number_format($selectedCustomer->available_credit, 2) }}
                        </p>
                    </div>
                </div>

                @if($orders->count() > 0)
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 dark:text-[#EDEDEC] mb-2">Unpaid Orders</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 dark:bg-zinc-800">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase dark:text-[#EDEDEC]">Order #</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase dark:text-[#EDEDEC]">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase dark:text-[#EDEDEC]">Total</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase dark:text-[#EDEDEC]">Paid</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase dark:text-[#EDEDEC]">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="px-4 py-2 text-sm font-medium dark:text-[#EDEDEC] text-blue-600">{{ $order->order_number }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-[#EDEDEC]">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-[#EDEDEC]">KSh {{ number_format($order->total_amount, 2) }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-[#EDEDEC]">KSh {{ number_format($order->paid_amount, 2) }}</td>
                                            <td class="px-4 py-2 text-sm font-medium text-red-600">KSh {{ number_format($order->balance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="flex justify-end space-x-3">
            <a href="{{ route('payments.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 dark:text-[#EDEDEC] hover:bg-gray-50 dark:hover:bg-zinc-700">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                @if($payment_method === 'mpesa')
                    Initiate M-Pesa Payment
                @else
                    Record Payment
                @endif
            </button>
        </div>
    </form>
</div>
