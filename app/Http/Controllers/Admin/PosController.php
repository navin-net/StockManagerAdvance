<?php

namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\BaseController;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Brand, Categories, Companies, Products, User};

class PosController extends BaseController
{
    public function __construct()
    {
        parent::__construct();


    }

    public function openRegister()
    {
        $openRegister = DB::table('pos_registers')
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($openRegister) {

            // Restore session if missing
            session([
                'register_opened' => true,
                'cash_register_id' => $openRegister->id
            ]);

            return redirect()->route('pos.index');
        }

        return view('admin.pos.open-register', [
            'pageTitle' => __('messages.open_register'),
            'heading' => __('messages.open_register'),
            'description' => __('messages.dashboard_welcome'),
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => route('admin.dashboard'), 'active' => false],
                ['label' => __('messages.open_register'), 'url' => '', 'active' => true],
            ]
        ]);
    }


    // Open register
    public function storeOpenRegister(Request $request)
    {
        $request->validate([
            'cash_in_hand' => 'required|numeric|min:0',
        ]);

        // 🔥 Check if already open
        $existing = DB::table('pos_registers')
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return redirect()->route('pos.index');
        }

        $registerId = DB::table('pos_registers')->insertGetId([
            'user_id' => auth()->id(),
            'cash_in_hand' => $request->cash_in_hand,
            'status' => 'open',
            'date' => now(),
        ]);

        session([
            'register_opened' => true,
            'cash_register_id' => $registerId,
        ]);

        return redirect()->route('pos.index');
    }

    // Close register

    public function closeRegister(Request $request)
    {
        $validated = $request->validate([
            'total_cash' => 'required|numeric|min:0',
        ]);

        $register = DB::table('pos_registers')
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$register) {
            return redirect('/admin')
                ->with('error', 'No open register found.');
        }

        DB::table('pos_registers')
            ->where('id', $register->id)
            ->update([
                'total_cash' => $validated['total_cash'],
                'closed_by' => auth()->id(),
                'closed_at' => now(),
                'status' => 'closed',
            ]);

        // Clear session
        session()->forget(['register_opened', 'cash_register_id']);

        return redirect('/admin')->with('success', 'Cash register closed successfully.');
    }


    public function index()
    {
        $register = DB::table('pos_registers')
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

    // dd($register);


        if (!$register) {
            return redirect()->route('pos.openRegister');
        }

        // Restore session if needed
        session([
            'register_opened' => true,
            'cash_register_id' => $register->id
        ]);

        $products = Products::all();
        $categories = Categories::all();
        $brands = Brand::all();
        $customers = Companies::where('group_id', 4)->get();

        return view('admin.pos.index1', [
            'products'   => $products,
            'categories' => $categories,
            'brands'     => $brands,
            'pageTitle'  => __('messages.pos_system'),
            'heading'    => __('messages.pos_system'),
            'customers'  => $customers,
            'records'   => $register,
        ]);
    }


    // public function index()
    // {
    //     $id = session('cash_register_id');

    //     $records = DB::table('pos_registers')->where('id', $id)->first();
    //     $products = Products::all();
    //     $categories = Categories::all();
    //     $brands = Brand::all();
    //     $customers = Companies::where('group_id', 4)->get();

    //     // die($products);


    //     return view('admin.pos.index1', [
    //         'pageTitle' => __('messages.pos_system'),
    //         'heading' => __('messages.pos_system'),
    //         'brands' => $brands,
    //         'categories' => $categories,
    //         'customers' => $customers,
    //         'products' => $products,
    //         'records' => $records
    //     ]);

    // }

}
