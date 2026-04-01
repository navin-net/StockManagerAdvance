<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\{Auth, Cookie, DB, Hash, RateLimiter, Storage};
use App\Models\{Companies, Products, Purchase, Sale, User};
use Carbon\CarbonPeriod;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\View\View;

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
        $remember = $request->has('remember');

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $credentials = [
            $field => $request->login,
            'password' => $request->password,
        ];
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->status == 0) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Your account access has been denied.'
                ]);
            }
            if (!in_array($user->group_id, [1, 2, 3])) {
                Auth::logout();
                return back()->withErrors(['login' => 'No permission']);
            }

            $ip = $request->ip();

            if ($ip === '127.0.0.1' || $ip === '::1') {
                $ip = getHostByName(getHostName());
            }

            $user->update([
                'ip_address' => $ip,
            ]);

            $request->session()->regenerate();

            // return redirect()->route('admin.dashboard');
            return redirect()->intended('/admin');


        }
        return back()->withErrors([
            'login' => 'Invalid credentials',
        ]);
    }

    public function logout(Request $request)
    {
        // $registerId = session('cash_register_id');

        // if ($registerId) {
        //     DB::table('cash_registers')->where('id', $registerId)->update([
        //         'closing_balance' => 0,
        //         'closed_at' => now(),
        //         'status' => 'closed'
        //     ]);
        // }

        // session()->forget(['register_opened', 'cash_register_id', 'opened_at']);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('status', 'You have been logged out.');
    }



    public function dashboard(): View
    {
        $currentUser = auth()->user();
        $ipFromDB = $currentUser->ip_address;

        $productCount = Products::count();
        $salesCount = Sale::count();
        $saleTotal = Sale::sum('total_amount');
        $Purchases = Purchase::count();

        $brands = DB::table('brands')
            ->join('products', 'products.brand_id', '=', 'brands.id')
            ->select(
                'brands.id',
                'brands.name',
                'brands.slug',
                'brands.image',
                DB::raw('COUNT(sma_products.id) as total')
            )
            ->groupBy(
                'brands.id',
                'brands.name',
                'brands.slug',
                'brands.image'
            )
            ->orderBy('brands.name')
            ->get();

        $labels = $brands->pluck('name');
        $data = $brands->pluck('total');



        return view('admin.dashboard', [
            'ipFromDB' => $ipFromDB,
            'salesCount' => $salesCount,
            'saleTotal' => $saleTotal,
            'avg_sales' => $Purchases,
            'labels' => $labels,
            'data' => $data,
            'brands' => $brands,
            'productCount' => $productCount

        ]);
    }



}
