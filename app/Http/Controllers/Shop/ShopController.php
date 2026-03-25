<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class ShopController extends Controller
{
    public function index(){

        $banners = Banner::where('status', 1)->get();
        return view('frontend.shop.index', compact('banners'));
    }
}
