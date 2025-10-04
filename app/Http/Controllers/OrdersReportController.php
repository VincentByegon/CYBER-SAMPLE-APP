<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdersReportController extends Controller
{
    public function pdf(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');

        $orders = Order::whereBetween('created_at', [$start, $end])->get();
        $total = $orders->sum('total_amount'); // Adjust field name if needed

        $business = [
            'name' => 'Your Business Name',
            'address' => 'Business Address',
            'phone' => 'Business Phone',
            'email' => 'Business Email',
        ];

        $pdf = Pdf::loadView('livewire.reports.orders-reports-pdf', compact('orders', 'total', 'business', 'start', 'end'));
        return $pdf->download('orders_report.pdf');
    }
}