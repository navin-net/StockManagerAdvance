<?php

namespace App\Http\Controllers\Api\Shop;



use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\{AddToCartRequest, UpdateCartItemRequest};
use App\Http\Resources\CartResource;
use App\Models\{CartItem, Products};
use App\Services\CartService;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        $cartKey = request('cart_key');

        $cart = $this->cartService->getOrCreateCart($user, $cartKey);

        return response()->json(
            new CartResource($cart->load('items.product'))
        );
    }

    public function store(AddToCartRequest $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        $cartKey = $request->cart_key;

        $cart = $this->cartService->getOrCreateCart($user, $cartKey);
        $product = Products::findOrFail($request->product_id);

        $this->cartService->addToCart($cart, $product, $request->quantity ?? 1);

        return response()->json([
            'message' => 'Product added to cart successfully',
            'cart'    => new CartResource($cart->fresh()->load('items.product'))
        ], 201);
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): JsonResponse
    {
        $this->authorizeItem($cartItem);
        $this->cartService->updateQuantity($cartItem, $request->quantity);

        return response()->json([
            'message' => 'Cart item updated',
            'cart'    => new CartResource($cartItem->cart->fresh()->load('items.product'))
        ]);
    }

    public function destroy(CartItem $cartItem): JsonResponse
    {
        $this->authorizeItem($cartItem);
        $this->cartService->removeItem($cartItem);

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function clear(): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        $cartKey = request('cart_key');
        $cart = $this->cartService->getOrCreateCart($user, $cartKey);

        $this->cartService->clearCart($cart);

        return response()->json(['message' => 'Cart cleared successfully']);
    }
    private function authorizeItem(CartItem $item): void
    {
        $user = Auth::guard('sanctum')->user();
        $cart = $item->cart;
        if ($user) {
            abort_unless($user->isActive() && (int) $cart->user_id === (int) $user->id, 403);
        } else {
            $key = request('cart_key');
            abort_unless($cart->user_id === null && is_string($key) && $cart->cart_key
                && hash_equals($cart->cart_key, $key), 403);
        }
    }
}
