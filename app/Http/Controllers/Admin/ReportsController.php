<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use App\Models\Products;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Warehouses;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use PhpOffice\PhpSpreadsheet\{Writer\Pdf as PdfAlias};

class ReportsController extends Controller
{
    public function productSalesReport(Request $request)
    {
        $filters = $request->validate([
            'period' => ['nullable', 'in:daily,monthly,yearly'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'month' => ['nullable', 'date_format:Y-m'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $period = $filters['period'] ?? 'daily';
        $selectedDate = match ($period) {
            'monthly' => \Carbon\Carbon::createFromFormat('!Y-m', $filters['month'] ?? now()->format('Y-m')),
            'yearly' => \Carbon\Carbon::create((int) ($filters['year'] ?? now()->year), 1, 1),
            default => \Carbon\Carbon::parse($filters['date'] ?? now()->format('Y-m-d')),
        };

        [$start, $end] = match ($period) {
            'monthly' => [$selectedDate->copy()->startOfMonth(), $selectedDate->copy()->endOfMonth()],
            'yearly' => [$selectedDate->copy()->startOfYear(), $selectedDate->copy()->endOfYear()],
            default => [$selectedDate->copy()->startOfDay(), $selectedDate->copy()->endOfDay()],
        };

        $products = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->whereBetween('sales.date', [$start->toDateString(), $end->toDateString()])
            ->select(
                'products.id', 'products.name', 'products.code',
                DB::raw('SUM(sma_sale_items.quantity) as units_sold'),
                DB::raw('SUM(sma_sale_items.quantity * sma_sale_items.sale_price) as revenue'),
                DB::raw('COUNT(DISTINCT sma_sales.id) as order_count')
            )
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderByDesc('units_sold')
            ->get();

        $summary = [
            'units' => (int) $products->sum('units_sold'),
            'revenue' => (float) $products->sum('revenue'),
            'orders' => (int) DB::table('sales')
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->whereExists(function ($query) {
                    $query->selectRaw('1')->from('sale_items')->whereColumn('sale_items.sale_id', 'sales.id');
                })->count(),
            'products' => $products->count(),
        ];

        return view('admin.reports.product-sales', compact('period', 'selectedDate', 'products', 'summary'));
    }

    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $sales = Sale::with('items')->whereYear('date', $year)->get();
        $purchases = Purchase::with('items')->whereYear('date', $year)->get();

        $salesByMonth = $sales->groupBy(fn ($sale) => (int) \Carbon\Carbon::parse($sale->date)->month);
        $purchasesByMonth = $purchases->groupBy(fn ($purchase) => (int) \Carbon\Carbon::parse($purchase->date)->month);

        $chart = [
            'labels' => collect(range(1, 12))->map(fn ($month) => \Carbon\Carbon::create($year, $month, 1)->format('M'))->values(),
            'sales' => collect(range(1, 12))->map(fn ($month) => $salesByMonth->get($month, collect())->sum('total_amount'))->values(),
            'purchases' => collect(range(1, 12))->map(fn ($month) => $purchasesByMonth->get($month, collect())->sum('total_amount'))->values(),
            'items' => collect(range(1, 12))->map(fn ($month) => $salesByMonth->get($month, collect())->sum(fn ($sale) => $sale->items->sum('quantity')))->values(),
        ];

        $summary = [
            'products' => Products::count(),
            'sales' => $sales->sum('total_amount'),
            'items' => collect($sales)->sum(fn ($sale) => $sale->items->sum('quantity')),
            'purchases' => $purchases->sum('total_amount'),
        ];

        return view('admin.reports.index', compact('year', 'summary', 'chart'));
    }

    public function monthly_sales(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $monthNumber] = array_pad(explode('-', $month), 2, null);

        if (!checkdate((int) $monthNumber, 1, (int) $year)) {
            $month = now()->format('Y-m');
            [$year, $monthNumber] = explode('-', $month);
        }

        $sales = Sale::with(['customer', 'payments', 'items'])
            ->whereYear('date', $year)
            ->whereMonth('date', $monthNumber)
            ->when($request->filled('warehouse_id'), fn ($q) => $q->where('warehouse_id', $request->warehouse_id))
            ->when($request->filled('customer_id'), fn ($q) => $q->where('customer_id', $request->customer_id))
            ->when($request->filled('search'), fn ($q) => $q->where('reference', 'like', '%' . $request->search . '%'))
            ->orderBy('date')
            ->get();

        $totalSales = $sales->sum('total_amount');
        $totalPaid = $sales->sum(fn ($sale) => $sale->payments->sum('amount'));

        $dailyGrouped = $sales->groupBy(fn ($sale) => $sale->date->format('Y-m-d'));
        $daily = [
            'labels' => $dailyGrouped->keys()->values(),
            'values' => $dailyGrouped->map(fn ($group) => $group->sum('total_amount'))->values(),
        ];

        $summary = [
            'total_sales' => $totalSales,
            'total_paid' => $totalPaid,
            'total_balance' => $totalSales - $totalPaid,
            'total_orders' => $sales->count(),
            'total_items' => $sales->sum(fn ($sale) => $sale->items->sum('quantity')),
        ];

        $paymentStatus = [
            'paid' => $sales->where('payment_status', 'paid')->count(),
            'pending' => $sales->where('payment_status', 'pending')->count(),
        ];

        return view('admin.reports.monthly-sales', [
                'pageTitle' => __('messages.monthly_sales'),
                'heading' => __('messages.monthly_sales'),
                'breadcrumbs' => [
                    ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                    ['label' => __('messages.reports'), 'url' => '', 'active' => true],
                    ['label' => __('messages.monthly_sales'), 'url' => '', 'active' => true],
                ],
                'sales' => $sales,
                'summary' => $summary,
                'daily' => $daily,
                'paymentStatus' => $paymentStatus,
                'warehouses' => Warehouses::select('id', 'name')->get(),
                'billers' => Companies::select('id', 'name')->where('group_id', 4)->get(),
                'month' => $month,
        ]);
    }



    public function dailySalesReport(Request $request)
    {
        $date = $request->input('created_at', now()->format('Y-m-d'));

        $sales = $this->getFilteredDailySales($request, $date);

        // ----- Summary cards -----
        $totalSales = $sales->sum('total_amount');
        $totalPaid = $sales->sum(fn($sale) => $sale->payments->sum('amount'));

        $summary = [
            'total_sales' => $totalSales,
            'total_paid' => $totalPaid,
            'total_balance' => $totalSales - $totalPaid,
            'total_orders' => $sales->count(),
        ];

        // ----- Hourly chart: bucket sales by hour of day (uses created_at, since
        // the `date` column is a DATE type with no time component) -----
        $hourlyGrouped = $sales->groupBy(fn($sale) => $sale->created_at->format('ga'));
        $hourly = [
            'labels' => $hourlyGrouped->keys()->values(),
            'values' => $hourlyGrouped->map(fn($group) => $group->sum('total_amount'))->values(),
        ];

        // ----- Payment status pie -----
        $paymentStatus = [
            'completed' => $sales->where('payment_status', 'completed')->count(),
            'pending' => $sales->where('payment_status', 'pending')->count(),
        ];

        $warehouses = Warehouses::select('id', 'name')->get();
        $billers = Companies::select('id', 'name')->where('group_id', 4)->get();

        return view('admin.reports.daily_sales', [
            'pageTitle' => __('messages.daily_report'),
            'heading' => __('messages.stock_management_system'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                ['label' => __('messages.daily_sales'), 'url' => '', 'active' => true],
            ],
            'sales' => $sales,
            'summary' => $summary,
            'hourly' => $hourly,
            'paymentStatus' => $paymentStatus,
            'warehouses' => $warehouses,
            'billers' => $billers,
            'date' => $date,
        ]);
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
