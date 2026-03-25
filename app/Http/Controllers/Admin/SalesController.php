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
                'sales.date',
                'sales.total_amount AS grand_total',
                DB::raw('COALESCE(SUM(sma_payments.amount), 0) AS paid'),
                DB::raw('(sma_sales.total_amount - COALESCE(SUM(sma_payments.amount), 0)) AS balance'),
                'companies.name AS customer',
                'biller.name as biller',
                'sales.status',
                'sales.payment_status',
            ])
            ->leftJoin('companies', 'sales.customer_id', '=', 'companies.id')
            ->leftJoin('companies as biller', 'sales.biiler_id', '=', 'biller.id')
            ->leftJoin('payments', 'sales.id', '=', 'payments.sale_id')
            ->where('sales.sale_type', 1)
            ->groupBy([
                'sales.id',
                'sales.customer_id',
                'sales.reference',
                'sales.date',
                'sales.total_amount',
                'companies.name',
                'sales.status',
            ]);
        if ($request->filled('warehouse_id')) {
            $data->where('sales.warehouse_id', $request->warehouse_id);
        }
        return DataTables::of($data)
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
        $validator = Validator::make($request->all(), [
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.sale_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'reference' => 'SALE-' . strtoupper(uniqid()),
                'date' => $request->date,
            ]);

            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'sale_price' => $item['sale_price'],
                ]);

                Products::where('id', $item['product_id'])->decrement('stock_quantity', $item['quantity']);
            }
        });

        return response()->json(
            [
                'message' => __('messages.sale_created_successfully'),
                'redirect' => route('sales.index'),
            ]
        );
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

        return view('admin.sales.edit', compact('pageTitle', 'breadcrumbs', 'products', 'sale'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.sale_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($request, $id) {
            $sale = Sale::findOrFail($id);

            foreach ($sale->items as $item) {
                Products::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            }

            $sale->update([
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'date' => $request->date,
            ]);

            $sale->items()->delete();

            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'sale_price' => $item['sale_price'],
                ]);

                Products::where('id', $item['product_id'])->decrement('stock_quantity', $item['quantity']);
            }
        });

        return response()->json([
            'message' => __('messages.sale_updated_successfully'),
            'redirect' => route('sales.index'),
        ]);
    }
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $sale = Sale::findOrFail($id);
            foreach ($sale->items as $item) {
                Products::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            }
            $sale->items()->delete();
            $sale->delete();
        });

        return response()->json(['message' => __('messages.sale_deleted_successfully')]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        DB::transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $sale = Sale::find($id);
                if ($sale) {
                    foreach ($sale->items as $item) {
                        Products::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
                    }
                    $sale->items()->delete();
                    $sale->delete();
                }
            }
        });

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
        $totalPaid = DB::table('payments')->where('sale_id', $id)->sum('amount');
        $balance = $sale->total_amount - $totalPaid;
        if ($balance <= 0) {
            return redirect()->route('sales.index')
                ->with('error', 'This sale is already fully paid. You cannot add more payments.');
        }
        return view('admin.sales.add-payemts', compact('pageTitle', 'breadcrumbs', 'sale', 'totalPaid', 'balance'));

    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'sale_id' => 'nullable|exists:sales,id',
            'amount' => 'required|numeric|min:0.01',
            // 'method' => 'required|string',
            'paid_at' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $sale_id = $request->sale_id;

        $sale = Sale::findOrFail($request->sale_id);

        $totalPaid = DB::table('payments')
            ->where('sale_id', $sale_id)
            ->sum('amount');

        $remainingBalance = $sale->total_amount - $totalPaid;

        if ($remainingBalance <= 0) {
            return back()->withErrors([
                'amount' => 'This sale is already fully paid.'
            ]);
        }
        if ($request->amount > $remainingBalance) {
            return back()->withErrors([
                'amount' => 'Payment exceeds remaining balance.'
            ]);
        }

        $newBalance = $remainingBalance - $request->amount;

        if ($request->amount <= 0) {
            // $status = 'pending';
            $paymentStatus = 'unpaid';
        } elseif ($newBalance <= 0) {
            // $status = 'paid';
            $paymentStatus = 'completed';
        } else {
            // $status = 'partial';
            $paymentStatus = 'partial';
        }


        $data = [
            'sale_id' => $sale_id,
            'reference' => $request->reference,
            'method' => $request->methods,
            'amount' => $request->amount,
            'paid_at' => $request->paid_at ?? now(),
            // 'pos_paid'    => $totalPaid + $request->amount,
            // 'pos_balance' => $remainingBalance - $request->amount,
            'created_by' => auth()->id(),
        ];

        $sale_data = [
            // 'status'         => $status,
            'payment_status' => $paymentStatus,
        ];


        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')
                ->store('payments', 'public');
        }

        Payment::create($data);
        Sale::where('id', $sale_id)->update($sale_data);


        return redirect()
            ->route('sales.index')
            ->with('success', 'Payment saved successfully');
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
                'sales.date',
                'sales.total_amount AS grand_total',
                DB::raw('COALESCE(SUM(sma_payments.amount), 0) AS paid'),
                DB::raw('(sma_sales.total_amount - COALESCE(SUM(sma_payments.amount), 0)) AS balance'),
                'companies.name AS customer',
                'biller.name as biller',
                'sales.status',
                'sales.payment_status',
            ])
            ->leftJoin('companies', 'sales.customer_id', '=', 'companies.id')
            ->leftJoin('companies as biller', 'sales.biiler_id', '=', 'biller.id')
            ->leftJoin('payments', 'sales.id', '=', 'payments.sale_id')
            ->where('sales.sale_type', 0)
            ->groupBy([
                'sales.id',
                'sales.customer_id',
                'sales.reference',
                'sales.date',
                'sales.total_amount',
                'companies.name',
                'sales.status',
            ]);
        if ($request->filled('warehouse_id')) {
            $data->where('sales.warehouse_id', $request->warehouse_id);
        }
        return DataTables::of($data)
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



}
