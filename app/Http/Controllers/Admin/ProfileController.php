<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Auth, Hash};
use App\Models\{Brand, Categories, Companies, Products, User};

class ProfileController extends BaseController
{

    public function __construct()
    {
        parent::__construct();

    }



    public function index()
    {
        $user = Auth::user();
        $id = $user->id;
        $company = Companies::find($id);
        // die($user);
        return view('admin.profile.edit1', [
            'pageTitle' => __('messages.my_account'),
            'heading' => __('messages.my_account'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                ['label' => __('messages.my_account'), 'url' => '', 'active' => true],
            ],
            'user' => $user,
            'company' => $company,
        ]);
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

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $user->update($request->only('name', 'email'));

        return back()->with('success', 'Profile updated successfully');
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
            'dob' => 'required|date|after:today',
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


        return view('admin.pos.index', compact('products','categories','brands','customers'))
        ->with('pageTitle',  $this->shopDetail->name . ' | POS System');
    }




}
