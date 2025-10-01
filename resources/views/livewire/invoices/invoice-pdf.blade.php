<html>
<head>
    <meta charset="utf-8">
    <title>Invoice PDF</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #fff; color: #222; }
        .container { max-width: 800px; margin: 0 auto; padding: 32px 24px; background: #fff; }
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 18px;
        }
        .company-block {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .logo {
            width: 48px; height: 48px; background: #e3e7f7; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #1a237e; font-size: 1.2em; }
        .company-details {
            font-size: 0.85em;
        }
        .company-name {
            font-size: 1.1em; font-weight: bold; color: #1a237e; margin-bottom: 2px; }
        .company-info { color: #222; font-size: 0.85em; }
        .invoice-block {
            text-align: right;
            min-width: 220px;
            font-size: 0.85em;
            margin-left: auto;
        }
        .invoice-title { font-size: 1.1em; font-weight: bold; color: #7c8fdc; letter-spacing: 2px; margin-bottom: 8px; }
        .invoice-table { width: auto; border-collapse: collapse; }
        .invoice-table th, .invoice-table td { border: none; padding: 2px 10px; font-size: 0.85em; }
        .invoice-table th { background: none; color: #6366f1; font-weight: 600; text-align: right; }
        .invoice-table td { background: none; color: #222; text-align: right; }
        .billto-row { background: #2c3e6c; color: #fff; font-weight: bold; padding: 6px 12px; }
        .billto-block { margin-bottom: 18px; }
        .desc-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .desc-table th, .desc-table td { border: 1px solid #dbeafe; padding: 8px 10px; }
        .desc-table th { background: #2c3e6c; color: #fff; font-weight: 600; }
        .desc-table td { background: #f8fafc; color: #222; }
        .totals-block { float: right; width: 320px; margin-top: 12px; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 10px 10px; border: none; font-size: 1.1em; font-weight: bold; color: #1a237e; border-top: 2px solid #dbeafe; }
        .footer { text-align: center; color: #222; font-size: 1em; margin-top: 2em; }
        .thankyou { font-weight: bold; font-size: 1.1em; margin-top: 1em; }
    </style>
</head>
<body>
<div class="container">
    <div class="header-row">
        <div class="company-block">
            <div class="logo">
                <img src="{{ public_path('favicon.svg') }}" alt="BigTunes Cyber Logo" style="width:40px;height:40px;object-fit:contain;">
            </div>
            <div class="company-details">
                <div class="company-name">BigTunes Cyber</div>
                <div class="company-info">Kericho, Nairobi-Kisumu Highway</div>
                <div class="company-info">Phone: 0712 345678</div>
                <div class="company-info">Email: info@bigtunescyber.co.ke</div>
                <div class="company-info">Website: bigtunescyber.co.ke</div>
            </div>
        </div>
        <div class="invoice-block" style="position: absolute; right: 40px; top: 40px; background: #fff; z-index: 10;">
            <div class="invoice-title">INVOICE</div>
            <table class="invoice-table">
                <tr><th>DATE</th><td>{{ $invoice->created_at->format('M d, Y') }}</td></tr>
                <tr><th>INVOICE #</th><td>{{ $invoice->invoice_number }}</td></tr>
                <tr><th>CUSTOMER ID</th><td>{{ $invoice->customer->id }}</td></tr>
                <tr><th>DUE DATE</th><td>{{ $invoice->due_date->format('M d, Y') }}</td></tr>
            </table>
        </div>
    </div>
    <div class="billto-block">
        <div class="billto-row">BILL TO</div>
        <div style="border:1px solid #dbeafe; padding:10px; background:#f8fafc;">
            <div>{{ $invoice->customer->name }}</div>
            <div>{{ $invoice->customer->email }}</div>
            <div>{{ $invoice->customer->phone }}</div>
        </div>
    </div>
    <table class="desc-table">
        <thead>
            <tr>
                <th>Service</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->orderItems as $item)
            <tr>
                <td>{{ $item->service->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>KSh {{ number_format($item->unit_price, 2) }}</td>
                <td>KSh {{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="width:320px; float:left; margin-top:8px;">
        <div style="border:1px solid #dbeafe; background:#f8fafc; padding:12px; font-size:0.95em; color:#222; border-radius:6px; margin-bottom:18px;">
            <b>Payment method accepted:</b><br>
            Cash, Mpesa
        </div>
        <div style="border:1px solid #dbeafe; background:#fff; padding:12px; font-size:0.95em; color:#222; border-radius:6px; min-height:60px; margin-bottom:8px; display:flex; flex-direction:column; justify-content:flex-end;">
            <span style="font-size:0.95em; color:#222;">Signature: ___________________________</span>
            <span style="font-size:0.95em; color:#222; margin-top:8px;">Name: {{ auth()->user()->name ?? '' }}</span>
        </div>
    </div>
    <div class="totals-block">
        <table class="totals-table">
            <tr>
                <td style="text-align:left;">TOTAL</td>
                <td style="text-align:right;">KSh {{ number_format($invoice->amount, 2) }}</td>
            </tr>
        </table>
        <div style="margin-top:8px;">Make all checks payable to <b>BigTunes Cyber</b></div>
    </div>
    <div style="clear:both;"></div>
    <div class="footer">
        If you have any questions about this invoice, please contact<br>
        <b>0712 345678, info@bigtunescyber.co.ke</b>
        <div class="thankyou">Thank You For Your Business!</div>
    </div>
</div>
</body>
</html>
