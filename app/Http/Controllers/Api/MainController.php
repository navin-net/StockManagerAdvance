<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {

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
    public function show($id)
    {
        $brand = DB::table('banners')->where('id', $id)->first();

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        return response()->json($brand);
    }


}
