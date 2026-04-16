<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\{Cart, Payment, Products, Sale, SaleItem};

class CheckoutService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function processCheckout(Cart $cart, array $data = []): Sale
    {
        if ($cart->items->isEmpty()) {
            throw new \Exception('Cart is empty');
        }

        return DB::transaction(function () use ($cart, $data) {

            $totalAmount = $cart->items->sum(fn($i) => $i->quantity * $i->price);

            // Create Sale
            $sale = Sale::create([
                'reference'        => 'SALE-' . now()->format('YmdHis'),
                'customer_id'      => $data['customer_id'] ?? null,
                'user_id'          => $cart->user_id ?? auth()->id(),
                'total_amount'     => $totalAmount,
                'status'           => 'completed',
                'date'             => now()->toDateString(),
                'payment_status'   => 'paid',
                'cash_register_id' => $data['cash_register_id'] ?? null,
            ]);

            // Create Sale Items + Reduce Stock
            foreach ($cart->items as $cartItem) {
                $product = Products::findOrFail($cartItem->product_id);

                $product->decrement('stock_quantity', $cartItem->quantity);

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'sale_price' => $cartItem->price,
                ]);
            }

            // Create Payment
            Payment::create([
                'sale_id'    => $sale->id,
                'reference'  => 'PAY-' . Str::random(8),
                'method'     => $data['payment_method'] ?? 'cash',
                'amount'     => $totalAmount,
                'paid_at'    => now(),
                'created_by' => auth()->id(),
            ]);

            // Clear Cart
            $this->cartService->clearCart($cart);

            return $sale->load('items.product', 'payments');
        });
    }
}
