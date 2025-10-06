<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders & Payments Report</title>
    <style>
         @page {
            margin: 100px 40px;
        }
        body {
            font-family: 'Segoe UI', 'DejaVu Sans', Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-size: 13.5px;
            line-height: 1.6;
        }

        /* HEADER */
         footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            border-top: 1px solid #cbd5e1;
            font-family: 'Segoe UI', sans-serif;
            font-size: 12px;
        }

        .header {
            background-color: #f2e8e5;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #d1d5db;
        }
        .header-left {
            font-size: 13px;
            color: #374151;
        }
        .header-right img {
            height: 50px;
        }

        .container {
            width: 85%;
            margin: 40px auto;
        }

        h1.title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: 30px;
        }

        h2.section {
            font-size: 16px;
            font-weight: 700;
            margin-top: 25px;
            margin-bottom: 10px;
            color: #111827;
        }

        p {
            color: #374151;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 25px 0;
        }
        th, td {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .summary-text {
            background: #f1f5f9;
            border-left: 5px solid #2563eb;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            line-height: 1.6;
        }
        .summary-text p {
            margin: 0;
            font-size: 15px;
        }
        .footer {
            width: 85%;
            margin: 60px auto 40px auto;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            text-align: right;
            font-size: 12px;
            color: #6b7280;
        }

         .signature-section {
            margin-top: 80px;
            text-align: center;
        }

        .signature-line {
            display: inline-block;
            width: 250px;
            border-bottom: 1px solid #000;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
  <footer>
          Page {{PAGE_NUM}} of {{PAGE_COUNT}} — Confidential Report
    </footer>
    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            {{ $business['address'] }} | {{ $business['email'] }} | {{ $business['phone'] }} <br>
            Generated at: {{ now()->format('d M Y, h:i A') }}
        </div>
        
    </div>

    <div class="container">
        <h1 class="title">Orders & Payments Summary</h1>

        <h2 class="section">Overview:</h2>
        <p>
            This report provides an overview of <strong>{{ $business['name'] }}</strong>’s 
            operations between <strong>{{ $start }}</strong> and <strong>{{ $end }}</strong>. 
            It highlights revenue from orders and payments received within the selected period.
        </p>

        <h2 class="section">Revenue Analysis:</h2>
       <div class="summary-text">
            <p>
                During this period, <strong>{{ config('app.name') }}</strong> recorded total order revenue of 
                <strong>KES {{ number_format($total, 2) }}</strong>. 
                Payments received during the same period amounted to 
                <strong>KES {{ number_format($totalPayments, 2) }}</strong>, 
                resulting in a balance of 
                <strong>KES {{ number_format($total - $totalPayments, 2) }}</strong>. 
                This difference represents pending collections or outstanding credit from customers.
            </p>
        </div>

        <h2 class="section">Order Breakdown:</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Order No</th>
                    <th>Total (KES)</th>
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
                    <td>{{ ucfirst($order->status ?? 'Pending') }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h2 class="section">Payments Summary:</h2>
        @if($payments->isEmpty())
            <p>No payments were recorded during this period.</p>
        @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th>Amount (KES)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $i => $payment)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $payment->customer->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($payment->payment_method ?? '-') }}</td>
                    <td>{{ $payment->reference_number ?? '-' }}</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <h2 class="section">Financial Insight:</h2>
        <p>
            The above figures reflect steady inflows through M-Pesa and cash payments, 
            with credit customers actively reducing their outstanding balances. 
            Walk-in clients accounted for a significant share of immediate revenue.
        </p>
    </div>

       <div class="signature-section">
            <div class="signature-line"></div><br>
            <small>Approved by: {{ auth()->user()->name ?? 'Authorized Staff' }}</small>
        </div>
    </div>
  
   <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Helvetica", "normal");
                $pdf->text(500, 820, "Page $PAGE_NUM of $PAGE_COUNT", $font, 10);
            ');
        }
    </script>
</body>
</html>
