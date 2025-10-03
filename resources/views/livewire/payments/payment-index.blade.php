<div class="p-6 bg-white dark:bg-gray-900 dark:text-white">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-[#EDEDEC]">Payments</h1>
        <a href="{{ route('payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Record Payment
        </a>
    </div>
     <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 bg-white dark:bg-gray-900">
         <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total Revenue Today</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900"> KSh {{ number_format(\App\Models\Payment::whereDate('created_at', today())->sum('amount'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
         <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total Cash Payments</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900"> KSh {{ number_format(\App\Models\Payment::where('payment_method', 'cash')->sum('amount'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
         <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total MPESA Payments</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900"> KSh {{ number_format(\App\Models\Payment::where('payment_method', 'mpesa')->sum('amount'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
     </div>
             
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-[#EDEDEC]">Search</label>
                <input type="text" wire:model.live="search" placeholder="Search payments..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-[#EDEDEC]">Payment Method</label>
                <select wire:model.live="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All Methods</option>
                    <option value="cash">Cash</option>
                    <option value="mpesa">M-Pesa</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="credit">Credit</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-[#EDEDEC]">Date From</label>
                <input type="date" wire:model.live="date_from" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-[#EDEDEC]">Date To</label>
                <input type="date" wire:model.live="date_to" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex items-end">
                <button wire:click="$refresh" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-[#EDEDEC]">Payment #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-[#EDEDEC]">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-[#EDEDEC]">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-[#EDEDEC]">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-[#EDEDEC]">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-[#EDEDEC]">Reference</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-[#EDEDEC]">Order</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-[#EDEDEC] text-blue-600">
                            {{ $payment->payment_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-[#EDEDEC]">{{ $payment->customer->name??'WALK-IN CUSTOMER' }}</div>
                            <div class="text-sm text-gray-500 dark:text-[#EDEDEC]">{{ $payment->customer->email??'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-[#EDEDEC]">
                            {{ $payment->payment_date->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-[#EDEDEC]">
                            KSh {{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($payment->payment_method === 'cash') bg-green-100 text-green-800
                                @elseif($payment->payment_method === 'mpesa') bg-blue-100 text-blue-800
                                @elseif($payment->payment_method === 'bank_transfer') bg-purple-100 text-purple-800
                                @else bg-yellow-100 text-yellow-900 @endif">
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-[#EDEDEC]">
                            {{ $payment->reference_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-[#EDEDEC]">
                            @if($payment->order)
                                <a href="{{ route('orders.show', $payment->order) }}" class="dark:text-[#EDEDEC] text-blue-600 hover:text-blue-900">
                                    {{ $payment->order->order_number }}
                                </a>
                            @else
                                <span class="text-gray-500 dark:text-[#EDEDEC]">General Payment</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-[#EDEDEC]">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $payments->links() }}
        </div>
    </div>
</div>
