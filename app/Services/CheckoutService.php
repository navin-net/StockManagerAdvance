<?php

namespace App\Services;

use App\Models\{Cart, Products, Sale};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function processCheckout(Cart $cart, array $data = []): Sale
    {
        return DB::transaction(function () use ($cart, $data) {
            $cart = Cart::whereKey($cart->id)->lockForUpdate()->firstOrFail();
            abort_unless((int) $cart->user_id === (int) auth()->id(), 403);
            $items = $cart->items()->orderBy('product_id')->get();
            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'Cart is empty or has already been checked out']);
            }
            $products = Products::whereIn('id', $items->pluck('product_id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $total = 0;
            foreach ($items as $item) {
                $product = $products->get($item->product_id);
                if (!$product || $item->quantity < 1 || $product->stock_quantity < $item->quantity) {
                    throw ValidationException::withMessages(['cart' => 'One or more products have insufficient stock']);
                }
                $total += round($product->selling_price * $item->quantity, 2);
            }
            if ($total > 99999999.99) {
                throw ValidationException::withMessages(['cart' => 'Order total is too large']);
            }
            // Shopping checkout reserves inventory; collection of payment is a separate staff action.
            $sale = Sale::create([
                'reference' => Sale::generateReference(),
                'customer_id' => auth()->user()->company_id,
                'user_id' => $cart->user_id,
                'subtotal' => $total,
                'total_amount' => $total,
                'status' => 'pending',
                'date' => now()->toDateString(),
                'payment_status' => 'pending',
                'payment_method' => $data['payment_method'] ?? 'cash',
            ]);
            foreach ($items as $item) {
                $product = $products[$item->product_id];
                $product->decrement('stock_quantity', $item->quantity);
                $sale->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'sale_price' => $product->selling_price,
                    'subtotal' => round($product->selling_price * $item->quantity, 2),
                ]);
            }
            $cart->items()->delete();
            return $sale->load('items.product', 'payments');
        }, 3);
    }
}
