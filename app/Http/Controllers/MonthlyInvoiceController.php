<?php
namespace App\Http\Controllers;

use App\Models\MonthlyInvoice;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class MonthlyInvoiceController extends Controller
{
    public function index()
    {
        $monthlyInvoices = MonthlyInvoice::with('customer')->orderByDesc('year')->orderByDesc('month')->get();
        return view('livewire.invoices.monthly-invoice-index', compact('monthlyInvoices'));
    }

    public function create(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        return view('livewire.invoices.create-monthly-invoice', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $month = $request->month;
        $year = $request->year;
        $customerId = $request->customer_id;

        // Prevent duplicate monthly invoice for the same company, month, and year
        $exists = MonthlyInvoice::where('month', $month)
            ->where('year', $year)
            ->where('customer_id', $customerId)
            ->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'A monthly invoice for this company and month already exists.']);
        }

        $ordersQuery = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
        if ($customerId) {
            $ordersQuery->where('customer_id', $customerId);
        }
        $orders = $ordersQuery->get();
        $totalAmount = $orders->sum('total_amount');

        $monthlyInvoice = MonthlyInvoice::create([
            'customer_id' => $customerId,
            'month' => $month,
            'year' => $year,
            'total_amount' => $totalAmount,
            'generated_at' => now(),
        ]);

        return redirect()->route('monthly-invoices.index')->with('success', 'Monthly invoice created.');
    }

    public function show($id)
    {
        $monthlyInvoice = MonthlyInvoice::with('customer')->findOrFail($id);
        $orders = Order::whereYear('created_at', $monthlyInvoice->year)
            ->whereMonth('created_at', $monthlyInvoice->month);
        if ($monthlyInvoice->customer_id) {
            $orders->where('customer_id', $monthlyInvoice->customer_id);
        }
        $orders = $orders->get();
        return view('livewire.invoices.show-monthly-invoice', compact('monthlyInvoice', 'orders'));
    }
}
