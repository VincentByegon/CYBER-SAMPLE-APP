<div>
    <h1 class="text-2xl font-bold mb-6">Monthly Invoices</h1>
    <a href="{{ route('monthly-invoices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg mb-4 inline-block">Create Monthly Invoice</a>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <table class="min-w-full divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:text-white rounded-lg overflow-hidden">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Month</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Year</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Total Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monthlyInvoices as $invoice)
                <tr>
                    <td class="px-6 py-4">{{ date('F', mktime(0,0,0,$invoice->month,1)) }}</td>
                    <td class="px-6 py-4">{{ $invoice->year }}</td>
                    <td class="px-6 py-4">{{ $invoice->customer->name ?? 'All Customers' }}</td>
                    <td class="px-6 py-4">KSh {{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('monthly-invoices.show', $invoice->id) }}" class="text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-400">No monthly invoices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>