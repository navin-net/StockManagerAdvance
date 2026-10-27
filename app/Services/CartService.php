<?php

namespace App\Services;

use App\Models\{Cart, CartItem, Products, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getOrCreateCart($user = null, ?string $cartKey = null): Cart
    {
        if ($user) {
            abort_unless($user->isActive(), 403, 'Account disabled');
            return DB::transaction(function () use ($user, $cartKey) {
                // Serialize cart creation and guest-cart adoption for this user.
                User::whereKey($user->id)->lockForUpdate()->firstOrFail();
                $cart = Cart::firstOrCreate(['user_id' => $user->id]);
                if ($cartKey) {
                    $guest = Cart::where('cart_key', $cartKey)->whereNull('user_id')->lockForUpdate()->firstOrFail();
                    foreach ($guest->items()->orderBy('product_id')->get() as $item) {
                        $this->addToCart($cart, Products::findOrFail($item->product_id), $item->quantity);
                    }
                    $guest->items()->delete();
                    $guest->delete();
                }
                return $cart;
            });
        }

        if ($cartKey) {
            // A caller cannot choose a predictable key for a newly created cart.
            return Cart::where('cart_key', $cartKey)->whereNull('user_id')->firstOrFail();
        }
        return Cart::create(['cart_key' => Str::uuid()->toString()]);
    }

    public function addToCart(Cart $cart, Products $product, int $quantity = 1)
    {
        return DB::transaction(function () use ($cart, $product, $quantity) {
            Cart::whereKey($cart->id)->lockForUpdate()->firstOrFail();
            $product = Products::whereKey($product->id)->lockForUpdate()->firstOrFail();
            $item = $cart->items()->where('product_id', $product->id)->first();
            $newQuantity = ($item?->quantity ?? 0) + $quantity;
            if ($quantity < 1 || $newQuantity > $product->stock_quantity) {
                throw ValidationException::withMessages(['quantity' => 'Insufficient stock for ' . $product->name]);
            }
            return $cart->items()->updateOrCreate(['product_id' => $product->id], [
                'quantity' => $newQuantity,
                'price' => $product->selling_price,
            ]);
        });
    }

    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        return DB::transaction(function () use ($item, $quantity) {
            Cart::whereKey($item->cart_id)->lockForUpdate()->firstOrFail();
            $item = CartItem::findOrFail($item->id);
            $product = Products::whereKey($item->product_id)->lockForUpdate()->firstOrFail();
            if ($quantity < 1 || $quantity > $product->stock_quantity) {
                throw ValidationException::withMessages(['quantity' => 'Insufficient stock']);
            }
            $item->update(['quantity' => $quantity, 'price' => $product->selling_price]);
            return $item;
        });
    }

    public function removeItem(CartItem $item): bool
    {
        return DB::transaction(function () use ($item) {
            Cart::whereKey($item->cart_id)->lockForUpdate()->firstOrFail();
            return (bool) $item->delete();
        });
    }

    public function clearCart(Cart $cart): void
    {
        DB::transaction(function () use ($cart) {
            Cart::whereKey($cart->id)->lockForUpdate()->firstOrFail();
            $cart->items()->delete();
        });
    }
}
