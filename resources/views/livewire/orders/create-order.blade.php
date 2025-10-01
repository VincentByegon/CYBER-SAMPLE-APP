<div class="p-6 bg-white dark:bg-gray-900 dark:text-white min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#EDEDEC]">Create Order</h1>
        <a href="{{ route('orders.index') }}" class="text-blue-400 hover:text-blue-300">← Back to Orders</a>
    </div>

    <form wire:submit.prevent="createOrder">
        <!-- Order Information -->
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-medium text-[#EDEDEC] mb-4">Order Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Customer *</label>
            <select wire:model="customer_id"
                class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-700 rounded-lg px-3 py-2">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                        @endforeach
                    </select>
                    @error('customer_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
              <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Notes</label>
              <input type="text" wire:model="notes"
                  class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-700 rounded-lg px-3 py-2">
                    @error('notes') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
            <label class="block text-sm font-medium dark:text-white text-gray-700 mb-1">Payment Mode *</label>
            <select wire:model="payment_mode"
                class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-700 rounded-lg px-3 py-2">
                        <option value="">Select Payment Mode</option>
                        <option value="cash">Cash</option>
                        <option value="mpesa">M-Pesa</option>
                        <option value="card">Card</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                    @error('payment_mode') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Order Items -->
    <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-[#EDEDEC]">Order Items</h3>
                <button type="button" wire:click="addOrderItem"
                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                    Add Item
                </button>
            </div>

            <div class="space-y-4">
                @foreach($orderItems as $index => $item)
                    <div class="border border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800 dark:text-white">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-[#EDEDEC] mb-1">Service *</label>
                                <select wire:model="orderItems.{{ $index }}.service_id"
                                        class="w-full dark:bg-zinc-800 dark:text-[#EDEDEC] bg-gray-800 text-[#EDEDEC] border border-gray-700 rounded-lg px-3 py-2">
                                    <option value="">Select Service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }} (KSh {{ number_format($service->price, 2) }})</option>
                                    @endforeach
                                </select>
                                @error("orderItems.{$index}.service_id") <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-[#EDEDEC] mb-1">Quantity *</label>
                                <input type="number" wire:model="orderItems.{{ $index }}.quantity" min="1"
                                       class="w-full bg-gray-800 text-[#EDEDEC] border border-gray-700 rounded-lg px-3 py-2">
                                @error("orderItems.{$index}.quantity") <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-[#EDEDEC] mb-1">Unit Price</label>
                                <input type="number" step="0.01" wire:model="orderItems.{{ $index }}.unit_price"
                                       class="w-full bg-gray-800 text-[#EDEDEC] border border-gray-700 rounded-lg px-3 py-2">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-[#EDEDEC] mb-1">Total</label>
                                <input type="text" value="KSh {{ number_format($item['total_price'] ?? 0, 2) }}" readonly
                                       class="w-full bg-gray-700 text-[#EDEDEC] border border-gray-600 rounded-lg px-3 py-2">
                            </div>
                            
                            <div>
                                @if(count($orderItems) > 1)
                                    <button type="button" wire:click="removeOrderItem({{ $index }})"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">
                                        Remove
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Discount, Payment & Totals -->
            <div class="mt-6 pt-4 border-t border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <div>
                        <label class="block text-sm font-medium text-[#EDEDEC] mb-1">Discount (KES)</label>
                        <input type="number" step="0.01" wire:model.lazy="discount"
                               class="w-full bg-gray-900 text-[#EDEDEC] border border-gray-700 rounded-lg px-3 py-2">
                        @error('discount') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#EDEDEC] mb-1">Payment Amount (KES)</label>
                        <input type="number" step="0.01" wire:model.lazy="payment_amount"
                               class="w-full bg-gray-900 text-[#EDEDEC] border border-gray-700 rounded-lg px-3 py-2">
                        @error('payment_amount') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="text-right">
                        <div class="text-sm text-zinc-400">Subtotal</div>
                        <div class="text-lg font-semibold text-[#EDEDEC]">KSh {{ number_format($subtotal, 2) }}</div>

                        <div class="text-sm text-zinc-400 mt-1">Discount</div>
                        <div class="text-lg font-semibold text-[#EDEDEC]">KSh {{ number_format(min($discount, $subtotal), 2) }}</div>

                        <div class="text-sm text-zinc-400 mt-1">Total</div>
                        <div class="text-lg font-semibold text-[#EDEDEC]">KSh {{ number_format($totalAmount, 2) }}</div>

                        <div class="text-sm text-zinc-400 mt-1">Payment</div>
                        <div class="text-lg font-semibold text-[#EDEDEC]">KSh {{ number_format($payment_amount, 2) }}</div>

                        <div class="text-sm text-zinc-400 mt-1">Change</div>
                        <div class="text-lg font-semibold text-[#EDEDEC]">KSh {{ number_format($change, 2) }}</div>

                        <div class="text-sm text-zinc-400 mt-1">Balance</div>
                        <div class="text-lg font-semibold text-[#EDEDEC]">KSh {{ number_format($balance, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('orders.index') }}"
               class="px-4 py-2 border border-gray-700 rounded-lg text-[#EDEDEC] hover:bg-gray-700">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Create Order
            </button>
        </div>
    </form>
</div>
