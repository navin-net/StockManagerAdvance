<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\{Cart, CartItem, Products};

class CartService
{
    public function getOrCreateCart($user = null, ?string $cartKey = null): Cart
    {
        if ($user) {
            return Cart::firstOrCreate(['user_id' => $user->id]);
        }

        if (empty($cartKey)) {
            $cartKey = Str::uuid()->toString();
        }

        return Cart::firstOrCreate(['cart_key' => $cartKey]);
    }

    public function addToCart(Cart $cart, Products $product, int $quantity = 1)
    {
        if ($product->stock_quantity < $quantity) {
            throw new \Exception("Insufficient stock for product: " . $product->name);
        }

        return DB::transaction(function () use ($cart, $product, $quantity) {

            $cartItem = CartItem::where('cart_id', $cart->id)
                                ->where('product_id', $product->id)
                                ->first();

            if ($cartItem) {
                // Update existing item
                $newQty = $cartItem->quantity + $quantity;
                if ($newQty > $product->stock_quantity) {
                    $newQty = $product->stock_quantity;
                }
                $cartItem->update([
                    'quantity' => $newQty,
                    'price'    => $product->selling_price,
                ]);
                return $cartItem;
            } else {
                // Create new item
                $finalQty = min($quantity, $product->stock_quantity);
                return CartItem::create([
                    'cart_id'    => $cart->id,
                    'product_id' => $product->id,
                    'quantity'   => $finalQty,
                    'price'      => $product->selling_price,
                ]);
            }
        });
    }

    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        $quantity = max(1, $quantity);

        if ($quantity > $item->product->stock_quantity) {
            $quantity = $item->product->stock_quantity;
        }

        $item->update(['quantity' => $quantity]);
        return $item;
    }

    public function removeItem(CartItem $item): bool
    {
        return $item->delete();
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }
}
