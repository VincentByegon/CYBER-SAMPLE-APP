<x-layouts.app :title="__('Dashboard')">
        <div class="mb-2 flex items-center">
            <span class="font-bold text-2xl text-gray-900 dark:text-white">Hi, {{ auth()->user()->name }}</span>
        </div>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight mt-1">
            Cyber Management Dashboard
        </h2>
   

    
    <div class="py-12 bg-white dark:bg-gray-900 dark:text-white">
        <!-- Dashboard Graphs and Tables -->
        <div class="mb-8"></div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 bg-white dark:bg-gray-900">
                <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total Customers</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900">{{ \App\Models\Customer::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total Orders</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900">{{ \App\Models\Order::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total Services</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900">{{ \App\Models\Service::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total Invoices</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900">{{ \App\Models\Invoice::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900">KSh {{ number_format(\App\Models\Payment::sum('amount'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" dark:bg-gray-800 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Outstanding Balance</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900">KSh {{ number_format(\App\Models\Customer::sum('current_balance'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class=" dark:bg-gray-800 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total Orders Today</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class=" dark:bg-gray-800 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-500">Total Revenue Today</p>
                                <p class="text-2xl font-semibold dark:text-[#EDEDEC] text-gray-900"> KSh {{ number_format(\App\Models\Payment::whereDate('created_at', today())->sum('amount'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
             

            </div>

            <!-- Quick Actions -->
            <div class=" dark:bg-gray-800 bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('customers.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-zinc-800">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-900">Manage Customers</p>
                                <p class="text-sm text-gray-500 dark:text-[#EDEDEC]">Add, edit, and view customers</p>
                            </div>
                        </a>

                        <a href="{{ route('orders.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium dark:text-[#EDEDEC] text-gray-900">Create Order</p>
                                <p class="text-sm text-gray-500 dark:text-[#EDEDEC]">Process new customer orders</p>
                            </div>
                        </a>

                        <a href="{{ route('payments.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-[#EDEDEC]">Record Payment</p>
                                <p class="text-sm text-gray-500 dark:text-[#EDEDEC]">Process customer payments</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard Graphs and Tables at the bottom -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Select Year</label>
                    <select id="year" class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 dark:text-white" onchange="updateCharts()">
                        @for($y = date('Y'); $y >= date('Y')-4; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Monthly Revenue</h3>
                    <canvas id="monthlyRevenueChart" height="200"></canvas>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Revenue by Service</h3>
                    <canvas id="servicePieChart" height="200"></canvas>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mt-8">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Orders</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach(\App\Models\Order::latest()->take(10)->get() as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-300">
                                        <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $order->customer->name ?? 'WALK-IN CUSTOMER' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        KSh {{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        // Real backend data for charts
        let monthlyRevenueData = {
            @for($y = date('Y'); $y >= date('Y')-4; $y--)
                {{ $y }}: [
                    @for($m=1;$m<=12;$m++)
                        {{ \App\Models\Payment::whereYear('created_at', $y)->whereMonth('created_at', $m)->sum('amount') }}@if($m<12),@endif
                    @endfor
                ],
            @endfor
        };
        let serviceLabels = [];
        let serviceAmounts = [];
        @php
            $serviceTotals = \App\Models\Service::withSum(['orderItems as total' => function($q) {
                $q->whereYear('created_at', date('Y'));
            }], 'total_price')->get();
        @endphp
        @foreach($serviceTotals as $service)
            serviceLabels.push("{{ $service->name }}");
            serviceAmounts.push({{ $service->total ?? 0 }});
        @endforeach
        let servicePieData = {
            labels: serviceLabels,
            datasets: [{
                data: serviceAmounts,
                backgroundColor: ['#3b82f6','#10b981','#f59e42','#ef4444','#6366f1','#f43f5e','#22d3ee','#a3e635'],
            }]
        };
        let selectedYear = {{ date('Y') }};
        function updateCharts() {
            selectedYear = document.getElementById('year').value;
            monthlyRevenueChart.data.datasets[0].data = monthlyRevenueData[selectedYear];
            monthlyRevenueChart.update();
        }
        const monthlyRevenueChart = new Chart(document.getElementById('monthlyRevenueChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [{
                    label: 'Amount Collected',
                    data: monthlyRevenueData[selectedYear],
                    backgroundColor: '#3b82f6',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        const servicePieChart = new Chart(document.getElementById('servicePieChart').getContext('2d'), {
            type: 'pie',
            data: servicePieData,
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
        </script>
</x-layouts.app>
