<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Auth, Hash, RateLimiter, Storage};
use App\Models\{Products, Purchase, Sale, User};

class AuthController extends Controller
{

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

        $loginInput = $request->login;
        $key = 'login|' . $request->getClientIp() . '|' . $loginInput;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'login' => 'Too many login attempts. Try again in ' . gmdate('i:s', $seconds) . ' minutes.',
            ])->withInput($request->only('login'));
        }

        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $credentials = [$field => $loginInput,'password' => $request->password];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!in_array($user->group_id ?? 1, [1])) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Your account does not have permission to log in.',
                ])->withInput($request->only('login'));
            }

            $user->ip_address = $request->getClientIp();
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

        $productCount = Products::count();
        $salesCount = Sale::count();
        $saleTotal = Sale::sum('total_amount');
        $Purchases = Purchase::count();
        // die($avg_sales);
        return view('admin.dashboard', [
            'ipFromDB' => $ipFromDB,
            'salesCount' => $salesCount,
            'saleTotal' => $saleTotal,
            'avg_sales' => $Purchases,
            'productCount' => $productCount

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
