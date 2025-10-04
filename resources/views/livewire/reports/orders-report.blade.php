<div class="p-6 bg-white dark:bg-gray-900 dark:text-white">
    <h1 class="text-2xl font-bold mb-6">Orders Report</h1>

    <form wire:submit.prevent="generateReport" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-[#EDEDEC] mb-1">Start Date *</label>
                <input type="date" wire:model="start_date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-[#EDEDEC] mb-1">End Date *</label>
                <input type="date" wire:model="end_date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Generate PDF Report
        </button>
    </form>

    @if($reportUrl)
        <div class="mt-4">
            <a href="{{ $reportUrl }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Download PDF
            </a>
        </div>
    @endif
</div>