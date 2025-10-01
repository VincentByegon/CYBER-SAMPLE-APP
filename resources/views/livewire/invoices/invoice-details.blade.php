<div class="p-6 bg-white dark:bg-gray-900 dark:text-white min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Invoice Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('invoices.pdf', $invoice) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Download PDF
            </a>
            <a href="{{ route('invoices.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">← Back to Invoices</a>
        </div>
    </div>

    <!-- Invoice Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</h3>
                <p class="text-gray-600 dark:text-gray-300">Created: {{ $invoice->created_at->format('M d, Y') }}</p>
                <p class="text-gray-600 dark:text-gray-300">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white">Customer</h4>
                <p class="text-gray-600 dark:text-gray-300">{{ $invoice->customer->name }}</p>
                <p class="text-gray-600 dark:text-gray-300">{{ $invoice->customer->email }}</p>
                <p class="text-gray-600 dark:text-gray-300">{{ $invoice->customer->phone }}</p>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white">Status</h4>
                <div class="flex space-x-2 mt-1">
                    <select wire:change="updateStatus($event.target.value)" class="text-sm border border-gray-300 rounded px-2 py-1">
                        <option value="draft" {{ $invoice->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ $invoice->status === 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid" {{ $invoice->status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ $invoice->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ $invoice->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                @if($invoice->status !== 'paid' && $invoice->balance > 0)
                    <button wire:click="markAsPaid" onclick="return confirm('Mark this invoice as fully paid?')" 
                            class="mt-2 text-xs bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded">
                        Mark as Paid
                    </button>
                @endif
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white">Amount</h4>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">KSh {{ number_format($invoice->amount, 2) }}</p>
                <p class="text-sm text-green-600 dark:text-green-400">Paid: KSh {{ number_format($invoice->paid_amount, 2) }}</p>
                <p class="text-sm font-medium text-red-600 dark:text-red-400">Balance: KSh {{ number_format($invoice->balance, 2) }}</p>
            </div>
        </div>
        
        @if($invoice->notes)
            <div class="mt-4">
                <h4 class="font-medium text-gray-900 dark:text-white">Notes</h4>
                <p class="text-gray-600 dark:text-gray-300">{{ $invoice->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Order Details -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Details</h3>
        
        <div class="mb-4">
            <p class="text-sm text-gray-600 dark:text-gray-300">Order Number: 
                <a href="{{ route('orders.show', $invoice->order) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                    {{ $invoice->order->order_number }}
                </a>
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">Order Date: {{ $invoice->order->created_at->format('M d, Y') }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($invoice->order->orderItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->service->name }}</div>
                                @if($item->service->description)
                                    <div class="text-sm text-gray-500 dark:text-gray-300">{{ $item->service->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                KSh {{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                KSh {{ number_format($item->total_price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">Total Amount:</td>
                        <td class="px-6 py-3 text-sm font-bold text-gray-900 dark:text-white">KSh {{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Payment History -->
    @if($invoice->payments->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment History</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Payment #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($invoice->payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ $payment->payment_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $payment->payment_date->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    KSh {{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $payment->reference_number ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
