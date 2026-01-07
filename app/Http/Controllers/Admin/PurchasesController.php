<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Products, Purchase};
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
                ['label' => __('messages.dashboard'), 'url' => route('dashboard'), 'active' => false],
                ['label' => __('messages.purchases'), 'url' => '', 'active' => true],
            ],
            'products' => $products
        ]);
    }

    public function getData()
    {
        $query = Purchase::query()
            ->select('purchases.id', 'purchases.total_amount', 'purchases.date')
            ->withCount('items as item_count')
            ->withSum('items as total_quantity', 'quantity');

        return DataTables::of($query)->make(true);
    }


    public function create()
    {
        $products = Products::select('id', 'name', 'code', 'stock_quantity', 'cost_price')->get();

        return view('admin.purchases.create', [
            'products' => $products,
            'pageTitle' => __('messages.add_purchase'),
            'heading' => __('messages.add_purchase'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('dashboard'), 'active' => false],
                ['label' => __('messages.purchases'), 'url' => route('purchases.index'), 'active' => false],
                ['label' => __('messages.create'), 'url' => '', 'active' => true],
            ]
        ]);
    }

}
