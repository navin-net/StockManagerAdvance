@extends('frontend.shop.layouts.app')

@section('title', 'Your Cart')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">

    <h1 class="text-4xl font-bold text-gray-900 mb-10">Your Cart</h1>

    @if(empty($cart))
        <!-- Empty Cart -->
        <div class="text-center py-20">
            <div class="text-6xl mb-6">🛒</div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-3">Your cart is empty</h2>
            <p class="text-gray-500 mb-8">Looks like you haven't added anything yet.</p>
            <a href="{{ route('shop.products') }}"
               class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-2xl font-semibold hover:bg-indigo-700 transition-colors">
                Start Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!-- Cart Items -->
            <div class="lg:col-span-8">
                <div class="space-y-8">
                    @foreach($cart as $id => $item)
                        <div class="flex gap-6 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">

                            <!-- Product Image -->
                            <div class="w-32 h-32 flex-shrink-0">
<img src="{{ asset('storage/' . $item['image']) }}"
     alt="{{ $item['name'] }}"
     class="w-full h-full object-cover rounded-2xl">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 flex flex-col">
                                <h3 class="font-semibold text-xl text-gray-900">{{ $item['name'] }}</h3>

                                <div class="mt-auto flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600">
                                            Quantity:
                                            <span class="font-medium">{{ $item['quantity'] }}</span>
                                        </p>
                                        <p class="text-2xl font-bold text-indigo-600 mt-1">
                                            ${{ number_format($item['selling_price'] * $item['quantity'], 2) }}
                                        </p>
                                    </div>

                                    <!-- Remove Button -->
                                    <form action="{{ route('shop.cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Remove this item from cart?')"
                                                class="text-red-600 hover:text-red-700 font-medium flex items-center gap-2">
                                            <span>Remove</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="lg:col-span-4">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 sticky top-8">
                    <h2 class="text-2xl font-semibold mb-6">Cart Summary</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between text-lg">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-green-600 font-medium">Free</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 my-6"></div>

                    <div class="flex justify-between text-2xl font-bold mb-8">
                        <span>Total</span>
                        <span class="text-indigo-600">${{ number_format($total, 2) }}</span>
                    </div>

                    <a href="{{ route('shop.checkout') }}"
                       class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center font-semibold py-4 rounded-3xl text-lg transition-all">
                        Proceed to Checkout
                    </a>

                    <a href="{{ route('shop.products') }}"
                       class="block text-center text-gray-600 hover:text-gray-800 mt-6 font-medium">
                        ← Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
