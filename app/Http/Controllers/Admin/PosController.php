<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        if (session()->get('register_opened')) {
            return redirect()->route('pos.index');
        }
        return view('admin.pos.open-register');
    }

    // Open register
    public function storeOpenRegister(Request $request)
    {
        $request->validate([
            // 'cash_in_hand' => ['required', 'numeric', 'min:1'],
            'cash_in_hand' => 'required',

        ]);

        $registerId = DB::table('pos_registers')->insertGetId([
            'user_id' => auth()->id(),
            'cash_in_hand' => $request->cash_in_hand,
            'status' => 'open',
            'date' => now(),
        ]);

        session([
            'register_opened' => true,
            'cash_register_id' => $registerId,
            'date' => now()
        ]);

        return redirect()->route('pos.index');
    }

    // Close register

    public function closeRegister(Request $request)
    {
        $validated = $request->validate([
            'total_cash' => [ 'numeric', 'min:0'],
        ]);

        $registerId = session('cash_register_id');

        if (!$registerId) {
            return redirect()->route('dashboard')
                ->with('error', 'No open cash register found in session.');
        }

        DB::table('pos_registers')
            ->where('id', $registerId)
            ->update([
                'total_cash' => (float) $validated['total_cash'],
                // 'total_cash' => (int) ['total_cash'],
                // 'total_cash' => 'total_cash',
                'closed_by' => auth()->id(),
                'closed_at' => now(),
                'note'  => 'note',
                'status' => 'closed',
            ]);

        session()->forget(['register_opened', 'cash_register_id', 'date']);

        return redirect()->back()
            ->with('success', 'Cash register closed successfully.');

    }


    public function index()
    {
        $id = session('cash_register_id');

        $records = DB::table('pos_registers')->where('id', $id)->first();
        $products = Products::all();
        $categories = Categories::all();
        $brands = Brand::all();
        $customers = Companies::where('group_id', 4)->get();

        // die($products);


        return view('admin.pos.index1', [
            'pageTitle' => __('messages.pos_system'),
            'heading' => __('messages.pos_system'),
            'brands' => $brands,
            'categories' => $categories,
            'customers' => $customers,
            'products' => $products,
            'records' => $records
        ]);

    }

}
