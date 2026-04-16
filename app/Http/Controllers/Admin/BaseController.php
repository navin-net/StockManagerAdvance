<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Auth, View};
use App\Imports\UsersImport;
use App\Models\{Brand, Categories, Products, Shop};
use Maatwebsite\Excel\Facades\Excel;

class BaseController extends Controller
{
    protected $shopDetail;

    public function __construct()
    {
        $shop = Shop::first();

        $this->shopDetail = (object) [
            'id' => $shop->id,
            'name' => $shop->name_shop,
            'facebook' => $shop->facebook,
            'instagram' => $shop->instagram,
            'x' => $shop->x,
            'youtube' => $shop->youtube,
            'address' => $shop->address,
            'phone' => $shop->phone,
            'email' => $shop->email,
            'open_shop_time' => $shop->open_shop_time,
            'close_shop' => $shop->close_shop,
            'description' => $shop->description,
            'logo' => $shop->logo
                ? asset($shop->logo)
                : asset('images/default-shop-logo.png'),
        ];

        $this->categories = Categories::withCount('products')->get();
        $this->brands = Brand::withCount('products')->get();
        View::share('categories', $this->categories);
        View::share('brands',$this->brands);
        View::share('shopDetail', $this->shopDetail);
    }

    public function getAlerts()
    {
        return Products::where('stock_quantity', '<', 0)
            ->orWhere('stock_quantity', 0)
            ->get(['id', 'name', 'code', 'stock_quantity']);
    }



    public function chartData()
    {
        return response()->json([
            'labels' => ['Mon', 'Tue', 'Wed'],
            'values' => [12, 19, 7]
        ]);
    }

    public function showImportForm()
    {
        return view('import'); // Blade with upload form
    }


    public function import(Request $request)
    {
        $import = new UsersImport();
        Excel::import($import, $request->file('file'));

        return response()->json([
            'success'  => true,
            'messages' => $import->messages,
        ]);
    }


    public function testing(){

        $products = Products::where('stock_quantity', '>', 0)->get();

        return view('import', compact('products'));

    }




}
