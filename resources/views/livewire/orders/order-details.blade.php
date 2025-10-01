<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold dark:text-[#EDEDEC] text-gray-900">Order Details</h1>
        <a href="{{ route('orders.index') }}" class="dark:text-[#EDEDEC] text-blue-600 hover:text-blue-800">← Back to Orders</a>
    </div>

    <!-- Order Header -->
    <div class="dark:bg-zinc-800 bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <h3 class="text-lg font-semibold dark:text-[#EDEDEC] text-gray-900">{{ $order->order_number }}</h3>
                <p class=" dark:text-[#EDEDEC] text-gray-600">{{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
            
            <div>
                <h4 class="font-medium  dark:text-[#EDEDEC] text-gray-900">Customer</h4>
                <p class="dark:text-[#EDEDEC] text-gray-600">{{ $order->customer->name??'WALK-IN CUSTOMER' }}</p>
                <p class=" dark:text-[#EDEDEC] text-gray-600">{{ $order->customer->email??'Email: N/A' }}</p>
            </div>
            
            <div>
                <h4 class="font-medium  dark:text-[#EDEDEC] text-gray-900">Status</h4>
                <div class="flex space-x-2 mt-1">
                    <select wire:change="updateStatus($event.target.value)" class="text-sm border dark:bg-zinc-800 dark:text-[#EDEDEC] border-gray-300 rounded px-2 py-1">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium  dark:text-[#EDEDEC] text-gray-900">Payment Status</h4>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    @if($order->payment_status === 'paid') bg-green-100 text-green-800
                    @elseif($order->payment_status === 'partial') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>
        
        @if($order->notes)
            <div class="mt-4">
                <h4 class="font-medium dark:text-[#EDEDEC] text-gray-900">Notes</h4>
                <p class="  dark:text-[#EDEDEC] text-gray-600">{{ $order->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Order Items -->
    <div class="dark:bg-zinc-800 bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-medium dark:text-[#EDEDEC] text-gray-900 mb-4">Order Items</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="dark:bg-zinc-800 bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium dark:text-[#EDEDEC] text-gray-900">{{ $item->service->name }}</div>
                                @if($item->service->description)
                                    <div class="text-sm dark:text-[#EDEDEC] text-gray-500">{{ $item->service->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                KSh {{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-[#EDEDEC] text-gray-900">
                                KSh {{ number_format($item->total_price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="dark:bg-zinc-800 bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium dark:text-[#EDEDEC] text-gray-900">Sub Total:</td>
                        <td class="px-6 py-3 text-sm font-bold dark:text-[#EDEDEC] text-gray-900">KSh {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium dark:text-[#EDEDEC] text-gray-900">Discount:</td>
                        <td class="px-6 py-3 text-sm font-bold dark:text-[#EDEDEC] text-gray-900">KSh {{ number_format($order->discount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium dark:text-[#EDEDEC] text-gray-900">Total Amount:</td>
                        <td class="px-6 py-3 text-sm font-bold dark:text-[#EDEDEC] text-gray-900">KSh {{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium dark:text-[#EDEDEC] text-gray-900">Paid Amount:</td>
                        <td class="px-6 py-3 text-sm font-medium text-green-600">KSh {{ number_format($order->paid_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium dark:text-[#EDEDEC] text-gray-900">Change:</td>
                        <td class="px-6 py-3 text-sm font-medium text-green-600">KSh {{ number_format($order->change, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium dark:text-[#EDEDEC] text-gray-900">Balance:</td>
                        <td class="px-6 py-3 text-sm font-bold text-red-600">KSh {{ number_format($order->balance, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Payments -->
    @if($order->payments->count() > 0)
        <div class=" dark:bg-zinc-800 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium dark:text-[#EDEDEC] text-gray-900 mb-4">Payments</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="dark:bg-zinc-800 bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Payment #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium dark:text-[#EDEDEC] text-gray-500 uppercase">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-[#EDEDEC] text-blue-600">
                                    {{ $payment->payment_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm dark:text-[#EDEDEC] text-gray-900">
                                    {{ $payment->payment_date->format('M d, Y H:i') }}
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
        </div>
    @endif

    <!-- Ledger Entries for this Order -->
    @php
        $orderLedgerEntries = \App\Models\LedgerEntry::where('reference_type', 'App\\Models\\Order')
            ->where('reference_id', $order->id)
            ->latest()->get();
    @endphp
    @if($orderLedgerEntries->count() > 0)
        <div class="dark:bg-zinc-800 bg-white rounded-lg shadow p-6 mt-6">
            <h3 class="text-lg font-medium dark:text-[#EDEDEC] text-gray-900 mb-4">Ledger Entries</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="dark:bg-zinc-800 bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Balance</th>
                            <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Reference</th>
                            <th class="px-4 py-3 text-left text-xs font-bold dark:text-[#EDEDEC] text-gray-700 uppercase">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $runningBalance = $order->current_balance ?? 0; @endphp
                        @foreach($orderLedgerEntries as $entry)
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
                                        $orderRef = \App\Models\Order::find($refId);
                                        $ref = $orderRef ? $orderRef->order_number : '-';
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
        </div>
    @endif
</div>
