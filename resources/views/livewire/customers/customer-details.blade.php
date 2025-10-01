<div class="p-6 bg-white dark:bg-gray-900 min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-[#EDEDEC] text-gray-900">Customer Details</h1>
        <a href="{{ route('customers.index') }}" class="dark:text-[#EDEDEC] text-blue-600 hover:text-blue-800">← Back to Customers</a>
    </div>

    <!-- Customer Info -->
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <h3 class="text-lg font-semibold dark:text-[#EDEDEC] text-gray-900 mb-2">{{ $customer->name }}</h3>
                <p class="dark:text-[#EDEDEC] text-gray-600">{{ $customer->email }}</p>
                <p class="dark:text-[#EDEDEC] text-gray-600">{{ $customer->phone }}</p>
                @if($customer->id_number)
                    <p class="dark:text-[#EDEDEC] text-gray-600">ID: {{ $customer->id_number }}</p>
                @endif
            </div>
            
            <div>
                <h4 class="font-medium dark:text-[#EDEDEC] text-gray-900">Type</h4>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                    {{ $customer->type === 'company' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    {{ ucfirst($customer->type) }}
                </span>
            </div>
            
            <div>
                <h4 class="font-medium dark:text-[#EDEDEC] text-gray-900">Credit Limit</h4>
                <p class="text-lg font-semibold text-green-600">KSh {{ number_format($customer->credit_limit, 2) }}</p>
                <p class="text-sm  dark:text-[#EDEDEC] text-gray-500">Available: KSh {{ number_format($customer->available_credit, 2) }}</p>
            </div>
            
            <div>
                <h4 class="font-medium dark:text-[#EDEDEC] text-gray-900">Current Balance</h4>
                <p class="text-lg font-semibold {{ $customer->current_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                    KSh {{ number_format($customer->current_balance, 2) }}
                    <span class="text-sm font-normal text-gray-500 dark:text-[#EDEDEC]">
                        ({{ $customer->current_balance > 0 ? 'Owes You' : 'You Owe' }})
                    </span>
                </p>
            </div>
        </div>
        
        @if($customer->address)
            <div class="mt-4">
                <h4 class="font-medium dark:text-[#EDEDEC] text-gray-900">Address</h4>
                <p class="text-gray-600">{{ $customer->address }}</p>
            </div>
        @endif
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow">
    <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8 px-6">
                <button class="border-transparent dark:text-[#EDEDEC] text-gray-500 hover:text-gray-700 dark:hover:text-[#EDEDEC] hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Orders ({{ $customer->orders->count() }})
                </button>
                <button class="border-transparent dark:text-[#EDEDEC] text-gray-500 hover:text-gray-700 dark:hover:text-[#EDEDEC] hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Payments ({{ $customer->payments->count() }})
                </button>
                <button class="border-transparent dark:text-[#EDEDEC] text-gray-500 hover:text-gray-700 dark:hover:text-[#EDEDEC] hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Invoices ({{ $customer->invoices->count() }})
                </button>
                <button class="border-transparent dark:text-[#EDEDEC] text-gray-500 hover:text-gray-700 dark:hover:text-[#EDEDEC] hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Ledger ({{ $customer->ledgerEntries->count() }})
                </button>
            </nav>
        </div>

    <div class="p-6">
            <!-- Recent Orders -->
            <div class="mb-8">
                <h3 class="text-lg font-medium dark:text-white text-gray-900 mb-4">Recent Orders (Ksh {{number_format($customer->orders->sum('total_amount'), 2)}})</h3>
                @if($customer->orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:text-white">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Order #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($customer->orders()->latest()->take(5)->get() as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-[#EDEDEC] text-blue-600">
                                            <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                            KSh {{ number_format($order->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                                @elseif($order->payment_status === 'partial') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                            KSh {{ number_format($order->balance, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="dark:text-[#EDEDEC] text-gray-500">No orders found.</p>
                @endif
            </div>

            <!-- Recent Payments -->
            <div class="mb-8">
                <h3 class="text-lg font-medium dark:text-white text-gray-900 mb-4">Recent Payments (Ksh {{number_format($customer->payments->sum('amount'), 2)}})</h3>
                @if($customer->payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:text-white">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Payment #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Reference</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($customer->payments()->latest()->take(5)->get() as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-[#EDEDEC] text-blue-600">
                                            {{ $payment->payment_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                            {{ $payment->payment_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                            KSh {{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                            {{ $payment->reference_number ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="dark:text-[#EDEDEC] text-gray-500">No payments found.</p>
                @endif
            </div>

            <!-- Recent Invoices -->
            <div class="mb-8">
                <h3 class="text-lg font-medium dark:text-white text-gray-900 mb-4">Recent Invoices (Ksh {{number_format($customer->invoices->sum('amount'), 2)}})</h3>
                @if($customer->invoices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:text-white">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Invoice #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($customer->invoices()->latest()->take(5)->get() as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-[#EDEDEC] text-blue-600">
                                            <a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                            {{ $invoice->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                            KSh {{ number_format($invoice->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($invoice->status === 'paid') bg-green-100 text-green-800
                                                @elseif($invoice->status === 'sent') bg-blue-100 text-blue-800
                                                @elseif($invoice->status === 'overdue') bg-red-100 text-red-800
                                                @elseif($invoice->status === 'cancelled') bg-gray-100 text-gray-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="dark:text-[#EDEDEC] text-gray-500">No invoices found.</p>
                @endif
            </div>

            <!-- Recent Ledger Entries -->
            <div class="mb-8">
                <h3 class="text-lg font-medium dark:text-white text-gray-900 mb-4">Recent Ledger Entries</h3>
                @if($customer->ledgerEntries->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:text-white">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Balance</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Reference</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @php $runningBalance = $customer->current_balance; @endphp
                                @foreach($customer->ledgerEntries()->latest()->take(5)->get() as $entry)
                                    @php
                                        $isCredit = strtolower($entry->type) === 'credit';
                                        $isDebit = strtolower($entry->type) === 'debit';
                                        $amountClass = $isCredit ? 'text-green-600 bg-green-50 dark:bg-green-950' : ($isDebit ? 'text-red-600 bg-red-50 dark:bg-red-950' : 'text-blue-600 bg-blue-50 dark:bg-blue-950');
                                        $balanceClass = $runningBalance >= 0 ? 'text-green-600 font-bold' : 'text-red-600 font-bold';
                                        $refType = $entry->reference_type ?? null;
                                        $refId = $entry->reference_id ?? null;
                                        $ref = '-';
                                        if ($refType && $refId) {
                                            if (str_contains($refType, 'Order')) {
                                                $order = \App\Models\Order::find($refId);
                                                $ref = $order ? $order->order_number : '-';
                                            } elseif (str_contains($refType, 'Payment')) {
                                                $payment = \App\Models\Payment::find($refId);
                                                $ref = $payment ? $payment->payment_number : '-';
                                            } elseif (str_contains($refType, 'Invoice')) {
                                                $invoice = \App\Models\Invoice::find($refId);
                                                $ref = $invoice ? $invoice->invoice_number : '-';
                                            } else {
                                                $ref = class_basename($refType) . ' #' . $refId;
                                            }
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">{{ $entry->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold {{ $isCredit ? 'text-green-700' : ($isDebit ? 'text-red-700' : 'text-blue-700') }}">{{ ucfirst($entry->type) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold {{ $amountClass }}">KSh {{ number_format($entry->amount, 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm {{ $balanceClass }}">KSh {{ number_format($runningBalance, 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-600 dark:text-blue-300">{{ $ref }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">{{ $entry->description }}</td>
                                    </tr>
                                    @php
                                        $runningBalance -= $isDebit ? $entry->amount : ($isCredit ? -$entry->amount : 0);
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="dark:text-[#EDEDEC] text-gray-500">No ledger entries found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
