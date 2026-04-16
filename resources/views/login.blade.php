<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <h1>Checkout</h1>

    <div id="cart-summary">
        <p>Loading cart...</p>
    </div>

    <h2>Payment Information</h2>
    <label>Payment Method:</label><br>
    <select id="payment_method">
        <option value="cash">Cash</option>
        <option value="card">Card</option>
        <option value="bank">Bank Transfer</option>
    </select><br><br>

    <button onclick="completeCheckout()">Complete Checkout</button>

    <script>
        let cartKey = localStorage.getItem('cart_key');

        // Load Cart Summary before checkout
async function loadCartSummary() {
    try {
        const response = await fetch(`/api/cart?cart_key=${cartKey}`);
        const data = await response.json();

        // 1. Create a variable to hold the HTML for all the products
        let itemsHtml = "";

        // 2. Loop through the items and append them to the itemsHtml string
        data.items.forEach(item => {
            console.log(item.product.name);
            itemsHtml += `
                <div class="cart-item">
                    <p><strong>Product:</strong> ${item.product.name} (x${item.quantity})</p>
                </div>
            `;
        });
        console.log(data);

        // 3. Build the final HTML combining the summary and the items list
        let finalHtml = `
            <h3>Cart Summary</h3>
            <div id="items-list">
                ${itemsHtml}
            </div>
            <hr>
            <p><strong>Total Items:</strong> ${data.items_count || 0}</p>
            <p><strong>Total Amount:</strong> $${parseFloat(data.total_price || 0).toFixed(2)}</p>
        `;

        // 4. Update the page
        document.getElementById('cart-summary').innerHTML = finalHtml;

    } catch (error) {
        console.error(error);
        document.getElementById('cart-summary').innerHTML = '<p style="color:red;">Error loading cart</p>';
    }
}


        // Call Checkout API
        async function completeCheckout() {
            if (!confirm('Are you sure you want to complete this purchase?')) {
                return;
            }

            const paymentMethod = document.getElementById('payment_method').value;

            try {
                const response = await fetch('/api/shop/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cart_key: cartKey,
                        payment_method: paymentMethod
                        // You can add more fields here:
                        // warehouse_id: 1,
                        // customer_id: 5,
                        // cash_register_id: 2
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    alert(`✅ Success!\nSale Reference: ${result.sale.reference}\nTotal: $${result.sale.total_amount}`);
                    // Clear localStorage cart key (optional)
                    // localStorage.removeItem('cart_key');
                    window.location.href = '/thank-you';   // Redirect to success page
                } else {
                    alert('Error: ' + (result.message || 'Checkout failed'));
                }
            } catch (error) {
                console.error(error);
                alert('Network error. Please try again.');
            }
        }

        // Load cart when page opens
        window.onload = loadCartSummary;
    </script>

</body>
</html>
