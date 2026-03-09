<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use App\Models\{Companies, Products, Purchase, PurchaseItem};
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PurchasesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('purchases')
                ->select(
                    'purchases.id',
                    'purchases.total_amount',
                    'purchases.date',
                    DB::raw('COUNT(sma_purchase_items.id) as item_count'),
                    DB::raw('SUM(sma_purchase_items.quantity) as total_quantity')
                )
                ->leftJoin('purchase_items', 'purchases.id', '=', 'purchase_items.purchase_id')
                ->groupBy('purchases.id', 'purchases.total_amount', 'purchases.date');

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return view('admin.purchases.partials.actions', compact('row'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $products = Products::select('id', 'name', 'code')->get();

        return view('admin.purchases.index', [
            'pageTitle' => __('messages.purchases_list'),
            'heading' => __('messages.purchases_list'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                ['label' => __('messages.purchases'), 'url' => '', 'active' => true],
            ],
            'products' => $products
        ]);
    }

    public function getData()
    {
        $data = Purchase::query()
            ->select([
                'purchases.id',
                'purchases.supplier_id',
                'purchases.reference',
                'purchases.payment_status',
                'purchases.date',
                'purchases.status',
                'purchases.total_amount AS grand_total',
                DB::raw('COALESCE(SUM(sma_payments.amount), 0) AS paid'),
                DB::raw('(sma_purchases.total_amount - COALESCE(SUM(sma_payments.amount), 0)) AS balance'),
                'companies.name AS supplier',
            ])
            ->leftJoin('companies', 'purchases.supplier_id', '=', 'companies.id')
            ->leftJoin('payments', 'purchases.id', '=', 'payments.sale_id')
            ->groupBy([
                'purchases.id',
                'purchases.supplier_id',
                'purchases.reference',
                'purchases.date',
                'purchases.total_amount',
                'purchases.payment_status',
                'companies.name',
            ]);

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return '<div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        ' . __('messages.actions') . '
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="' . route('purchases.show', $data->id) . '">
                            <i class="bi bi-eye"></i> ' . __('messages.show') . '</a></li>
                        <li><a class="dropdown-item" href="' . route('purchases.edit', $data->id) . '">
                            <i class="bi bi-pencil"></i> ' . __('messages.edit') . '</a></li>
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
        $products = Products::select('id', 'name', 'code', 'stock_quantity', 'cost_price')->get();
        $suppliers = Companies::select('*')->where('group_id', 5)->get();

        // die($suppliers);
        return view('admin.purchases.create', [
            'products' => $products,
            'suppliers' => $suppliers,
            'pageTitle' => __('messages.add_purchase'),
            'heading' => __('messages.add_purchase'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                ['label' => __('messages.purchases'), 'url' => route('purchases.index'), 'active' => false],
                ['label' => __('messages.create'), 'url' => '', 'active' => true],
            ]
        ]);
    }



    public function edit($id)
    {
        $products = Products::select('id', 'name', 'code', 'stock_quantity', 'cost_price')->get();
        $suppliers = Companies::select('*');

        // die($suppliers);
        return view('admin.purchases.edit', [
            'products' => $products,
            'pageTitle' => __('messages.edit_purchase'),
            'heading' => __('messages.edit_purchase'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                ['label' => __('messages.purchases'), 'url' => route('purchases.index'), 'active' => false],
                ['label' => __('messages.edit'), 'url' => '', 'active' => true],
            ]
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required',
            'total_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'date' => 'required|date',
            'note' => 'nullable|string',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $attachmentPath = null;

        if ($request->hasFile('attachments')) {
            $attachmentPath = $request->file('attachments')
                ->store('purchases', 'public');
        }

        DB::transaction(function () use ($request, $attachmentPath) {

            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'total_amount' => $request->total_amount,
                'payment_status' => $request->payment_status,
                'status' => $request->status,
                'reference' => 'PUR-' . strtoupper(uniqid()),
                'date' => $request->date,
                'note' => $request->note,
                'attachments' => $attachmentPath,
            ]);

            foreach ($request->items as $item) {

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                ]);

                Products::where('id', $item['product_id'])
                    ->increment('stock_quantity', $item['quantity']);
            }
        });

        return redirect()
            ->route('purchases.index')
            ->with('success', __('messages.purchase_created_successfully'));
    }

}
