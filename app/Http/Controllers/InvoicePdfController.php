<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function show(Invoice $invoice)
    {
        $pdf = Pdf::loadView('livewire.invoices.invoice-pdf', compact('invoice'));
        return $pdf->download('invoice_'.$invoice->invoice_number.'.pdf');
    }
}
