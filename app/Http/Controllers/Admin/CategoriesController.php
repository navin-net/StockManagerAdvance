<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Categories::select(['id', 'name', 'slug']);
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex gap-2">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary editCategory" 
                                    data-id="' . $row->id . '" 
                                    title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger deleteCategory" 
                                    data-id="' . $row->id . '" 
                                    title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.settings.categories.index', [
            'pageTitle' => __('messages.categories_list'),
            'heading' => __('messages.stock_management_system'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                
                ['label' => __('messages.categories'), 'url' => '', 'active' => true],
            ]
        ]);
    }
}
