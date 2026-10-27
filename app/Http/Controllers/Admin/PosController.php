<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\BaseController;
use App\Models\{Brand, Categories, Companies, Payment, Products, Sale, SaleItem, Warehouses,PosRegisters};

class PosController extends BaseController
{
    public function __construct()
    {
        parent::__construct();


    }

    public function openRegister()
    {
        $id = auth()->id();
        $openRegister = DB::table('pos_registers')
            ->where('user_id', $id)
            ->where('status', 'open')
            ->first();
        // dd($openRegister);
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

        $registerId = DB::transaction(function () use ($request) {
            \App\Models\User::whereKey(auth()->id())->lockForUpdate()->firstOrFail();
            $existing = DB::table('pos_registers')->where('user_id', auth()->id())->where('status', 'open')->lockForUpdate()->first();
            if ($existing) {
                return $existing->id;
            }
            $id = DB::table('pos_registers')->insertGetId([
                'user_id' => auth()->id(), 'cash_in_hand' => round($request->cash_in_hand, 2),
                'total_cash' => 0, 'status' => 'open', 'opened_at' => now(),
            ]);
            DB::table('pos_registers')->where('id', $id)->update(['reference' => 'POS-' . now()->format('Ymd') . '-' . $id]);
            return $id;
        });

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

        DB::transaction(function () use ($validated) {
            $register = DB::table('pos_registers')->where('user_id', auth()->id())->where('status', 'open')->lockForUpdate()->first();
            abort_unless($register, 422, 'No open register found');
            DB::table('pos_registers')->where('id', $register->id)->update([
                'total_cash' => round($validated['total_cash'], 2), 'closed_by' => auth()->id(),
                'closed_at' => now(), 'status' => 'closed',
            ]);
        });

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




        if (!$register) {
            return redirect()->route('pos.open-register');
        }

        session([
            'register_opened' => true,
            'cash_register_id' => $register->id,
        ]);
        $products = Products::select(
            'products.id',
            'products.name',
            'products.selling_price',
            'products.stock_quantity',
            'products.image',
            'products.brand_id',
            'products.code',
            'products.category_id',
            'products.subcategory_id',
            'categories.name as category',
            'sub_categories.name as subcategory'
        )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->get();

        $id = $register->id;

//        dd($id);


        $categories = Categories::all();
        $brands = Brand::all();
        $customers = Companies::where('group_id', 4)->get();
        $warehouse = Warehouses::where('id',1)->get();

        $total = Payment::whereHas('sale', fn ($query) => $query->where('cash_register_id', $id))
            ->where('method', 'cash')->where('status', 'completed')->sum('amount');
        $total += DB::table('register_cash_movements')->where('register_id', $id)
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN amount ELSE -amount END), 0) as net")->value('net');

