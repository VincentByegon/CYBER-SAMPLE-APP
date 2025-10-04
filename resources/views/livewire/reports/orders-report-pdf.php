<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders Report</title>
    <style>
        body {
            font-family: 'Segoe UI', 'DejaVu Sans', Arial, sans-serif;
            color: #222;
            margin: 0;
            padding: 0;
            background: #f8fafc;
        }
        .header {
            background: linear-gradient(90deg, #2563eb 0%, #38bdf8 100%);
            color: #fff;
            padding: 30px 40px 20px 40px;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.2em;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 1.1em;
        }
        .summary {
            background: #fff;
            margin: 30px 40px 0 40px;
            padding: 20px 30px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(56,189,248,0.07);
            font-size: 1.1em;
        }
        .summary strong {
            color: #2563eb;
        }
        table {
            width: 90%;
            margin: 30px auto 0 auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(56,189,248,0.07);
        }
        th, td {
            padding: 12px 10px;
            font-size: 1em;
            text-align: left;
        }
        th {
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #38bdf8;
        }
        tr:nth-child(even) {
            background: #f1f5f9;
        }
        tr:hover {
            background: #e0e7ef;
        }
        .footer {
            width: 90%;
            margin: 40px auto 0 auto;
            text-align: right;
        }
        .signature-block {
            margin-top: 60px;
            display: inline-block;
            text-align: left;
        }
        .signature-line {
            border-bottom: 2px solid #2563eb;
            width: 250px;
            margin-bottom: 8px;
        }
        .signature-label {
            font-size: 1em;
            color: #2563eb;
            font-weight: 500;
        }
        @page {
            margin: 40px 0 80px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $business['name'] }}</h1>
        <p>
            {{ $business['address'] }}<br>
            {{ $business['phone'] }} | {{ $business['email'] }}
        </p>
    </div>

    <div class="summary">
        <strong>Orders Report</strong><br>
        <span>Period:</span> <strong>{{ $start }} to {{ $end }}</strong><br>
        <span>Total Orders Value:</span> <strong>{{ number_format($total, 2) }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Order No</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $i => $order)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>{{ $order->order_number ?? $order->id }}</td>
                <td>{{ number_format($order->total_amount, 2) }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-block">
            <div class="signature-line"></div>
            <div class="signature-label">Signature</div>
            <div style="height: 20px;"></div>
            <div class="signature-line"></div>
            <div class="signature-label">Name</div>
        </div>
    </div>
</body>
</html>