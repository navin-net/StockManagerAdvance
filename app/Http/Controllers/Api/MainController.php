<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\{Brand, Categories, Products, Shop};

class MainController extends Controller
{
    public function index()
    {
        $shop = Shop::first();
        return response()->json($shop);
    }

    public function getData()
    {
        $user = DB::table('banners')
          ->select('id', 'title')
          ->where('status',1 )
           ->get();
        return json_decode($user);
        // return response()->json($user);
    }

    public function getProducts(Request $request)
    {
// 1. Start the query
    $query = Products::query();

    // 2. Check if a category filter was sent
    if ($request->has('category_id')) {
        $query->where('category_id', $request->request->get('category_id'));
    }

    // 3. Optional: Add a price filter while you're at it
    if ($request->has('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    // 4. Return the data (Laravel handles JSON automatically)
    return $query->get();
    }

    public function slides()
    {
        return response()->json([
            [
                "eyebrow" => "New Collection — Spring 2026",
                "title" => "Dress with<br><em>Intent.</em>",
                "sub" => "Curated luxury pieces...",
                "img" => "https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1400&q=80",
                "btn1" => "Shop Collection",
                "btn2" => "Explore Lookbook",
                "btn1Style" => "",
                "slideClass" => "slide-1"
            ],
            [
                "eyebrow" => "Sale — Up to 40% Off",
                "title" => "Luxury at<br><em>Your Price.</em>",
                "sub" => "Select pieces...",
                // "img" => asset('storage/slides/slide2.jpg'),
                "img" => "https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1400&q=80",
                "btn1" => "Shop Sale",
                "btn2" => "All Offers",
                "btn1Style" => "background:#c0392b",
                "slideClass" => "slide-2"
            ]
        ]);
    }

    public function getBrands()
    {
        $products = Brand::get();
        return json_decode($products);
    }

    public function getCategories()
    {
        $categories = Categories::with('subcategories')->get();
        return response()->json($categories);
    }


    public function getTrending()
    {
        $products = Products::with('brand')
            ->where('stock_quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        return response()->json($products);
    }

    public function show($id)
    {
        $brand = DB::table('banners')->where('id', $id)->first();

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        return response()->json($brand);
    }


}