//        dd($total);

        return view('admin.pos.index1', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            // 'sales' => $sale,
            'pageTitle' => __('messages.pos_system'),
            'heading' => __('messages.pos_system'),
            'customers' => $customers,
            'warehouse' => $warehouse,
            'records' => $register,
            'total' => $total,
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:companies,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|integer|distinct|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1|max:1000000',
            'payment_method' => 'required|in:cash,card,qr,bank',
            'amount_paid' => 'required|numeric|gt:0|max:99999999.99',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric|min:0|max:99999999.99',
            'note' => 'nullable|string|max:500',
        ]);
        if (($data['discount_type'] ?? 'fixed') === 'percentage' && ($data['discount_value'] ?? 0) > 100) {
            throw \Illuminate\Validation\ValidationException::withMessages(['discount_value' => 'Percentage must be between 0 and 100']);
        }
        $sale = DB::transaction(function () use ($data) {
            $register = DB::table('pos_registers')->where('user_id', auth()->id())->where('status', 'open')->lockForUpdate()->first();
            abort_unless($register, 422, 'Open a register before making a sale');
            $products = Products::whereIn('id', array_column($data['cart'], 'id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $subtotal = 0;
            foreach ($data['cart'] as $item) {
                $product = $products[$item['id']];
                if ($product->stock_quantity < $item['qty']) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['cart' => 'Insufficient stock for ' . $product->name]);
                }
                $subtotal += round($product->selling_price * $item['qty'], 2);
            }
            if ($subtotal > 99999999.99) {
                throw \Illuminate\Validation\ValidationException::withMessages(['cart' => 'Sale total is too large']);
            }
            $type = $data['discount_type'] ?? 'fixed';
            $value = $data['discount_value'] ?? 0;
            $discount = $type === 'percentage' ? round($subtotal * $value / 100, 2) : min(round($value, 2), $subtotal);
            $total = round($subtotal - $discount, 2);
            $tendered = round($data['amount_paid'], 2);
            $applied = min($tendered, $total);
            $change = max(0, round($tendered - $total, 2));
            if ($data['payment_method'] !== 'cash' && $change > 0) {
                throw \Illuminate\Validation\ValidationException::withMessages(['amount_paid' => 'Noncash payments cannot exceed the total']);
            }
            $sale = Sale::create([
                'reference' => Sale::generateReference(),
                'customer_id' => $data['customer_id'] ?? null,
                'warehouse_id' => $data['warehouse_id'] ?? null,
                'cash_register_id' => $register->id,
                'user_id' => auth()->id(), 'sale_type' => 'pos',
                'subtotal' => $subtotal, 'discount' => $discount,
                'discount_type' => $type, 'discount_value' => $value,
                'total_amount' => $total, 'status' => 'completed',
                'payment_status' => $applied >= $total ? 'completed' : 'partial',
                'payment_method' => $data['payment_method'], 'date' => now()->toDateString(),
            ]);
            foreach ($data['cart'] as $item) {
                $product = $products[$item['id']];
                $sale->items()->create([
                    'product_id' => $product->id, 'product_name' => $product->name,
                    'quantity' => $item['qty'], 'sale_price' => $product->selling_price,
                    'subtotal' => round($product->selling_price * $item['qty'], 2),
                ]);
                $product->decrement('stock_quantity', $item['qty']);
            }
            $sale->payments()->create([
                'reference' => $sale->reference, 'method' => $data['payment_method'],
                'amount' => $applied, 'pos_paid' => $tendered, 'pos_balance' => $change,
                'status' => 'completed', 'paid_at' => now(), 'created_by' => auth()->id(),
            ]);
            if ($data['payment_method'] === 'cash') {
                DB::table('pos_registers')->where('id', $register->id)->update(['total_cash' => round(($register->total_cash ?? 0) + $applied, 2)]);
            }
            session()->forget('sale_id');
            return $sale;
        }, 3);
        return response()->json([
            'success' => true, 'sale_id' => $sale->id,
            'reference' => $sale->reference, 'receipt_url' => route('pos.receipt', $sale->id),
        ]);
    }

    public function addCash(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:99999999.99',
            'type' => 'sometimes|in:in,out',
            'reason' => 'required|string|max:255',
        ]);
        DB::transaction(function () use ($data) {
            $register = DB::table('pos_registers')->where('user_id', auth()->id())->where('status', 'open')->lockForUpdate()->first();
            abort_unless($register, 422, 'No open register');
            DB::table('register_cash_movements')->insert([
                'register_id' => $register->id, 'user_id' => auth()->id(),
                'type' => $data['type'] ?? 'in', 'amount' => round($data['amount'], 2),
                'reason' => $data['reason'], 'created_at' => now(), 'updated_at' => now(),
            ]);
        });
        return back()->with('success', 'Cash movement recorded');
    }


    public function receipt(Sale $sale)
    {
        $sale->load(['customer', 'items.product', 'payments', 'user']);
        $companyId = auth()->user()->company_id;

        $billers = Companies::where('id', $companyId)
            ->where('group_name', 'biller')
            ->get();
        // die($billers);

        return view('admin.pos.receipt', [
            'sale' => $sale,
            'billers' => $billers,
            'pageTitle' => 'Receipt — ' . $sale->reference,
            'heading' => 'Receipt',
            'breadcrumbs' => [
                ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],
                ['label' => 'POS', 'url' => route('pos.index'), 'active' => false],
                ['label' => $sale->reference, 'url' => '', 'active' => true],
            ],
        ]);
    }

}
