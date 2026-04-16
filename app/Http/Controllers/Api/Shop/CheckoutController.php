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
        $user = auth('sanctum')->user();
        $cartKey = $request->cart_key;

        $cart = $this->cartService->getOrCreateCart($user, $cartKey);

        $sale = $this->checkoutService->processCheckout($cart, [
            'customer_id'      => $request->customer_id,
            'cash_register_id' => $request->cash_register_id,
            'payment_method'   => $request->payment_method ?? 'cash',
        ]);

        return response()->json([
            'message' => 'Checkout completed successfully!',
            'sale'    => new SaleResource($sale)
        ]);
    }
}
