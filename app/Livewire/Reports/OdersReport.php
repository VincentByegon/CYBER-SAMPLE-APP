<?php
namespace App\Livewire\Reports;

use Livewire\Component;

class OrdersReport extends Component
{
    public $start_date;
    public $end_date;
    public $reportUrl;

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    public function generateReport()
    {
        $this->validate();

        // Generate the URL for the PDF report (adjust route name as needed)
        $this->reportUrl = route('reports.orders.pdf', [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
    }

    public function render()
    {
        return view('livewire.reports.oders-report');
    }
}