<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use App\Http\Controllers\Controller;
use App\Models\{ProductImage, Products};
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

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return view('admin.products.partials.actions', compact('row'))->render();
                })
                ->filterColumn('subcategory_name', function($query,$keyword){
                    $query->where('sub_categories.name','like',"%$keyword%");
                })
                ->filterColumn('brand_name', function($query,$keyword){
                    $query->where('brands.name','like',"%$keyword%");
                })
                ->filterColumn('quality_name', function($query,$keyword){
                    $query->where('qualitys.name','like',"%$keyword%");
                })
                ->filterColumn('unit_name', function($query,$keyword){
                    $query->where('units.name','like',"%$keyword%");
                })
                ->filterColumn('category_name', function($query,$keyword){
                    $query->where('categories.name','like',"%$keyword%");
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.products.index', [
            'pageTitle'   => __('messages.products_list'),
            'heading'     => __('messages.products_list'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('dashboard'), 'active' => false],
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
                ['label' => __('messages.dashboard'), 'url' => route('dashboard'), 'active' => false],
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
            $imageName = time() . '.' . $request->image->extension();
            $path = $request->image->storeAs('products', $imageName, 'public');
            $data['image'] = $path;
        }

        $product = Products::create($data);

        if ($request->hasFile('image_review')) {
            foreach($request->file('image_review') as $img){
                $imgName = uniqid().'.'.$img->extension();
                $path = $img->storeAs('products/review', $imgName, 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_review' => $path,
                ]);
            }
        }


        session()->flash('success', __('messages.product_created'));

        return response()->json([
            'message' => __('messages.product_created'),
            'redirect' => route('products.index'),
        ], 201);
    }

    public function getSubCategories(Request $request)
    {
        $subCategories = DB::table('sub_categories')
            ->select('id', 'name')
            ->where('category_id', $request->category_id)
            ->get();

        return response()->json($subCategories);
    }

    public function show($id)
    {
        $product = DB::table('products')
            ->select(
                'products.*',
                'brands.name as brand_name',
                'categories.name as category_name',
                'sub_categories.name as subcategory_name',
                'product_images.image_review',
                'qualitys.name as quality_name'
            )
            ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('qualitys', 'products.quality_id', '=', 'qualitys.id')
            ->where('products.id', $id)
            ->first();

        $images = DB::table('product_images')
            ->where('product_id', $id)
            ->pluck('image_review');
        // return response()->json(['product' => $product,'images'  => $images]);

        return view('admin.products.products-detail', [
            'product'      => $product,
            'images'       => $images,
            'pageTitle'    => __('messages.products_detail'),
            'heading'      => __('messages.products_detail'),
            'description'  => __('messages.dashboard_welcome'),
            'breadcrumbs'  => [
                ['label' => __('messages.products'), 'url' => route('products.index'), 'active' => false],
                ['label' => __('messages.products_detail'), 'url' => '', 'active' => true],
            ]
        ]);
    }






}
