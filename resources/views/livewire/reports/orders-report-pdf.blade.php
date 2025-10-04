<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders Report</title>
    <style>
        body {
            font-family: 'Segoe UI', 'DejaVu Sans', Arial, sans-serif;
            background: #ffffff;
            color: #1e293b;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.6;
        }

        /* HEADER BAR */
        .top-bar {
            background-color: #f2e8e5;
            padding: 18px 50px;
            color: #1f2937;
            font-size: 13px;
            border-bottom: 2px solid #d1d5db;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-bar .left {
            font-weight: 500;
        }
        .top-bar .right {
            font-weight: 600;
            font-size: 14px;
        }

        /* MAIN WRAPPER */
        .container {
            width: 85%;
            margin: 40px auto;
        }

        /* TITLE */
        h1.title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 30px;
            text-align: center;
        }

        /* SECTION HEADINGS */
        h2.section {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 12px;
            color: #374151;
        }

        strong {
            color: #111827;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 25px 0;
        }

        th, td {
            padding: 10px 12px;
            text-align: left;
            border: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* FOOTER */
        .footer {
            width: 85%;
            margin: 40px auto;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            font-size: 12px;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- TOP CONTACT BAR -->
    <div class="top-bar">
        <div class="left">
            {{ $business['address'] }} | {{ $business['email'] }} | {{ $business['phone'] }}
        </div>
        <div class="right">
            {{ strtoupper($business['name']) }}
        </div>
    </div>

    <div class="container">
        <h1 class="title">Sample Orders Summary</h1>

        <h2 class="section">Overview:</h2>
        <p>
            This report provides an overview of the company’s orders performance between 
            <strong>{{ $start }}</strong> and <strong>{{ $end }}</strong>. 
            It highlights order totals, revenue trends, and payment statuses to help assess operational performance.
        </p>

        <h2 class="section">Revenue Analysis:</h2>
        <p>
            During this period, <strong>{{ $business['name'] }}</strong> recorded 
            total order revenue of <strong>${{ number_format($total, 2) }}</strong>.
            The increase reflects growth in customer engagement and efficient service delivery.
        </p>

        <h2 class="section">Order Breakdown:</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Order No</th>
                    <th>Total Amount ($)</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $i => $order)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->order_number ?? $order->id }}</td>
                    <td>{{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p><em>Note:</em> The above table provides a breakdown of all orders recorded during the reporting period.</p>

        <h2 class="section">Profit Insight:</h2>
        <p>
            Based on the total order value and payment status, 
            <strong>{{ $business['name'] }}</strong> maintained a positive revenue stream 
            with consistent customer satisfaction across all services.
        </p>

        <h2 class="section">Cash Flow Overview:</h2>
        <p>
            The cash flow for the period shows continuous inflows from walk-in and company clients, 
            supported by structured ledger tracking for credit customers.
        </p>
    </div>

    <div class="footer">
        Report generated on {{ now()->format('d M Y, H:i') }}
    </div>

</body>
</html>
