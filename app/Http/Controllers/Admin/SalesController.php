<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function index()
    {
        $pageTitle = __('messages.sales_list');
        $breadcrumbs = [
            ['label' => __('messages.dashboard'), 'url' => route('dashboard'), 'active' => false],
            ['label' => __('messages.sales'), 'url' => '', 'active' => true]
        ];
        $products = Products::select('id', 'name', 'code')->get();
        return view('admin.sales.index', compact('pageTitle', 'breadcrumbs', 'products'));
    }

    public function getData(Request $request)
    {
        $query = Sale::withCount('items')->withSum('items', 'quantity')
            ->orderBy('date', 'desc');
        return DataTables::of($query)
            ->addColumn('action', function ($sale) {
                return '

                    <a href="' . route('sales.show', $sale->id) . '" class="action-btn" title="show">
                        <i class="bi bi-eye-fill text-primary"></i></a>
                    <a href="' . route('sales.edit', $sale->id) . '" class="action-btn" title="Edit">
                        <i class="bi bi-pencil-fill text-warning"></i></a>
                    <a href="#" class="delete-sale action-btn" data-id="' . $sale->id . '" title="Delete">
                        <i class="bi bi-trash-fill text-danger"></i></a>';
            })
            ->editColumn('total_amount', function ($sale) {
                return ($sale->total_amount);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $products = Products::select('id', 'name', 'code', 'stock_quantity', 'selling_price')->get();
        // die($products);
        $pageTitle = __('messages.create_sale');
        $breadcrumbs = [
            ['label' => __('messages.dashboard'), 'url' => route('dashboard'), 'active' => false],
            ['label' => __('messages.sales'), 'url' => route('sales.index'), 'active' => false],
            ['label' => __('messages.create'), 'url' => '', 'active' => true]
        ];

        return view('admin.sales.create', compact('pageTitle', 'breadcrumbs','products'));
    }

}
