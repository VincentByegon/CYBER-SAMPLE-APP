<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment; // ✅ You forgot this import
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function pdf(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');

        // ✅ Fetch orders and payments between given dates
        $orders = Order::whereBetween('created_at', [$start, $end])->get();
        $payments = Payment::whereBetween('created_at', [$start, $end])->get();

        // ✅ Calculate totals
        $total = $orders->sum('total_amount'); // Field name must match your database column
        $totalPayments = $payments->sum('amount');

        // ✅ Business info (you can later pull this from settings table)
        $business = [
            'name' => config('app.name', 'Your Business Name'),
            'address' => 'Kericho, Nairobi-Kisumu Highway',
            'phone' => '0712 345678',
            'email' => 'info@bigtunescyber.co.ke',
        ];

        // ✅ Generate PDF
        $pdf = Pdf::loadView('livewire.reports.orders-report-pdf', compact(
            'orders', 'payments', 'total', 'totalPayments', 'business', 'start', 'end'
        ));

        return $pdf->download('orders_report.pdf');
    }
}
