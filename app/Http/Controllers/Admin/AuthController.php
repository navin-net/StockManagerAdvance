<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Products;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // Show the registration form
    public function showRegisterForm()
    {
        $groups = DB::table('groups')->select('id', 'name')->get();
        return view('admin.auth.register', compact('groups'));
    }




    // Handle user registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'group_id' => 'required|exists:groups,id', // validate group

        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'role_id' => 2, // Default role is 'user'
            'group_id' => $request->group_id,

        ]);

        return redirect()->route('/')->with('success', 'Registration successful. Please log in.');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $key = 'login|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'login' => 'Too many login attempts. Try again in ' . gmdate('i:s', $seconds) . ' minutes.',
            ])->withInput($request->only('login'));
        }

        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $field => $loginInput,
            'password' => $request->input('password'),
        ];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

        if ($user->group_id != 1 && $user->group_id != 2){
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Your account does not have permission to log in.',
                ])->withInput($request->only('login'));
            }

            $user->ip_address = $request->ip();
            $user->save();

            RateLimiter::clear($key);
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        RateLimiter::hit($key, 180);

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('login'));
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('/')->with('status', 'You have been logged out.');
    }

    public function dashboard()
    {
        $currentUser = auth()->user();
        $ipFromDB = $currentUser->ip_address;


        return view('admin.dashboard', [
            'ipFromDB' => $ipFromDB
    
        ]);
    }
    public function getAlerts()
    {
        return Products::where('stock_quantity', '<=', -1)->get(['id', 'name', 'stock_quantity']);
    }


    public function edit($id)
    {
        $user = User::with('profile')->findOrFail($id);

        return view('admin.profile.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'dob' => 'nullable|date',
            'old_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->save();

        $profileData = ['dob' => $request->dob];

        if ($request->hasFile('image')) {
            if ($user->profile && $user->profile->image) {
                Storage::disk('public')->delete($user->profile->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $profileData['image'] = $image->storeAs('profiles', $imageName, 'public');
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        if ($request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'The current password is incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }



}
