<?php

namespace App\Http\Controllers\Shop;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
class MainController extends Controller
{


    public function index()
    {
        return view('frontend.index');
    }


    public function portfolio()
    {
        return view('frontend.portfolio.index');
    }


    public function register()
    {
        return view('frontend.portfolio.auth.register');
    }

    public function store(Request $request)
    {
        // Validate
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Example: save JSON file
        $json = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents(
            storage_path('app/contact.json'),
            $json
        );

        return back()->with('success', 'Message saved!');
    }
}
