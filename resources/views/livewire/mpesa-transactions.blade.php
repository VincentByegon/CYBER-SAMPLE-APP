<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">M-Pesa Transactions</h2>
        <input type="text" wire:model.debounce.500ms="search" 
               placeholder="Search transactions..." 
               class="border rounded px-2 py-1" />
    </div>

    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">#</th>
                <th class="border px-3 py-2">Receipt</th>
                <th class="border px-3 py-2">Customer</th>
                <th class="border px-3 py-2">Phone</th>
                <th class="border px-3 py-2">Amount</th>
                <th class="border px-3 py-2">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $txn)
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2">{{ $transactions->firstItem() + $index }}</td>
                    <td class="border px-3 py-2 font-mono">{{ $txn->reference_number }}</td>
                    <td class="border px-3 py-2">{{ $txn->customer_name ?? 'N/A' }}</td>
                    <td class="border px-3 py-2">{{ $txn->phone_number ?? 'N/A' }}</td>
                    <td class="border px-3 py-2 text-green-700 font-bold">KES {{ number_format($txn->amount, 2) }}</td>
                    <td class="border px-3 py-2">{{ $txn->created_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-3">No transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
