<?php
namespace App\Http\Controllers;

use App\Models\MonthlyInvoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MonthlyInvoicePdfController extends Controller
{
    public function show($id)
    {
        $monthlyInvoice = MonthlyInvoice::with('customer')->findOrFail($id);
        $orders = Order::whereYear('created_at', $monthlyInvoice->year)
            ->whereMonth('created_at', $monthlyInvoice->month);
        if ($monthlyInvoice->customer_id) {
            $orders->where('customer_id', $monthlyInvoice->customer_id);
        }
        $orders = $orders->get();

        $pdf = Pdf::loadView('livewire.invoices.monthly-invoice-pdf', compact('monthlyInvoice', 'orders'));
        return $pdf->download('monthly-invoice-'.$monthlyInvoice->id.'.pdf');
    }
}
