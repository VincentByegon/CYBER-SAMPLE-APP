<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use OpenAI\Laravel\Facades\OpenAI;

class ReportController extends Controller
{
    public function pdf(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');

        $orders = Order::whereBetween('created_at', [$start, $end])->get();
        $payments = Payment::whereBetween('created_at', [$start, $end])->get();

        $total = $orders->sum('total_amount');
        $totalPayments = $payments->sum('amount');

        // 🔹 Generate summaries using AI
        $ordersSummary = $this->generateAISummary("Orders", $orders, $total);
        $paymentsSummary = $this->generateAISummary("Payments", $payments, $totalPayments);

        $business = [
            'name' => 'Your Business Name',
            'address' => 'Business Address',
            'phone' => 'Business Phone',
            'email' => 'Business Email',
        ];

        $pdf = Pdf::loadView('livewire.reports.orders-report-pdf', compact(
            'orders', 'payments', 'total', 'totalPayments',
            'business', 'start', 'end', 'ordersSummary', 'paymentsSummary'
        ));

        return $pdf->download('orders_report.pdf');
    }

    private function generateAISummary($type, $data, $total)
    {
        if ($data->isEmpty()) {
            return "No {$type} data was recorded in this period.";
        }

        // Prepare a short description for AI
        $sampleData = $data->take(5)->map(function ($item) {
            return $item->toArray();
        })->toJson();

        $prompt = "You are an accountant assistant. Write a concise, human-like business summary (3 sentences max)
        for the {$type} data below. Mention trends, totals, and what they may indicate.
        Data sample: {$sampleData}. Total amount: {$total} KES.";

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You summarize financial reports clearly and professionally.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            return trim($response['choices'][0]['message']['content']);
        } catch (\Exception $e) {
            return "AI summary unavailable (".$e->getMessage().").";
        }
    }
}
