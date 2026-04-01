@extends('frontend.shop.layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">

    <h1 class="text-4xl font-bold text-gray-900 mb-10">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <!-- Shipping Details Form -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-2xl font-semibold mb-8">Shipping Details</h2>

                <form action="{{ route('shop.checkout.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" required
                                   class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" required
                                   class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" name="address" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" name="city" required
                                   class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input type="text" name="postal_code" required
                                   class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <input type="text" name="country" value="Cambodia" required
                                   class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Notes (Optional)</label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-5 rounded-3xl text-lg transition-all active:scale-[0.98]">
                        Proceed to Payment
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 sticky top-8">
                <h2 class="text-2xl font-semibold mb-6">Order Summary</h2>

                @if(!empty($cart))
                    <div class="space-y-6 mb-8">
                        @foreach($cart as $item)
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $item['image'] ?? 'https://picsum.photos/id/'.rand(100,400).'/80/80' }}"
                                         alt="{{ $item['name'] }}"
                                         class="w-12 h-12 object-cover rounded-xl">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                                        <p class="text-sm text-gray-500">Qty: {{ $item['quantity'] }}</p>
                                    </div>
                                </div>
                                <p class="font-semibold text-gray-900">
                                    ${{ number_format($item['selling_price'] * $item['quantity'], 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 pt-6 space-y-4">
                        <div class="flex justify-between text-lg">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-green-600 font-medium">Free</span>
                        </div>
                        <div class="border-t border-gray-200 pt-4 flex justify-between text-xl font-bold">
                            <span>Total</span>
                            <span class="text-indigo-600">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-10">Your cart is empty.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
