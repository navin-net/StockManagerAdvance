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
        $user = Auth::user();
        $cartKey = request('cart_key');

        $cart = $this->cartService->getOrCreateCart($user, $cartKey);

        return response()->json(
            new CartResource($cart->load('items.product'))
        );
    }

    public function store(AddToCartRequest $request): JsonResponse
    {
        $user = Auth::user();
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
        $this->cartService->updateQuantity($cartItem, $request->quantity);

        return response()->json([
            'message' => 'Cart item updated',
            'cart'    => new CartResource($cartItem->cart->fresh()->load('items.product'))
        ]);
    }

    public function destroy(CartItem $cartItem): JsonResponse
    {
        $this->cartService->removeItem($cartItem);

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function clear(): JsonResponse
    {
        $user = Auth::user();
        $cartKey = request('cart_key');
        $cart = $this->cartService->getOrCreateCart($user, $cartKey);

        $this->cartService->clearCart($cart);

        return response()->json(['message' => 'Cart cleared successfully']);
    }
}
