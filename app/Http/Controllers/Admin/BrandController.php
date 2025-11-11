<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::select(['id', 'code', 'name', 'image', 'slug', 'description']);
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return view('admin.settings.brands.partials.actions', compact('row'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.settings.brands.index', [
            'pageTitle' => __('messages.brands_list'),
            'heading' => __('messages.stock_management_system'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                
                ['label' => __('messages.brands'), 'url' => '', 'active' => true],
            ]
        ]);
    }
}
