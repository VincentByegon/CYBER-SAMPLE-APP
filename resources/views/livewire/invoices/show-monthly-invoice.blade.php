
<div class="p-6 bg-white dark:bg-gray-900 dark:text-white min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Monthly Invoice Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('monthly-invoices.pdf', $monthlyInvoice->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Download PDF
            </a>
            <a href="{{ route('monthly-invoices.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">← Back to Monthly Invoices</a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ date('F', mktime(0,0,0,$monthlyInvoice->month,1)) }} {{ $monthlyInvoice->year }}</h3>
                <p class="text-gray-600 dark:text-gray-300">Generated: {{ $monthlyInvoice->generated_at ? date('M d, Y', strtotime($monthlyInvoice->generated_at)) : '-' }}</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white">Customer</h4>
                <p class="text-gray-600 dark:text-gray-300">{{ $monthlyInvoice->customer->name ?? 'All Customers' }}</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white">Total Amount</h4>
                <p class="text-lg font-semibold text-blue-600">KSh {{ number_format($monthlyInvoice->total_amount, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Orders Included</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                KSh {{ number_format($order->total_amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-400">No orders found for this invoice.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <td colspan="2" class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">Total Amount:</td>
                        <td class="px-6 py-3 text-sm font-bold text-gray-900 dark:text-white">KSh {{ number_format($monthlyInvoice->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
