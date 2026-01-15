<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Auth, View};
use App\Models\{Products, Shop};

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

        View::share('shopDetail', $this->shopDetail);
    }

    public function getAlerts()
    {
    return Products::where('stock_quantity', '<', 0)
        ->orWhere('stock_quantity', 0)
        ->get(['id', 'name','code', 'stock_quantity']);
    }

}
