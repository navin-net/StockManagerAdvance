<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\BaseController;
use App\Models\{Brand, Categories, Companies, Payment, Products, Sale, SaleItem, Warehouses};

class PosController extends BaseController
{
    public function __construct()
    {
        parent::__construct();


    }

    public function openRegister()
    {

        $id = auth()->id();

        // dd($id);

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

        // Check if already open
        $existing = DB::table('pos_registers')
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return redirect()->route('pos.index');
        }

        // Create register
        $registerId = DB::table('pos_registers')->insertGetId([
            'user_id' => auth()->id(),
            'cash_in_hand' => $request->cash_in_hand,
            'status' => 'open',
            'date' => now(),
        ]);

        // Create sale
        $sale = Sale::create([
            'total_amount' => 0,
            // 'reference' => 'reference',
            'customer_id' => 4, // default customer
            'cash_register_id' => $registerId,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        $sale->reference = 'POS-' . now()->format('Ymd') . '-' . $sale->id;

        $sale->save();
        // dd($sale);

        session([
            'register_opened' => true,
            'cash_register_id' => $registerId,
            'sale_id' => $sale->id,
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

        DB::table('pos_registers')->where('id', $register->id)->update([
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

        if (!$register) {
            return redirect()->route('pos.openRegister');
        }

        session([
            'register_opened' => true,
            'cash_register_id' => $register->id,
        ]);




        $sale = Sale::where('cash_register_id', $register->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$sale) {
            $sale = Sale::create([
                'reference'        => Sale::generateReference(),
                'customer_id'      => 4,
                'cash_register_id' => $register->id,
                'user_id'          => auth()->id(),
                'total_amount'     => 0,
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'date'             => now()->toDateString(),
            ]);
        }
    session(['sale_id' => $sale->id]);

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
        // dd($register);
        $categories = Categories::all();
        $brands = Brand::all();
        $customers = Companies::where('group_id', 4)->get();
        $warehouse = Warehouses::where('id',1)->get();

        return view('admin.pos.index1', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'sales' => $sale,
            'pageTitle' => __('messages.pos_system'),
            'heading' => __('messages.pos_system'),
            'customers' => $customers,
            'warehouse' => $warehouse,
            'records' => $register,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:companies,id',
            'warehouse_id' => 'required',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,qr',
            'amount_paid' => 'required|numeric|gt:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        $sale = DB::transaction(function () use ($request) {

            // ── Step 1: Lock products & validate stock ────────────
            $rows = [];
            $subtotal = 0;

            foreach ($request->cart as $item) {
                $product = Products::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock_quantity < $item['qty']) {
                    throw new \Exception("Insufficient stock for: {$product->name}");
                }

                $lineTotal = round($product->selling_price * $item['qty'], 2);
                $subtotal += $lineTotal;
                $rows[] = [
                    'product' => $product,
                    'qty' => (int) $item['qty'],
                    'lineTotal' => $lineTotal,
                ];
            }

            // ── Step 2: Calculate totals ──────────────────────────
            $discountType = $request->discount_type ?? 'fixed';
            $discountValue = (float) ($request->discount_value ?? 0);
            $discountAmt = $discountType === 'percentage'
                ? round($subtotal * $discountValue / 100, 2)
                : min($discountValue, $subtotal);

            $afterDiscount = round($subtotal - $discountAmt, 2);
            $tax = round($afterDiscount * 0.08, 2);
            $totalAmount = round($afterDiscount + $tax, 2);

            $amountPaid = (float) $request->amount_paid;
            $posBalance = round($amountPaid - $totalAmount, 2);
            $paymentStatus = $amountPaid >= $totalAmount ? 'paid' : 'partial';

            // ── Step 3: Update the pending sale from session ──────
            $sale = Sale::find(session('sale_id'));

            if ($sale && $sale->status === 'pending') {
                $sale->update([
                    'customer_id' => $request->customer_id ?? $sale->customer_id,
                    'warehouse_id' => $request->warehouse_id ?? $sale->warehouse_id,
                    'subtotal' => $subtotal,
                    'discount' => $discountAmt,
                    'discount_type' => $discountType,
                    'discount_value' => $discountValue,
                    'tax' => $tax,
                    'total_amount' => $totalAmount,
                    'status' => 'completed',
                    'payment_status' => $paymentStatus,
                    // 'note' => $request->note,
                    'date' => now()->toDateString(),
                ]);
            } else {
            //     // Fallback: create a fresh sale
                $sale = Sale::create([
                    'reference' => Sale::generateReference(),
                    'customer_id' => $request->customer_id ?? 4,
                    'cash_register_id' => session('cash_register_id'),
                    'user_id' => auth()->id(),
                    'total_amount' => $totalAmount,
                    'status' => 'completed',
                    'payment_status' => $paymentStatus,
                    // 'note' => $request->note,
                    'date' => now()->toDateString(),
                ]);
            }

            // ── Step 4: Insert sale items & decrement stock ───────
            foreach ($rows as $row) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $row['product']->id,
                    'product_name' => $row['product']->name,
                    'sale_price' => $row['product']->selling_price,
                    'quantity' => $row['qty'],
                    'subtotal' => $row['lineTotal'],
                ]);

                $row['product']->decrement('stock_quantity', $row['qty']);
            }

            // ── Step 5: Record payment ────────────────────────────
            Payment::create([
                'sale_id' => $sale->id,
                'reference' => $sale->reference,
                'method' => $request->payment_method,
                'amount' => $amountPaid,
                'pos_paid' => $amountPaid,
                'pos_balance' => $posBalance,
                'paid_at' => now(),
                'note' => $request->note,
                'created_by' => auth()->id(),
            ]);


            if ($request->payment_method === 'cash') {
                DB::table('pos_registers')
                    ->where('id', session('cash_register_id'))
                    ->update([
                        'total_cash' => DB::raw("total_cash + {$amountPaid}"),
                        'cash_in_hand' => DB::raw("cash_in_hand - {$posBalance}")
                    ]);
            }

            // ── Step 6: Clear sale_id so index creates a new pending sale ──
            session()->forget('sale_id');

            return $sale;
        });

        return response()->json([
            'success' => true,
            'sale_id' => $sale->id,
            'reference' => $sale->reference,
            'receipt_url' => route('pos.receipt', $sale->id),
        ]);
    }


    public function receipt(Sale $sale)
    {
        $sale->load(['customer', 'items.product', 'payments', 'user']);
        $companyId = auth()->user()->company_id;

        $billers = Companies::where('id', $companyId)
            ->where('group_name', 'biller')
            ->get();
        // die($billers);
        // ['label' => __('messages.dashboard'), 'url' => '/admin/dashboard', 'active' => false],

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
