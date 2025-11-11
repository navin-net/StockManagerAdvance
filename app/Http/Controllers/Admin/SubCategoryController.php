<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Categories::all();

        if ($request->ajax()) {
            $data = SubCategory::select([
                    'sub_categories.id',
                    'sub_categories.name as sub_category_name',
                    'categories.name as category_name',
                ])
                ->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id');

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex gap-2">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary editSubCategory" 
                                    data-id="' . $row->id . '" 
                                    title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger deleteSubCategory" 
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

        // return view('admin.sub_category.index', compact('categories'));
        return view('admin.settings.sub_categories.index', [
            'categories'  => $categories,
            'pageTitle'   => __('messages.sub_categories'),
            'heading'     => __('messages.stock_management_system'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                ['label' => __('messages.categories'), 'url' => '', 'active' => true],
            ],
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
