<?php

namespace App\Http\Controllers\Shop;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
class MainController extends Controller
{


    public function store(Request $request)
    {

        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);


        $json = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents(
            storage_path('app/contact.json'),
            $json
        );

        return back()->with('success', 'Message saved!');
    }
}
