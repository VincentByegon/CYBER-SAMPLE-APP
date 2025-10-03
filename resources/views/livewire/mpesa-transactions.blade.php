<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">M-Pesa Transactions</h2>
        <input type="text" wire:model.debounce.500ms="search" 
               placeholder="Search transactions..." 
               class="border rounded px-2 py-1 text-gray-900 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 placeholder-gray-400 focus:ring focus:ring-indigo-500" />
    </div>

    <!-- Responsive wrapper -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="border px-3 py-2 text-left text-gray-700 dark:text-gray-200">#</th>
                    <th class="border px-3 py-2 text-left text-gray-700 dark:text-gray-200">Receipt</th>
                    <th class="border px-3 py-2 text-left text-gray-700 dark:text-gray-200">Customer</th>
                    <th class="border px-3 py-2 text-left text-gray-700 dark:text-gray-200">Phone</th>
                    <th class="border px-3 py-2 text-left text-gray-700 dark:text-gray-200">Amount</th>
                    <th class="border px-3 py-2 text-left text-gray-700 dark:text-gray-200">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $index => $txn)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="border px-3 py-2 text-gray-900 dark:text-gray-100">
                            {{ $transactions->firstItem() + $index }}
                        </td>
                        <td class="border px-3 py-2 font-mono text-gray-900 dark:text-gray-100">
                            {{ $txn->reference_number }}
                        </td>
                        <td class="border px-3 py-2 text-gray-900 dark:text-gray-100">
                            {{ $txn->customer_name ?? 'N/A' }}
                        </td>
                        <td class="border px-3 py-2 text-gray-900 dark:text-gray-100">
                            {{ $txn->phone_number ?? 'N/A' }}
                        </td>
                        <td class="border px-3 py-2 text-green-700 dark:text-green-400 font-bold">
                            KES {{ number_format($txn->amount, 2) }}
                        </td>
                        <td class="border px-3 py-2 text-gray-900 dark:text-gray-100">
                            {{ $txn->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-3 text-gray-600 dark:text-gray-300">
                            No transactions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
