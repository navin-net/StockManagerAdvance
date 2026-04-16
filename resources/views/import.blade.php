<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <h1>Product List</h1>

    <div id="products-list">
        @foreach($products as $product)
            <div style="border:1px solid #ccc; margin:10px; padding:10px;">
                <h3>{{ $product->name }} ({{ $product->code }})</h3>
                <p>Price: ${{ number_format($product->selling_price, 2) }}</p>
                <p>Stock: {{ $product->stock_quantity }}</p>

                <input type="number"
                       id="qty-{{ $product->id }}"
                       value="1"
                       min="1"
                       max="{{ $product->stock_quantity }}"
                       style="width:60px;">

                <button onclick="addToCart({{ $product->id }})">Add to Cart</button>
            </div>
        @endforeach
    </div>

    <hr>
    <button onclick="viewCart()">View My Cart</button>

    <div id="cart-section" style="margin-top:30px; display:none;">
        <h2>Your Cart</h2>
        <div id="cart-items"></div>
        <p><strong>Total: $<span id="cart-total">0</span></strong></p>
        <button onclick="clearCart()">Clear Cart</button>
    </div>

    <script>
        // Generate or get persistent cart_key for guests
        let cartKey = localStorage.getItem('cart_key');
        if (!cartKey) {
            // Better fallback for older browsers
            cartKey = 'guest-' + Math.random().toString(36).substring(2, 15) +
                      Math.random().toString(36).substring(2, 15);
            localStorage.setItem('cart_key', cartKey);
        }

        const API_BASE = '/api/cart';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Add product to cart
        async function addToCart(productId) {
            const qtyInput = document.getElementById(`qty-${productId}`);
            const quantity = parseInt(qtyInput ? qtyInput.value : 1) || 1;

            try {
                const response = await fetch(`${API_BASE}/items`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        cart_key: cartKey
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.message || 'Product added to cart successfully!');
                    // Optional: auto refresh cart if it's already open
                    if (document.getElementById('cart-section').style.display !== 'none') {
                        viewCart();
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to add product'));
                }
            } catch (error) {
                console.error(error);
                alert('Network error. Please try again.');
            }
        }

        // View / Refresh Cart
        async function viewCart() {
            const section = document.getElementById('cart-section');
            section.style.display = 'block';

            try {
                const response = await fetch(`${API_BASE}?cart_key=${encodeURIComponent(cartKey)}`);
                const data = await response.json();

                if (response.ok) {
                    renderCart(data);
                } else {
                    alert('Failed to load cart');
                }
            } catch (error) {
                console.error(error);
                alert('Network error while loading cart');
            }
        }

        // Render cart items
        function renderCart(cartData) {
            const container = document.getElementById('cart-items');
            container.innerHTML = '';

            if (!cartData.items || cartData.items.length === 0) {
                container.innerHTML = '<p>Cart is empty</p>';
                document.getElementById('cart-total').textContent = '0';
                return;
            }

            let html = '';
            cartData.items.forEach(item => {
                html += `
                    <div style="border:1px solid #ddd; margin:8px; padding:8px;">
                        <strong>${item.product.name}</strong> - $${item.price} × ${item.quantity}
                        <br>
                        Subtotal: $${item.subtotal}

                        <button onclick="updateCartItem(${item.id}, ${item.quantity + 1})" style="margin-left:10px;">+</button>
                        <button onclick="updateCartItem(${item.id}, ${item.quantity - 1})">-</button>
                        <button onclick="removeCartItem(${item.id})" style="margin-left:10px;">Remove</button>
                    </div>
                `;
            });

            container.innerHTML = html;
            document.getElementById('cart-total').textContent = cartData.total_price || 0;
        }

        // Update quantity
        async function updateCartItem(cartItemId, newQuantity) {
            if (newQuantity < 1) return;

            try {
                const response = await fetch(`${API_BASE}/items/${cartItemId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ quantity: newQuantity })
                });

                if (response.ok) {
                    viewCart(); // refresh
                } else {
                    alert('Failed to update quantity');
                }
            } catch (error) {
                console.error(error);
                alert('Network error');
            }
        }

        // Remove single item
        async function removeCartItem(cartItemId) {
            if (!confirm('Remove this item from cart?')) return;

            try {
                const response = await fetch(`${API_BASE}/items/${cartItemId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });

                if (response.ok) {
                    viewCart();
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Clear entire cart
        async function clearCart() {
            if (!confirm('Clear all items from cart?')) return;

            try {
                const response = await fetch(`${API_BASE}?cart_key=${encodeURIComponent(cartKey)}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });

                if (response.ok) {
                    alert('Cart cleared successfully');
                    viewCart();
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>

</body>
</html>
