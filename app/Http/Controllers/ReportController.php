<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function pdf(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');

        $orders = Order::whereBetween('created_at', [$start, $end])
            ->with('customer')
            ->get();

        $total = $orders->sum('total_amount');

        $payments = Payment::whereBetween('created_at', [$start, $end])
            ->with('customer')
            ->get();

        $totalPayments = $payments->sum('amount');

        $business = [
            'name' => config('app.name', 'BigTunes Cyber'),
            'address' => 'Kericho, Kenya',
            'phone' => '+254 700 000000',
            'email' => 'info@bigtunescyber',
        ];

        // 🧠 Generate AI Summary
        $aiSummary = $this->generateAISummary($orders, $payments, $total, $totalPayments);

        // If AI fails or no data exists
        if (!$aiSummary || str_contains($aiSummary, 'unavailable')) {
            $aiSummary = "During this period, {$business['name']} recorded total order revenue of KES " . 
                number_format($total, 2) . 
                " and received KES " . 
                number_format($totalPayments, 2) . 
                " in payments. Financial data suggests steady operations and consistent customer activity.";
        }

        $pdf = Pdf::loadView('livewire.reports.orders-report-pdf', compact(
            'orders',
            'payments',
            'total',
            'totalPayments',
            'start',
            'end',
            'business',
            'aiSummary'
        ));

        return $pdf->download("orders_report_{$start}_to_{$end}.pdf");
    }

    /**
     * Generate AI Summary with retry logic & graceful fallback
     */


protected function generateAISummary($orders, $payments, $total, $totalPayments)
{
    $attempts = 0;
    $maxAttempts = 3;

    // Convert to collections (in case arrays are passed)
    $ordersCollection = collect($orders);
    $paymentsCollection = collect($payments);

    // Format recent orders (limit to 10 for brevity)
    $ordersText = $ordersCollection
        ->take(10)
        ->map(function ($o) {
            $customerName = optional($o->customer)->name ?? 'N/A';
            $total = isset($o->total_amount) ? number_format((float)$o->total_amount, 2) : '0.00';
            $status = $o->status ?? 'unknown';
            $date = $o->created_at ? Carbon::parse($o->created_at)->format('d M Y') : 'unknown date';

            return "{$customerName} - KES {$total} ({$status}) on {$date}";
        })
        ->join("\n");

    // Format recent payments (limit to 10)
    $paymentsText = $paymentsCollection
        ->take(10)
        ->map(function ($p) {
            $payer = optional($p->customer)->name ?? 'N/A';
            $amount = isset($p->amount) ? number_format((float)$p->amount, 2) : '0.00';
            $method = $p->method ?? 'unknown';
            $date = $p->created_at ? Carbon::parse($p->created_at)->format('d M Y') : 'unknown date';

            return "{$payer} paid KES {$amount} via {$method} on {$date}";
        })
        ->join("\n");

    // Construct AI prompt
    $prompt = "
You are an AI financial assistant. Summarize the business performance based on the following data.

Orders:
$ordersText

Payments:
$paymentsText

Total order revenue: KES $total
Total payments received: KES $totalPayments

Provide a short, insightful paragraph summarizing financial health and trends (no bullet points, just text).
";

    // Attempt AI call with retry logic
    while ($attempts < $maxAttempts) {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a financial analyst who writes concise, insightful summaries.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            return trim($response['choices'][0]['message']['content']);
        } catch (\Exception $e) {
            $attempts++;

            if (str_contains($e->getMessage(), 'rate limit')) {
                sleep(2); // wait before retrying
                continue;
            }

            Log::error('AI Summary Error: ' . $e->getMessage());
            return '⚠️ AI summary unavailable (' . $e->getMessage() . ')';
        }
    }

    return '⚠️ AI summary temporarily unavailable (rate limit exceeded, please try again later).';
}

}
