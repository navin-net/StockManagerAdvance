<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Brand, Categories, ProductImage, Products, Qualitys, SaleItem, SubCategory, Units};
use App\Imports\Admin\ProductsImport;
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
            'second_name' => 'required|string|max:191',
            'expiry_date' => 'nullable|date',
            'subcategory_id' => ['nullable', \Illuminate\Validation\Rule::exists('sub_categories', 'id')->where('category_id', $request->category_id)],
            'unit_id' => 'required|exists:units,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_review' => 'nullable|array|max:10',
            'image_review.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $paths = [];
        try {
            $product = DB::transaction(function () use ($request, $validator, &$paths) {
                $data = collect($validator->validated())->except(['image', 'image_review'])->all();
                if ($request->hasFile('image')) {
                    $data['image'] = $request->file('image')->store('products', 'public');
                    $paths[] = $data['image'];
                }
                $product = Products::create($data);
                foreach ($request->file('image_review', []) as $img) {
                    $path = $img->store('products/review', 'public');
                    $paths[] = $path;
                    $product->images()->create(['image_review' => $path]);
                }
                return $product;
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($paths);
            throw $e;
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
            'second_name' => 'required|string|max:191',
            'expiry_date' => 'nullable|date',
            'subcategory_id' => ['nullable', \Illuminate\Validation\Rule::exists('sub_categories', 'id')->where('category_id', $request->category_id)],
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

        $newPaths = [];
        $oldPaths = [];
        try {
            DB::transaction(function () use ($id, $request, $validator, &$newPaths, &$oldPaths) {
                $product = Products::whereKey($id)->lockForUpdate()->firstOrFail();
                $data = collect($validator->validated())->except(['image', 'image_review'])->all();
                if ($request->hasFile('image')) {
                    $data['image'] = $request->file('image')->store('products', 'public');
                    $newPaths[] = $data['image'];
                    if ($product->image) {
                        $oldPaths[] = $product->image;
                    }
                }
                $product->update($data);
                if ($request->hasFile('image_review')) {
                    $oldPaths = array_merge($oldPaths, $product->images()->pluck('image_review')->all());
                    $product->images()->delete();
                    foreach ($request->file('image_review') as $img) {
                        $path = $img->store('products/review', 'public');
                        $newPaths[] = $path;
                        $product->images()->create(['image_review' => $path]);
                    }
                }
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($newPaths);
            throw $e;
        }
        Storage::disk('public')->delete(array_filter($oldPaths));
        return redirect()->route('products.index')->with('success', __('messages.product_updated'));
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
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('qualitys', 'products.quality_id', '=', 'qualitys.id')
            ->where('products.id', $id)
            ->first();


        abort_unless($product, 404);

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
        $this->deleteProducts([$id]);
        return response()->json(['message' => __('messages.product_deleted_successfully'), 'redirect' => route('products.index')]);
    }

    public function deleteImage($id)
    {
        $path = DB::transaction(function () use ($id) {
            $image = ProductImage::whereKey($id)->lockForUpdate()->firstOrFail();
            $path = $image->image_review;
            $image->delete();
            return $path;
        });
        if ($path) {
            Storage::disk('public')->delete($path);
        }
        return response()->json(['status' => true, 'message' => 'Image deleted successfully']);
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
    $validator = Validator::make($request->all(), [
        'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'messages' => [
                'error_messages' => $validator->errors()->all(),
            ],
        ], 422);
    }

    $import = new ProductsImport();

    try {
        Excel::import($import, $request->file('file'));
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'messages' => [
                'error_messages' => ['Import failed: ' . $e->getMessage()],
            ],
        ], 422);
    }

    $messages = $import->getMessages();

    return response()->json([
        'success'  => empty($messages['error_messages']),
        'messages' => $messages,
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
            'products' => Products::orderBy('name')->get(['id', 'name', 'code', 'stock_quantity']),
            'adjustments' => DB::table('stock_adjustments')->orderByDesc('id')->paginate(20),
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

    public function storeAdjustment(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1|max:1000000',
            'reason' => 'required|string|max:1000',
        ]);
        DB::transaction(function () use ($data) {
            $product = Products::whereKey($data['product_id'])->lockForUpdate()->firstOrFail();
            $before = (int) $product->stock_quantity;
            $delta = $data['type'] === 'increase' ? $data['quantity'] : -$data['quantity'];
            $after = $before + $delta;
            if ($after < 0 || $after > 2147483647) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'quantity' => __('messages.invalid_stock_adjustment'),
                ]);
            }
            $product->update(['stock_quantity' => $after]);
            DB::table('stock_adjustments')->insert([
                'product_id' => $product->id, 'product_name' => $product->name,
                'product_code' => $product->code, 'user_id' => auth()->id(),
                'quantity_before' => $before, 'quantity_change' => $delta,
                'quantity_after' => $after, 'reason' => $data['reason'],
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }, 3);
        return redirect()->route('products.adjustment')->with('success', __('messages.stock_adjusted'));
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:products,id',
        ]);
        $this->deleteProducts($data['ids']);
        return response()->json(['message' => __('messages.deleted_successfully')]);
    }
    public function export(\App\Services\CsvExportService $export)
    {
        return $export->download('products.csv', ['Code', 'Name', 'Stock', 'Cost price', 'Selling price'],
            Products::orderBy('id')->cursor()->map(fn ($p) => [$p->code, $p->name, $p->stock_quantity, $p->cost_price, $p->selling_price]));
    }

    private function deleteProducts(array $ids): void
    {
        $paths = DB::transaction(function () use ($ids) {
            $paths = [];
            foreach (Products::whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get() as $product) {
                if ($product->cartItems()->exists() || SaleItem::where('product_id', $product->id)->exists()
                    || \App\Models\PurchaseItem::where('product_id', $product->id)->exists()) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['ids' => 'Products referenced by carts, sales, or purchases cannot be deleted']);
                }
                $paths[] = $product->image;
                $paths = array_merge($paths, $product->images()->pluck('image_review')->all());
                $product->images()->delete();
                $product->delete();
            }
            return $paths;
        });
        Storage::disk('public')->delete(array_filter($paths));
    }
}