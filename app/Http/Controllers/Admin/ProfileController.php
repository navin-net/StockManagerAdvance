<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Routing\Controller;
use App\Models\{Brand, Categories, Companies, Products, User};

class ProfileController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }



    public function index()
    {
        echo "Profile index";
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            // 'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => __('messages.current_password_error'),
            ]);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }

        return back()->with('success', __('messages.avatar_updated'));
    }

    public function updateInformation(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        // $user->address = $request->address;
        // $user->country = $request->country;
        // $user->city = $request->city;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->save();

        return back()->with('success', __('messages.profile_updated'));

    }



    public function pos()
    {
        $products = Products::all();
        $categories = Categories::all();
        $brands = Brand::all();
        $customers = Companies::where('group_id', 4)->get();

        // die($customers);
        return view('testing1', compact('products','categories','brands','customers'))->with('pageTitle', 'POS System');
    }




}
