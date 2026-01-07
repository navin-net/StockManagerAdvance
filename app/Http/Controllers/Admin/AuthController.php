<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Auth, Hash, RateLimiter, Storage};
use App\Models\{Companies, Products, Purchase, Sale, User};

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
        $credentials = [$field => $loginInput, 'password' => $request->password];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!in_array($user->group_id ?? 1, [1,2,3])) {
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
    public function edit()
    {
        // $user = User::with('profile')->findOrFail($id);

        $user = Auth::user();
        $id = $user->id;
        $company = Companies::find($id);
        // die($user);
        return view('admin.profile.edit1', [
            'pageTitle' => __('messages.my_account'),
            'heading' => __('messages.my_account'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('dashboard'), 'active' => false],
                ['label' => __('messages.my_account'), 'url' => '', 'active' => true],
            ],
            'user' => $user,
            'company' => $company,
        ]);

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



    //    public function update(Request $request, $id)
//     {
//         $user = User::findOrFail($id);

    //         $validated = $request->validate([
//             'phone' => 'required|string|max:15',
//             'first_name' => 'nullable|string|max:50',
//             'last_name' => 'nullable|string|max:50',
//             'old_password' => 'required_with:new_password|string',
//             'new_password' => 'nullable|string|min:8|confirmed',
//             'avatar' => 'nullable|image|max:2048',
//         ]);

    //         $user->fill([
//             'phone' => $validated['phone'],
//             'first_name' => $validated['first_name'] ?? $user->first_name,
//             'last_name' => $validated['last_name'] ?? $user->last_name,
//         ]);

    //         if ($request->filled('new_password')) {
//             if (!Hash::check($request->old_password, $user->password)) {
//                 return back()->withErrors(['old_password' => 'The current password is incorrect.']);
//             }
//             $user->password = Hash::make($request->new_password);
//             $user->save();
//         }

    //         if ($request->hasFile('avatar')) {
//             if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
//                 Storage::disk('public')->delete($user->avatar);
//             }
//             $user->avatar = $request->file('avatar')->store('profiles', 'public');
//         }

    //         $user->save();

    //         return back()->with('success', 'Profile updated successfully');
//     }



}
