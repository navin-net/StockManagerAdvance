@extends('frontend.shop.layouts.app')

@section('title', $product->name ?? __('messages.products_detail'))

@section('content')

{{-- <a href="{{ route('shop.cart') }}" class="relative">
    Cart
    @if($cartCount > 0)
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
            {{ $cartCount }}
        </span>
    @endif
</a> --}}

<div class="max-w-7xl mx-auto px-6 py-12">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">

        <!-- Product Image -->
        <div class="flex justify-center">
            <div class="relative">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('noimage.png') }}"
                     alt="{{ $product->name ?? 'Product' }}"
                     class="w-full max-w-lg lg:max-w-none rounded-3xl shadow-xl object-cover aspect-square">

                @if($product->is_new ?? false)
                    <span class="absolute top-6 left-6 bg-green-500 text-white text-sm font-medium px-4 py-2 rounded-2xl">
                        New Arrival
                    </span>
                @endif
            </div>
        </div>

        <!-- Product Information -->
        <div class="space-y-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 leading-tight">
                    {{ $product->name ?? 'Product Name' }}
                </h1>
                <p class="text-gray-500 mt-2 text-lg">
                    {{ $product->category->name ?? 'Category' }}
                </p>
            </div>

            <!-- Price -->
            <div class="flex items-baseline gap-3">
                <span class="text-5xl font-bold text-indigo-600">
                    ${{ number_format($product->price ?? 0, 2) }}
                </span>
                @if($product->old_price ?? false)
                    <span class="text-2xl text-gray-400 line-through">
                        ${{ number_format($product->old_price, 2) }}
                    </span>
                @endif
            </div>

            <!-- Description -->
            <div class="prose prose-gray max-w-none">
                <p class="text-lg text-gray-600 leading-relaxed">
                    {{ $product->description ?? 'Product description goes here. It\'s a great product with excellent quality and performance.' }}
                </p>
            </div>

            <!-- Add to Cart Section -->
            <div class="pt-6 border-t">
                <form action="{{ route('shop.cart.add', $product) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <div class="flex items-center gap-4">
                            <button type="button"
                                    onclick="decreaseQty()"
                                    class="w-12 h-12 flex items-center justify-center border border-gray-300 rounded-2xl text-2xl hover:bg-gray-100 transition-colors">-</button>

                            <input type="number" id="quantity" name="quantity" value="1" min="1"
                                   class="w-20 text-center border border-gray-300 rounded-2xl py-3 text-lg focus:outline-none focus:border-indigo-500">

                            <button type="button"
                                    onclick="increaseQty()"
                                    class="w-12 h-12 flex items-center justify-center border border-gray-300 rounded-2xl text-2xl hover:bg-gray-100 transition-colors">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-4 rounded-3xl text-lg transition-all active:scale-95">
                            Add to Cart
                        </button>

                        <button type="button"
                                onclick="addToWishlist()"
                                class="flex-1 border-2 border-gray-300 hover:border-gray-400 font-semibold py-4 rounded-3xl text-lg transition-all">
                            ❤️ Wishlist
                        </button>
                    </div>
                </form>
            </div>

            <!-- Extra Info -->
            <div class="grid grid-cols-2 gap-6 text-sm">
                <div class="flex items-center gap-3">
                    <span class="text-green-500">✓</span>
                    <span class="text-gray-600">Free Shipping</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-green-500">✓</span>
                    <span class="text-gray-600">30 Days Return</span>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function increaseQty() {
        let qty = document.getElementById('quantity');
        qty.value = parseInt(qty.value) + 1;
    }

    function decreaseQty() {
        let qty = document.getElementById('quantity');
        if (parseInt(qty.value) > 1) {
            qty.value = parseInt(qty.value) - 1;
        }
    }

    function addToWishlist() {
        alert('Added to wishlist! ❤️');
        // You can implement real wishlist logic later
    }
</script>
@endsection
