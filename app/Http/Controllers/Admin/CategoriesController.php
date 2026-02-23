<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{Categories, SubCategory};
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
                                    class="btn btn-sm btn-outline-primary editCategoryBtn"
                                    data-id="' . $row->id . '"
                                    title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger deleteCategories"
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $categories = Categories::create($validated);

        return response()->json([
            'success' => true,
            'message' => __('messages.categories_added_successfully'),
            'categories' => $categories
        ]);
    }
    public function edit($id)
    {
        $categories = Categories::findOrFail($id);
        return response()->json($categories); // This must be JSON\
    }
    public function update(Request $request, $id)
    {
        $categories = Categories::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $categories->update($validated);

        return response()->json([
            'success' => true,
            'message' => __('messages.categories_updated_successfully'),
            'categories' => $categories
        ]);
    }

    public function destroy($id)
    {
        $category = Categories::findOrFail($id);

        if (SubCategory::where('category_id', $id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete category because it has subcategories.'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully.'
        ]);
    }



}
