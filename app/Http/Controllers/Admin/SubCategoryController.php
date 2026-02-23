<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\{Categories, SubCategory};
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
                                    class="btn btn-sm btn-primary editSubCategorybtn"
                                    data-id="' . $row->id . '"
                                    title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-danger deleteSubCategory"
                                    data-id="' . $row->id . '"
                                    title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->filterColumn('sub_category_name', function($query,$keyword){
                    $query->where('sub_categories.name','like',"%$keyword%");
                })
                ->filterColumn('category_name', function($query,$keyword){
                    $query->where('categories.name','like',"%$keyword%");
                })
                ->rawColumns(['action'])
                ->make(true);
        }

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


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
        ]);

        $categories = SubCategory::create($validated);

        return response()->json(['success' => __('messages.brand_added_successfully')]);

    }
    public function edit($id)
    {
        $subcategory = SubCategory::findOrFail($id);

        return response()->json([
            'id' => $subcategory->id,
            'name' => $subcategory->name,
            'category_id' => $subcategory->category_id,
        ]);
    }




    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $subcategory = SubCategory::findOrFail($id);
        $subcategory->update($request->only('name', 'category_id'));

        return response()->json(['success' => __('messages.brand_added_successfully')]);

    }

    public function destroy($id)
    {
        $sub_category = SubCategory::findOrFail($id);


        $sub_category->delete();

        return response()->json(['success' => __('messages.brand_added_successfully')]);

    }















}
