<?php

namespace App\Http\Controllers\Api\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Services\{CartService, CheckoutService};

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;
    protected CartService $cartService;

    public function __construct(CheckoutService $checkoutService, CartService $cartService)
    {
        $this->checkoutService = $checkoutService;
        $this->cartService = $cartService;
    }

    public function checkout(Request $request)
    {
        $request->validate(['cart_key' => 'nullable|uuid', 'payment_method' => 'nullable|in:cash,card,qr,bank']);
        $user = auth('sanctum')->user();
        $cartKey = $request->cart_key;

        $cart = $this->cartService->getOrCreateCart($user, $cartKey);

        $sale = $this->checkoutService->processCheckout($cart, [
            'payment_method'   => $request->payment_method ?? 'cash',
        ]);

        return response()->json([
            'message' => 'Order placed; payment is pending.',
            'sale'    => new SaleResource($sale)
        ]);
    }
}
