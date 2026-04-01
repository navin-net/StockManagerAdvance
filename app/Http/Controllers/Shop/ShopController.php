<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseController;
use App\Models\{Banner, Brand, Categories, Products};

class ShopController extends BaseController
{

    public function __construct()
    {
        parent::__construct(); // 👈 important
    }

    public function index()
    {
        $banners = Banner::where('status', 1)->get();
        $categories = Categories::withCount('products')->get();
        return view('frontend-v2.index', compact('banners','categories'));
    }

    public function products(Request $request)
    {



        return view('frontend-v2.products');
    }

    public function show($code)
    {
        $product = Products::with(['brand', 'category','images'])
            ->where('code', $code)
            ->firstOrFail();


            // die($product);
        $relatedProducts = Products::where('brand_id', $product->brand_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();


            // die($product);
        return view('frontend-v2.product-detail', compact('product', 'relatedProducts'));
    }


    public function contact_us()
    {
        return view('frontend-v2.contact-us');
    }

    public function cart()
    {
        return view('frontend-v2.cart');
    }

    public function about_us()
    {
        return view('frontend-v2.about-us');
    }

    public function checkout()
    {
        return view('frontend-v2.checkout');
    }

    public function wishlist()
    {
        return view('frontend-v2.wishlist');
    }




}
