<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use App\Http\Controllers\Controller;
use App\Models\{Payment, Products, Sale, SaleItem};
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function index(Request $request)
    {


        $pageTitle = __('messages.sales_list');
        $breadcrumbs = [
            ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
            ['label' => __('messages.sales'), 'url' => '', 'active' => true]
        ];
        $products = Products::select('id', 'name', 'code')->get();

        return view('admin.sales.index', compact('pageTitle', 'breadcrumbs', 'products'));
    }

    public function getData(Request $request)
    {
        $data = Sale::query()
            ->select([
                'sales.id',
                'sales.customer_id',
                'sales.reference',
                'sales.created_at',
                'sales.total_amount',
                'sales.total_amount AS grand_total',
                'companies.name AS customer',
                'biller.name as biller',
                'sales.status',
                'sales.payment_status',
            ])
            ->leftJoin('companies', 'sales.customer_id', '=', 'companies.id')
            ->leftJoin('companies as biller', 'sales.biller_id', '=', 'biller.id')
            ->where('sales.sale_type', null)
//            ->orWhereNull('sales.sale_type')
            ->withSum(['payments as paid' => fn ($q) => $q->where('status', 'completed')], 'amount');
        if ($request->filled('warehouse_id')) {
            $data->where('sales.warehouse_id', $request->warehouse_id);
        }
        return DataTables::of($data)
            ->addColumn('balance', fn ($sale) => round($sale->total_amount - ($sale->paid ?? 0), 2))
            ->editColumn('created_at', function ($row) {
                // Format to your preferred date/time format
                return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
            })
            ->addColumn('action', function ($data) {
                return '<div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        ' . __('messages.actions') . '
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="' . route('sales.show', $data->id) . '">
                            <i class="bi bi-eye"></i> ' . __('messages.show') . '</a></li>
                        <li><a class="dropdown-item" href="' . route('sales.edit', $data->id) . '">
                            <i class="bi bi-pencil"></i> ' . __('messages.edit') . '</a></li>
                        <li><a class="dropdown-item" href="' . route('sales.payments', $data->id) . '">
                            <i class="bi bi-file-earmark-plus"></i> ' . __('messages.add_payments') . '</a></li>
                        <li><a class="dropdown-item list-payment-sale" data-id="' . $data->id . '">
                            <i class="bi bi-list-columns"></i> ' . __('messages.list_payment') . '</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button class="dropdown-item text-danger delete-sale" data-id="' . $data->id . '">
                            <i class="bi bi-trash"></i> ' . __('messages.delete') . '</button></li>
                    </ul>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle = __('messages.create');
        $breadcrumbs = [
            ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
            ['label' => __('messages.sales'), 'url' => route('sales.index'), 'active' => false],

            ['label' => __('messages.add'), 'url' => '', 'active' => true]
        ];
        $products = Products::select('id', 'name', 'code', 'stock_quantity', 'selling_price')->get();
        return view('admin.sales.create', compact('pageTitle', 'breadcrumbs', 'products'));
    }

    public function store(Request $request)
    {
        $this->persistSale($request);
        return response()->json(['message' => __('messages.sale_created_successfully'), 'redirect' => route('sales.index')]);
    }

    public function show($id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);


        $items = SaleItem::with('product')
            ->where('sale_id', $id)
            ->get();
        // die($sale);
        return view('admin.sales.show', compact('sale', 'items'));
    }

    public function edit($id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);
        $products = Products::select('id', 'name', 'code', 'stock_quantity', 'selling_price')->get();
        $pageTitle = __('messages.edit');
        $breadcrumbs = [['label' => __('messages.sales_list'), 'url' => route('sales.index'), 'active' => false], ['label' => __('messages.edit'), 'url' => '', 'active' => true]];

        $customers = \App\Models\Companies::where('group_id', 4)->get();
        return view('admin.sales.edit', compact('pageTitle', 'breadcrumbs', 'products', 'sale', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $this->persistSale($request, $id);
        return response()->json(['message' => __('messages.sale_updated_successfully'), 'redirect' => route('sales.index')]);
    }
    public function destroy($id)
    {
        DB::transaction(fn () => $this->deleteSale($id), 3);
        return response()->json(['message' => __('messages.sale_deleted_successfully')]);
    }

    public function bulkDelete(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'required|integer|distinct|exists:sales,id']);
        DB::transaction(function () use ($data) {
            $ids = $data['ids'];
            sort($ids);
            foreach ($ids as $id) {
                $this->deleteSale($id);
            }
        }, 3);
        return response()->json(['message' => __('messages.selected_sales_deleted_successfully')]);
    }


    public function payments($id)
    {
        $pageTitle = __('messages.add_payments');
        $breadcrumbs = [
            ['label' => __('messages.sales_list'), 'url' => route('sales.index'), 'active' => false],
            ['label' => __('messages.add_payments'), 'url' => '', 'active' => true]
        ];
        $sale = DB::table('sales')->where('id', $id)->first();
        if (!$sale) {
            return redirect()->route('sales.index')
                ->with('error', 'Sale not found.');
        }
        $totalPaid = DB::table('payments')->where('sale_id', $id)->where('status', 'completed')->sum('amount');
        $balance = $sale->total_amount - $totalPaid;
        if ($balance <= 0) {
            return redirect()->route('sales.index')
                ->with('error', 'This sale is already fully paid. You cannot add more payments.');
        }
        return view('admin.sales.add-payemts', compact('pageTitle', 'breadcrumbs', 'sale', 'totalPaid', 'balance'));

    }

    public function storePayment(Request $request)
    {
        $request->merge(['methods' => $request->input('method', $request->input('methods'))]);
        $data = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'amount' => 'required|numeric|min:0.01|max:99999999.99',
            'methods' => 'required|in:cash,card,qr,bank',
            'paid_at' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $path = $request->hasFile('attachment') ? $request->file('attachment')->store('payments', 'public') : null;
        try {
            DB::transaction(function () use ($data, $path) {
                // POS operations consistently lock register before sale.
                $candidate = Sale::findOrFail($data['sale_id']);
                $register = null;
                if ($candidate->cash_register_id) {
                    $register = DB::table('pos_registers')->where('id', $candidate->cash_register_id)->lockForUpdate()->first();
                    abort_unless($register && $register->status === 'open' && (int) $register->user_id === (int) auth()->id(), 422, 'The sale register is closed or belongs to another user');
                }
                $sale = Sale::whereKey($data['sale_id'])->lockForUpdate()->firstOrFail();
                abort_if($sale->status === 'cancelled', 422, 'Cannot pay a cancelled sale');
                $paid = $sale->payments()->where('status', 'completed')->sum('amount');
                $balance = round($sale->total_amount - $paid, 2);
                if (round($data['amount'], 2) > $balance || $balance <= 0) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['amount' => 'Payment exceeds remaining balance']);
                }
                $sale->payments()->create([
                    'amount' => round($data['amount'], 2),
                    'method' => $data['methods'],
                    'reference' => $data['reference'] ?? null,
                    'paid_at' => $data['paid_at'],
                    'attachment' => $path,
                    'status' => 'completed',
                    'created_by' => auth()->id(),
                ]);
                $sale->update(['payment_status' => round($balance - $data['amount'], 2) <= 0 ? 'completed' : 'partial']);
                if ($register && $data['methods'] === 'cash') {
                    DB::table('pos_registers')->where('id', $register->id)->update(['total_cash' => round(($register->total_cash ?? 0) + $data['amount'], 2)]);
                }
            }, 3);
        } catch (\Throwable $e) {
            if ($path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            throw $e;
        }
        return redirect()->route('sales.index')->with('success', 'Payment saved successfully');
    }



    public function listPayments($id)
    {
        $sale = Sale::with('payments')->findOrFail($id);


        return response()->json([
            'success' => true,
            'data' => $sale,
            'sale_id' => $id
        ]);
    }


    public function pos()
    {
        $pageTitle = __('messages.pos_sales');
        $breadcrumbs = [['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false], ['label' => __('messages.sales_list'), 'url' => route('sales.index'), 'active' => false], ['label' => __('messages.pos_sales'), 'url' => '', 'active' => true]];
        $warehouses = DB::table('warehouses')->select('id', 'name')->get();

        return view('admin.sales.pos', compact('pageTitle', 'breadcrumbs', 'warehouses'));
    }

    public function getDataPos(Request $request)
    {
        $data = Sale::query()
            ->select([
                'sales.id',
                'sales.customer_id',
                'sales.reference',
                'sales.created_at',
                'sales.total_amount',
                'sales.total_amount AS grand_total',
                'companies.name AS customer',
                'biller.name as biller',
                'sales.status',
                'sales.payment_status',
            ])
            ->leftJoin('companies', 'sales.customer_id', '=', 'companies.id')
            ->leftJoin('companies as biller', 'sales.biller_id', '=', 'biller.id')
            ->where('sales.sale_type', 'pos')
//            ->orWhereNull('sales.sale_type')
            ->withSum(['payments as paid' => fn ($q) => $q->where('status', 'completed')], 'amount');
        if ($request->filled('warehouse_id')) {
            $data->where('sales.warehouse_id', $request->warehouse_id);
        }
        return DataTables::of($data)
            ->addColumn('balance', fn ($sale) => round($sale->total_amount - ($sale->paid ?? 0), 2))
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
            })
            ->addColumn('action', function ($data) {
                return '<div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        ' . __('messages.actions') . '
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="' . route('sales.show', $data->id) . '">
                            <i class="bi bi-eye"></i> ' . __('messages.show') . '</a></li>
                        <li><a class="dropdown-item" href="' . route('sales.edit', $data->id) . '">
                            <i class="bi bi-pencil"></i> ' . __('messages.edit') . '</a></li>
                        <li><a class="dropdown-item" href="' . route('sales.payments', $data->id) . '">
                            <i class="bi bi-file-earmark-plus"></i> ' . __('messages.add_payments') . '</a></li>
                        <li><a class="dropdown-item list-payment-sale" data-id="' . $data->id . '">
                            <i class="bi bi-list-columns"></i> ' . __('messages.list_payment') . '</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button class="dropdown-item text-danger delete-sale" data-id="' . $data->id . '">
                            <i class="bi bi-trash"></i> ' . __('messages.delete') . '</button></li>
                    </ul>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }



    public function export()
    {
        return app(\App\Services\CsvExportService::class)->download('sales.csv',
            ['Reference', 'Date', 'Total', 'Status', 'Payment status'],
            Sale::orderBy('id')->cursor()->map(fn ($sale) => [$sale->reference, $sale->date?->toDateString(), $sale->total_amount, $sale->status, $sale->payment_status]));
    }

    private function persistSale(Request $request, $id = null): Sale
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:companies,id',
            'status' => 'required|in:pending,completed,cancelled',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|distinct|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:1000000',
            'items.*.sale_price' => 'required|numeric|min:0|max:999999.99',
        ]);
        return DB::transaction(function () use ($data, $id) {
            $sale = $id ? Sale::whereKey($id)->lockForUpdate()->firstOrFail() : new Sale();
            abort_if($sale->sale_type === 'pos', 422, 'POS sales cannot be edited through normal sales');
            $old = $sale->exists ? $sale->items()->get() : collect();
            $ids = collect($data['items'])->pluck('product_id')->merge($old->pluck('product_id'))->unique()->sort()->values();
            $products = Products::whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            if ($sale->exists && $sale->status !== 'cancelled') {
                foreach ($old as $item) {
                    $products[$item->product_id]->increment('stock_quantity', $item->quantity);
                }
            }
            $total = collect($data['items'])->sum(fn ($item) => round($item['quantity'] * $item['sale_price'], 2));
            if ($total > 99999999.99) {
                throw \Illuminate\Validation\ValidationException::withMessages(['items' => 'Sale total is too large']);
            }
            $paid = $sale->exists ? $sale->payments()->where('status', 'completed')->sum('amount') : 0;
            if ($paid > $total || ($paid > 0 && $data['status'] === 'cancelled')) {
                throw \Illuminate\Validation\ValidationException::withMessages(['total_amount' => 'Refund payments before reducing or cancelling a paid sale']);
            }
            $sale->fill([
                'reference' => $sale->reference ?? Sale::generateReference(),
                'user_id' => $sale->user_id ?? auth()->id(),
                'customer_id' => $data['customer_id'] ?? $sale->customer_id,
                'date' => $data['date'], 'status' => $data['status'],
                'subtotal' => $total, 'total_amount' => $total,
                'payment_status' => $paid >= $total ? 'completed' : ($paid > 0 ? 'partial' : 'pending'),
            ])->save();
            $sale->items()->delete();
            foreach ($data['items'] as $item) {
                $product = $products[$item['product_id']];
                if ($data['status'] !== 'cancelled') {
                    if ($product->stock_quantity < $item['quantity']) {
                        throw \Illuminate\Validation\ValidationException::withMessages(['items' => 'Insufficient stock for ' . $product->name]);
                    }
                    $product->decrement('stock_quantity', $item['quantity']);
                }
                $sale->items()->create($item + ['product_name' => $product->name, 'subtotal' => round($item['quantity'] * $item['sale_price'], 2)]);
            }
            return $sale;
        }, 3);
    }

    private function deleteSale($id): void
    {
        $sale = Sale::whereKey($id)->lockForUpdate()->firstOrFail();
        abort_if($sale->sale_type === 'pos' || $sale->payments()->exists(), 422, 'Sales with register activity or payments must be retained');
        if ($sale->status !== 'cancelled') {
            foreach ($sale->items()->orderBy('product_id')->get() as $item) {
                Products::whereKey($item->product_id)->lockForUpdate()->firstOrFail()->increment('stock_quantity', $item->quantity);
            }
        }
        $sale->items()->delete();
        $sale->delete();
    }
}
