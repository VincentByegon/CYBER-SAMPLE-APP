<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders Report</title>
    <style>
        body {
            font-family: 'Segoe UI', 'DejaVu Sans', Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background: #f8fafc;
            font-size: 14px;
            line-height: 1.5;
        }

        /* HEADER */
        .header {
            background: linear-gradient(90deg, #0f172a 0%, #2563eb 100%);
            color: #fff;
            padding: 40px 50px 30px 50px;
            border-bottom: 5px solid #38bdf8;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.6em;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 1.05em;
            opacity: 0.9;
        }

        /* SUMMARY CARD */
        .summary {
            background: #fff;
            margin: 40px auto 20px auto;
            padding: 25px 35px;
            border-radius: 16px;
            width: 85%;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
            font-size: 1.1em;
        }
        .summary h2 {
            margin: 0 0 15px 0;
            font-size: 1.4em;
            color: #2563eb;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .summary p {
            margin: 6px 0;
        }
        .summary strong {
            color: #0f172a;
        }

        /* TABLE */
        table {
            width: 85%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 14px 12px;
            font-size: 0.95em;
            text-align: left;
        }
        th {
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        tr:hover {
            background: #f1f5f9;
        }

        /* FOOTER */
        .footer {
            width: 85%;
            margin: 50px auto 0 auto;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 0.9em;
            color: #64748b;
        }
        .footer .signature-block {
            margin-top: 40px;
            text-align: left;
        }
        .signature-line {
            border-bottom: 2px solid #2563eb;
            width: 240px;
            margin-bottom: 6px;
        }
        .signature-label {
            font-size: 0.95em;
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
            {{ $business['address'] }} • {{ $business['phone'] }} • {{ $business['email'] }}
        </p>
    </div>

    <div class="summary">
        <h2>Executive Summary</h2>
        <p><strong>Report Period:</strong> {{ $start }} – {{ $end }}</p>
        <p><strong>Total Orders Value:</strong> ${{ number_format($total, 2) }}</p>
        <p><strong>Number of Orders:</strong> {{ $orders->count() }}</p>
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
                <td>${{ number_format($order->total_amount, 2) }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>
            <p>Generated on {{ now()->format('d M Y, H:i') }}</p>
        </div>
        <div class="signature-block">
            <div class="signature-line"></div>
            <div class="signature-label">Authorized Signature</div>
            <div style="height: 18px;"></div>
            <div class="signature-line"></div>
            <div class="signature-label">Name & Title</div>
        </div>
    </div>
</body>
</html>
