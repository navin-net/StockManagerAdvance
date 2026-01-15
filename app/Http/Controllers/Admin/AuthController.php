<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{Auth, Cookie, DB, Hash, RateLimiter, Storage};
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

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'name';

        $credentials = [
            $field => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            $request->session()->regenerate();

            if (!in_array($user->group_id, [1,2,3])) {
                Auth::logout();
                return back()->withErrors(['login' => 'No permission']);
            }

            $lastUrl = Cookie::get('admin_last_url');

            return $lastUrl
                ? redirect($lastUrl)
                : redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'login' => 'Invalid credentials',
        ]);
    }

    public function logout(Request $request)
    {
        $registerId = session('cash_register_id');

        if ($registerId) {
            DB::table('cash_registers')->where('id', $registerId)->update([
                'closing_balance' => 0,
                'closed_at' => now(),
                'status' => 'closed'
            ]);
        }

        session()->forget(['register_opened', 'cash_register_id', 'opened_at']);

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
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
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


}
