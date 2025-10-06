<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders & Payments Report</title>
    <style>
        body {
            font-family: 'Segoe UI', 'DejaVu Sans', Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        h1, h2, h3 {
            margin: 0;
            color: #0f172a;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }
        .business-info {
            margin-bottom: 20px;
            font-size: 13px;
            text-align: center;
        }
        .report-dates {
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
        }
        th {
            background-color: #f1f5f9;
            font-weight: 600;
        }
        .summary {
            margin-top: 25px;
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
        }
        .summary h3 {
            margin-bottom: 8px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #64748b;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $business['name'] }}</h1>
        <p class="business-info">
            {{ $business['address'] }}<br>
            Phone: {{ $business['phone'] }} | Email: {{ $business['email'] }}
        </p>
        <h2>Orders & Payments Report</h2>
        <p class="report-dates">From <strong>{{ $start }}</strong> to <strong>{{ $end }}</strong></p>
    </div>

    <h3>Orders</h3>
    @if($orders->isEmpty())
        <p>No orders found in this date range.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->customer->name ?? 'Walk-in' }}</td>
                <td>KES {{ number_format($order->total_amount, 2) }}</td>
                <td>{{ ucfirst($order->status ?? 'N/A') }}</td>
                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="summary">
        <h3>Orders Summary</h3>
        <p><strong>Total Orders:</strong> {{ $orders->count() }}</p>
        <p><strong>Total Order Value:</strong> KES {{ number_format($total, 2) }}</p>
    </div>

   <h3>Payments</h3>
@if($payments->isEmpty())
    <p>No payments found in this date range.</p>
@else
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Method</th>
            <th>Reference No.</th>
            <th>Amount (KES)</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $index => $payment)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $payment->customer->name ?? 'Unknown' }}</td>
            <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
            <td>{{ $payment->reference_number ?? '—' }}</td>
            <td>{{ number_format($payment->amount, 2) }}</td>
            <td>{{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d M Y, H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

    <div class="summary">
        <h3>Payments Summary</h3>
        <p><strong>Total Payments:</strong> {{ $payments->count() }}</p>
        <p><strong>Total Amount Paid:</strong> KES {{ number_format($totalPayments, 2) }}</p>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y, H:i') }} by {{ config('app.name') }}</p>
    </div>
</body>
</html>
