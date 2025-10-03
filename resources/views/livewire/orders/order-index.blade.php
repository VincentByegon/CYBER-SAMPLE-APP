<div class="p-6 bg-gray-900 min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#EDEDEC]">Orders(Ksh {{number_format($orders->sum('total_amount'), 2)}})</h1>
        <a href="{{ route('orders.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-[#EDEDEC] px-4 py-2 rounded-lg shadow">
            Create Order
        </a>
    </div>
 {{-- ADDED: Customer Type Summary Statistics --}}
    <div class="mt-6 mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-800 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-600/20 mr-3">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Walk-in Customers</p>
                    <p class="text-2xl font-bold text-[#EDEDEC]">{{ $orders->where('customer_id', '')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-purple-600/20 mr-3">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Credit Customers</p>
                    <p class="text-2xl font-bold text-[#EDEDEC]">{{ $orders->where('customer.type', 'credit')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-600/20 mr-3">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Company Customers</p>
                    <p class="text-2xl font-bold text-[#EDEDEC]">{{ $orders->where('customer.type', 'company')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-yellow-600/20 mr-3">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-[#EDEDEC]">KSh {{ number_format($orders->sum('total_amount'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4"> {{-- Changed from 4 to 5 columns --}}
         <div>
          <label class="block text-sm font-medium dark:text-white mb-1">Search</label>
          <input type="text" wire:model.debounce.500ms="search" placeholder="Search orders..."
              class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-700 rounded-lg px-3 py-2
                  placeholder-gray-400 dark:placeholder-white focus:border-blue-500 focus:ring focus:ring-blue-500/40">
         </div>
            <div>
                <label class="block text-sm font-medium dark:text-white mb-1">Status</label>
                <select wire:model="status"
                        class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-700 rounded-lg px-3 py-2">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium dark:text-white mb-1">Payment Status</label>
                <select wire:model="payment_status"
                        class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-700 rounded-lg px-3 py-2">
                    <option value="">All Payment Status</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="partial">Partial</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
            {{-- ADDED: Customer Type Filter --}}
            <div>
                <label class="block text-sm font-medium dark:text-white mb-1">Customer Type</label>
                <select wire:model="customer_type"
                        class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-700 rounded-lg px-3 py-2">
                    <option value="">All Types</option>
                    <option value="walk_in">Walk-in</option>
                    <option value="credit">Credit</option>
                    <option value="company">Company</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700 bg-white dark:bg-gray-800 dark:text-white">
            <thead class="bg-gray-700 dark:bg-gray-800 dark:text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Customer</th>
                    {{-- ADDED: Customer Type Column --}}
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-blue-400">
                                <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                            </div>
                            <div class="text-sm text-gray-400">{{ $order->orderItems->count() }} items</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-[#EDEDEC]">{{ $order->customer->name??'WALK-IN CUSTOMER' }}</div>
                            <div class="text-sm text-gray-400">{{ $order->customer->email??'N/A' }}</div>
                        </td>
                        {{-- ADDED: Customer Type Display --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($order->customer?->type === 'walk_in') bg-blue-700 text-blue-100
                                @elseif($order->customer?->type === 'credit') bg-purple-700 text-purple-100
                                @elseif($order->customer?->type === 'company') bg-green-700 text-green-100
                                @else bg-gray-700 text-gray-100 @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->customer?->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#EDEDEC]">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#EDEDEC]">
                            KSh {{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($order->status === 'completed') bg-green-700 text-green-100
                                @elseif($order->status === 'processing') bg-blue-700 text-blue-100
                                @elseif($order->status === 'cancelled') bg-red-700 text-red-100
                                @else bg-yellow-700 text-yellow-100 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($order->payment_status === 'paid') bg-green-700 text-green-100
                                @elseif($order->payment_status === 'partial') bg-yellow-700 text-yellow-100
                                @else bg-red-700 text-red-100 @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-400 hover:text-blue-300">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-400">No orders found.</td> {{-- Changed from 7 to 8 --}}
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-3 border-t border-gray-700">
            {{ $orders->links() }}
        </div>
    </div>

   
</div>