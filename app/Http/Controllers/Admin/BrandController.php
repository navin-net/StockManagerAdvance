<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage, Validator};
use App\Models\Brand;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::select(['id', 'code', 'name', 'image', 'slug', 'description']);
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                return '
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-primary editBrandBtn action-btn" data-id="' . $row->id . '">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteBrandBtn action-btn" data-id="' . $row->id . '">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                ';

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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'code' => 'nullable|max:20',
            'image' => 'nullable|image|max:1024',
            'slug' => 'nullable|max:55',
            'description' => 'nullable|max:255',
        ]);

        $data = $request->except('image');

        if ($image = $request->file('image')) {
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();

            // Store inside 'storage/app/public/images'
            $path = $image->storeAs('images', $profileImage, 'public');

            // Save file name or full path
            $data['image'] = $profileImage;
        }


        Brand::create($data);

        return response()->json(['success' => __('messages.brand_added_successfully')]);
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json($brand);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:50',
            'code' => 'nullable|max:20',
            'image' => 'nullable|image|max:1024',
            'slug' => 'nullable|max:55',
            'description' => 'nullable|max:255',
        ]);

        $brand = Brand::findOrFail($id);
        $data = $request->except('image');

        if ($image = $request->file('image')) {
            // Generate unique filename
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();

            // Delete old image if exists
            if ($brand->image && Storage::disk('public')->exists('images/' . $brand->image)) {
                Storage::disk('public')->delete('images/' . $brand->image);
            }

            // Store new image in storage/app/public/images
            $image->storeAs('images', $profileImage, 'public');
            $data['image'] = $profileImage;
        }

        $brand->update($data);

        return response()->json(['success' => __('messages.brand_updated_successfully')]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // Delete image from storage if exists
        if ($brand->image && Storage::disk('public')->exists('images/' . $brand->image)) {
            Storage::disk('public')->delete('images/' . $brand->image);
        }

        $brand->delete();

        return response()->json(['success' => __('messages.brand_deleted_successfully')]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        $brands = Brand::whereIn('id', $ids)->get();

        foreach ($brands as $brand) {
            if ($brand->image && Storage::disk('public')->exists('images/' . $brand->image)) {
                Storage::disk('public')->delete('images/' . $brand->image);
            }
            $brand->delete();
        }

        return response()->json(['success' => __('messages.selected_brands_deleted_successfully')]);
    }


}
