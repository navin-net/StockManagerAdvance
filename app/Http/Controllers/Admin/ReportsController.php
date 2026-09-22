<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use App\Models\Sale;
use App\Models\Warehouses;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
//use PhpOffice\PhpSpreadsheet\{Writer\Pdf as PdfAlias};

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function monthly_sales()
    {

        return view('admin.reports.monthly-sales', [
                'pageTitle' => __('messages.monthly_sales'),
                'heading' => __('messages.monthly_sales'),
                'breadcrumbs' => [
                    ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                    ['label' => __('messages.reports'), 'url' => '', 'active' => true],
                    ['label' => __('messages.monthly_sales'), 'url' => '', 'active' => true],
                ]
        ]);
    }

    public function daily_sales()
    {
        return view('admin.reports.daily-sales', [
            'pageTitle' => __('messages.daily_sales'),
            'heading' => __('messages.daily_sales'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                ['label' => __('messages.reports'), 'url' => '', 'active' => true],
                ['label' => __('messages.daily_sales'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    public function dailySalesReport(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $sales = $this->getFilteredDailySales($request, $date);

        // ----- Summary cards -----
        $totalSales = $sales->sum('total_amount');
        $totalPaid  = $sales->sum(fn ($sale) => $sale->payments->sum('amount'));

        $summary = [
            'total_sales'   => $totalSales,
            'total_paid'    => $totalPaid,
            'total_balance' => $totalSales - $totalPaid,
            'total_orders'  => $sales->count(),
        ];

        // ----- Hourly chart: bucket sales by hour of day (uses created_at, since
        // the `date` column is a DATE type with no time component) -----
        $hourlyGrouped = $sales->groupBy(fn ($sale) => $sale->created_at->format('ga'));
        $hourly = [
            'labels' => $hourlyGrouped->keys()->values(),
            'values' => $hourlyGrouped->map(fn ($group) => $group->sum('total_amount'))->values(),
        ];

        // ----- Payment status pie -----
        $paymentStatus = [
            'completed' => $sales->where('payment_status', 'paid')->count(),
            'pending'   => $sales->where('payment_status', '!=', 'paid')->count(),
        ];

        $warehouses = Warehouses::select('id', 'name')->get();
        $billers = Companies::select('id', 'name')->where('group_id', 4)->get();

        return view('admin.reports.daily_sales', compact(
            'sales', 'summary', 'hourly', 'paymentStatus', 'warehouses', 'billers', 'date'
        ));
    }
    public function dailySalesReportPdf(Request $request)
    {
        $date  = $request->input('date', now()->format('Y-m-d'));
        $sales = $this->getFilteredDailySales($request, $date);

        $totalSales = $sales->sum('total_amount');
        $totalPaid  = $sales->sum(fn ($sale) => $sale->payments->sum('amount'));

        $summary = [
            'total_sales'   => $totalSales,
            'total_paid'    => $totalPaid,
            'total_balance' => $totalSales - $totalPaid,
            'total_orders'  => $sales->count(),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.daily_sales_pdf', compact('sales', 'summary', 'date'));

        return $pdf->stream("daily-sales-report-{$date}.pdf");
    }
    private function getFilteredDailySales(Request $request, string $date)
    {
        return Sale::with(['customer', 'payments'])
            ->whereDate('date', $date)
            ->when($request->filled('warehouse_id'), fn ($q) => $q->where('warehouse_id', $request->warehouse_id))
            ->when($request->filled('customer_id'), fn ($q) => $q->where('customer_id', $request->customer_id))
            ->when($request->filled('search'), fn ($q) => $q->where('reference', 'like', '%' . $request->search . '%'))
            ->orderBy('date')
            ->get();
    }




}
