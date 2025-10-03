<div>
    <div class="p-6 bg-white dark:bg-gray-900 min-h-screen">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Invoices</h1>
            <a href="{{ route('invoices.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Create Invoice
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Search</label>
                   <input type="text" wire:model.debounce.500ms="search"
       placeholder="Search invoices..."
       class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">

                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Status</label>
                    <select wire:model.live="status"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="sent">Sent</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <!-- Customer Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Customer Type</label>
                    <select wire:model.live="customer_type"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                        <option value="">All Types</option>
                        <option value="individual">Individual</option>
                        <option value="company">Company</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Date From</label>
                    <input type="date" wire:model.live="date_from"
                           class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Date To</label>
                    <input type="date" wire:model.live="date_to"
                           class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white">
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($invoices as $invoice)
                        <tr class="{{ $invoice->due_date < now() && $invoice->status !== 'paid' ? 'bg-red-50 dark:bg-red-900' : '' }}">
                            <td class="px-6 py-4 text-sm font-medium text-blue-600 dark:text-blue-300">
                                <a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->customer->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ ucfirst($invoice->customer->type) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-blue-600 dark:text-blue-300">
                                <a href="{{ route('orders.show', $invoice->order) }}">{{ $invoice->order->order_number }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                KSh {{ number_format($invoice->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $invoice->due_date->format('M d, Y') }}
                                @if($invoice->due_date < now() && $invoice->status !== 'paid')
                                    <span class="text-red-500 text-xs block">Overdue</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($invoice->status === 'paid') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                    @elseif($invoice->status === 'sent') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300
                                    @elseif($invoice->status === 'overdue') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                    @elseif($invoice->status === 'cancelled') bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-300
                                    @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 @endif">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-red-600 dark:text-red-300">
                                KSh {{ number_format($invoice->balance, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 dark:text-blue-300 hover:text-blue-900">View</a>
                                    <a href="#" class="text-green-600 dark:text-green-300 hover:text-green-900">PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">No invoices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>