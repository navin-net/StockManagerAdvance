<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\{Brand, Categories, ProductImage, Products, Qualitys, SaleItem, SubCategory, Units};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage, Validator};
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Products::select([
                'products.id',
                'products.image',
                'products.name',
                'products.code',
                'products.stock_quantity',
                'products.cost_price',
                'products.selling_price',
                'brands.name as brand_name',
                'categories.name as category_name',
                'sub_categories.name as subcategory_name',
                'qualitys.name as quality_name',
                'units.name as unit_name'
            ])
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                ->leftJoin('qualitys', 'products.quality_id', '=', 'qualitys.id')
                ->leftJoin('units', 'products.unit_id', '=', 'units.id');
            // ->Orderby('products.id','DESC')
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return view('admin.products.partials.actions', compact('row'))->render();
                })
                ->filterColumn('subcategory_name', function ($query, $keyword) {
                    $query->where('sub_categories.name', 'like', "%$keyword%");
                })
                ->filterColumn('brand_name', function ($query, $keyword) {
                    $query->where('brands.name', 'like', "%$keyword%");
                })
                ->filterColumn('quality_name', function ($query, $keyword) {
                    $query->where('qualitys.name', 'like', "%$keyword%");
                })
                ->filterColumn('unit_name', function ($query, $keyword) {
                    $query->where('units.name', 'like', "%$keyword%");
                })
                ->filterColumn('category_name', function ($query, $keyword) {
                    $query->where('categories.name', 'like', "%$keyword%");
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.products.index', [
            'pageTitle' => __('messages.products_list'),
            'heading' => __('messages.products_list'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                ['label' => __('messages.products'), 'url' => '', 'active' => true],
            ]
        ]);
    }


    public function getData()
    {
        $data = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                'products.code',
                'products.stock_quantity',
                'products.cost_price',
                'products.selling_price',
                'brands.name as brand_name',
                'categories.name as category_name',
                'sub_categories.name as subcategory_name',
                'qualitys.name as quality_name'
            )
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('qualitys', 'products.quality_id', '=', 'qualitys.id')
            ->get();

        return response()->json($data);
    }

    public function create()
    {
        $brands = DB::table('brands')->select('id', 'name')->get();
        $categories = DB::table('categories')->select('id', 'name')->get();
        $qualities = DB::table('qualitys')->select('id', 'name')->get();
        $units = DB::table('units')->select('id', 'name')->get();

        return view('admin.products.create', [
            'pageTitle' => __('messages.add_products'),
            'heading' => __('messages.add_products'),
            'brands' => $brands,
            'categories' => $categories,
            'qualities' => $qualities,
            'units' => $units,
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                ['label' => __('messages.products'), 'url' => route('products.index'), 'active' => false],
                ['label' => __('messages.create'), 'url' => '', 'active' => true],
            ],
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'code' => 'required|string|max:191|unique:products,code',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'quality_id' => 'required|exists:qualitys,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'second_name' => 'nullable|string|max:191',
            'unit_id' => 'required|exists:units,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_review.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $request->except('image', 'image_review');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('products', 'public');
        }
        $product = Products::create($data);
        if ($request->hasFile('image_review')) {
            foreach ($request->file('image_review') as $img) {
                $path = $img->store('products/review', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_review' => $path,
                ]);
            }
        }
        // session()->flash('success', __('messages.product_created'));
        return response()->json([
            'message' => __('messages.product_created'),
            'redirect' => route('products.index'),
        ], 201);
    }

    public function edit($id)
    {
        $product = Products::with([
            'brand',
            'category',
            'subCategory',
            'quality',
            'images'
        ])->findOrFail($id);

        // die($product);

        return view('admin.products.edit', [
            'pageTitle' => __('messages.edit_product') . ' - ' . $product->name,
            'heading' => __('messages.edit_product') . ' - ' . $product->name,
            'product' => $product,
            'brands' => Brand::select('id', 'name')->get(),
            'categories' => Categories::all(),
            'subcategories' => SubCategory::where('category_id', $product->category_id)->get(),
            'qualities' => Qualitys::select('id', 'name')->get(),
            'units' => Units::select('id', 'name')->get(),
            'breadcrumbs' => [
                ['label' => __('messages.products'), 'url' => route('products.index'), 'active' => false],
                ['label' => __('messages.edit'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'code' => 'required|string|max:191|unique:products,code,' . $product->id,
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'quality_id' => 'required|exists:qualitys,id', // change if table is "qualities"
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'second_name' => 'nullable|string|max:191',
            'unit_id' => 'required|exists:units,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_review' => 'nullable|array',
            'image_review.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->except('image', 'image_review');

            // Main image upload
            if ($request->hasFile('image')) {
                $newMainImage = $request->file('image')->store('products', 'public');

                // delete old image after new upload success
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $data['image'] = $newMainImage;
            }

            $product->update($data);

            // Review images upload
            if ($request->hasFile('image_review')) {

                // delete old review images
                foreach ($product->images as $oldImage) {
                    if ($oldImage->image_review && Storage::disk('public')->exists($oldImage->image_review)) {
                        Storage::disk('public')->delete($oldImage->image_review);
                    }
                    $oldImage->delete();
                }

                // store new review images
                foreach ($request->file('image_review') as $img) {
                    $path = $img->store('products/review', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_review' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('messages.product_updated') . ' - ' . $product->name,
                'redirect' => route('products.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        $product = DB::table('products')
            ->select(
                'products.*',
                'brands.name as brand_name',
                'categories.name as category_name',
                'sub_categories.name as subcategory_name',
                'units.name as unit_name',
                'qualitys.name as quality_name'
            )
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('qualitys', 'products.quality_id', '=', 'qualitys.id')
            ->where('products.id', $id)
            ->first();


        // dd($product);
        $products = Products::with('images')->findOrFail($id);
        $mainImage = $products->image;
        $images = $products->images;



        return view('admin.products.products-detail', [
            'product' => $product,
            'images' => $images,
            'mainImage' => $mainImage,
            'pageTitle' => __('messages.products_detail'),
            'heading' => __('messages.products_detail'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.products'), 'url' => route('products.index'), 'active' => false],
                ['label' => __('messages.products_detail'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    public function destroy($id)
    {
        $product = Products::with('images')->findOrFail($id);

        if (SaleItem::where('product_id', $product->id)->exists()) {
            return response()->json([
                'message' => __('messages.product_cannot_be_deleted_has_sales')
            ], 400);
        }

        if (!empty($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        foreach ($product->images as $img) {
            if (!empty($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
        }
        $product->images()->delete();

        $product->delete();

        return response()->json([
            'message' => __('messages.product_deleted_successfully'),
            'redirect' => route('products.index'),
        ], 200);
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        if ($image->image_review) {
            Storage::disk('public')->delete($image->image_review);
        }
        $image->delete();
        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully'
        ]);
    }

    public function getSubCategories($categoryId)
    {
        $subCategories = DB::table('sub_categories')
            ->select('id', 'name')
            ->where('category_id', $categoryId)
            ->get();

        return response()->json($subCategories);
    }

    public function showImportForm()
    {
        return view('admin.products.imports', [
            'pageTitle' => __('messages.products_import'),
            'heading' => __('messages.products_import'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.products'), 'url' => route('products.index'), 'active' => false],
                ['label' => __('messages.products_import'), 'url' => '', 'active' => true],
            ]
        ]);
    }

    public function import(Request $request)
    {
        $import = new UsersImport();
        Excel::import($import, $request->file('file'));

        return response()->json([
            'success' => true,
            'messages' => $import->messages,
        ]);
    }


    public function barcodelabel(Request $request)
    {


        $search = $request->query('q');

        $products = Products::query()
            ->when($search, function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            })
            ->select('id', 'code', 'name', 'image', 'selling_price')
            ->orderBy('name')
            // ->take(3)      // or ->limit(10)
            ->get();
        // die($products);

        return view('admin.products.barcode-generator', [
            'pageTitle' => __('messages.barcode-generator'),
            'heading' => __('messages.barcode-generator'),
            'description' => __('messages.dashboard_welcome'),
            'products' => $products,
            'breadcrumbs' => [
                ['label' => __('messages.products'), 'url' => route('products.index'), 'active' => false],
                ['label' => __('messages.barcode-generator'), 'url' => '', 'active' => true],
            ]
        ]);
    }



    public function adjustment(Request $request)
    {
        return view('admin.products.adjustment', [
            'pageTitle' => __('messages.add_adjustment'),
            'heading' => __('messages.add_adjustment'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                [
                    'label' => __('messages.home'),
                    'url' => route('admin.dashboard'), // or url('/admin')
                    'active' => false
                ],
                ['label' => __('messages.products'), 'url' => url('admin/products'), 'active' => false],
                ['label' => __('messages.adjustment'), 'url' => '', 'active' => true],
            ]
        ]);



    }

}
