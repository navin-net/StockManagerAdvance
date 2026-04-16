document.getElementById('cartToggle').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('cartSidebar').classList.add('open');
    document.getElementById('cartOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
});
function closeCart() {
    document.getElementById('cartSidebar').classList.remove('open');
    document.getElementById('cartOverlay').classList.remove('show');
    document.body.style.overflow = '';
}


// ================== CART KEY ==================
let cartKey = localStorage.getItem('cart_key');

if (!cartKey) {
    cartKey = 'guest-' + Math.random().toString(36).substr(2, 9) + Date.now().toString(36);
    localStorage.setItem('cart_key', cartKey);
}

const API_BASE = '/api/cart';
const ASSET_URL = '/storage';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';


// ================== NORMALIZE ==================
function normalizeCart(data) {
    const items = Array.isArray(data)
        ? data
        : data.items || data.data?.items || [];

    return {
        items,
        summary: data.summary || {
            subtotal: data.subtotal || 0,
            tax: data.tax || 0,
            total: data.total || 0,
            count: items.length
        }
    };
}


// ================== API ==================
async function apiCall(url, method = 'GET', body = null) {
    const res = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: body ? JSON.stringify(body) : null
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok) throw new Error(data.message || `HTTP ${res.status}`);

    return data;
}

// ================== REFRESH CART ==================
async function refreshCart() {
    try {
        const data = await apiCall(`${API_BASE}?cart_key=${cartKey}`);
        const cart = normalizeCart(data);
        items = data.items;
        updateCartCount(data.items_count ?? cart.items.length);
        renderCart(cart);
        calculateCartTotals(cart.items);

    } catch (e) {
        console.error('Cart error:', e);
    }
}


// ================== ADD TO CART ==================
async function addToCart(productId, btn, stockQuantity) {

    if (stockQuantity <= 0) {
        btn.disabled = true;
        btn.classList.replace('btn-primary', 'btn-secondary');
        btn.innerHTML = 'Out of Stock';
        return;
    }

    const qtyInput = document.getElementById(`qty-${productId}`);
    const quantity = parseInt(qtyInput?.value || 1) || 1;

    // ✅ Validate qty vs stock before API call
    if (quantity > stockQuantity) {
        alert(`Only ${stockQuantity} item(s) available in stock.`);
        if (qtyInput) qtyInput.value = stockQuantity;
        return;
    }

    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check-circle"></i> Added!';
    btn.classList.add('added');

    setTimeout(() => {
        if (!btn.disabled) {
            btn.innerHTML = orig;
            btn.classList.remove('added');
        }
    }, 1800);

    try {
        const data = await apiCall(`${API_BASE}/items`, 'POST', {
            product_id: productId,
            quantity,
            cart_key: cartKey
        });

        // ✅ Use stock_quantity field
        if (data.stock_quantity <= 0) {
            btn.disabled = true;
            btn.innerHTML = 'Out of Stock';
        }

        refreshCart();

    } catch (e) {
        btn.innerHTML = orig;
        btn.classList.remove('added');
        alert(e.message);
    }
}

// ================== COUNT ==================
function updateCartCount(count) {
    document.querySelectorAll('#cart-count, .cart-count-display, #itemCountLabel, #heroItemCount')
        .forEach(el => {
            if (el.classList.contains('cart-count-display')) {
                el.textContent = `(${count} items)`;
            } else if (el.id === 'itemCountLabel') {
                el.textContent = `${count} items`;
            } else if (el.id === 'heroItemCount') {
                el.textContent = `${count}`;
            } else {
                el.textContent = count;
                el.style.display = count > 0 ? 'flex' : 'none';
            }
        });
}

// ================== UPDATE ITEM ==================
async function updateItem(id, qty, stock = 99) {
    if (qty < 1) return removeItem(id);

    // ✅ Check stock limit before API call
    if (qty > stock) {
        alert(`Only ${stock} item(s) available in stock.`);
        return;
    }

    try {
        await apiCall(`${API_BASE}/items/${id}`, 'PUT', {
            quantity: qty,
            cart_key: cartKey
        });
    } catch (err) {
        alert(err.message || 'Out of stock');
    }

    refreshCart();
}


// ================== SET QTY ==================
function setQty(id, qty, stock = 99) {
    qty = parseInt(qty);
    if (isNaN(qty) || qty < 1) qty = 1;

    if (qty > stock) {
        const isDark = document.body.classList.contains('dark-mode');

        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            icon: 'warning',
            title: `Limit: ${stock} items`,
            background: isDark ? '#333' : '#fff',
            color: isDark ? '#fff' : '#000'
        });

        qty = stock;
    }
    updateItem(id, qty, stock);
}


// ================== REMOVE ==================
async function removeItem(id) {
    const isDark = document.body.classList.contains('dark-mode');

    // 1. Ask for confirmation
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "Remove this item from your cart?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: isDark ? '#d33' : '#e03e3e',
        cancelButtonColor: isDark ? '#444' : '#aaa',
        confirmButtonText: 'Yes, remove it!',
        background: isDark ? '#212529' : '#fff',
        color: isDark ? '#f8f9fa' : '#545454'
    });

    // 2. If user confirmed, proceed with deletion
    if (result.isConfirmed) {
        try {
            await apiCall(`${API_BASE}/items/${id}?cart_key=${cartKey}`, 'DELETE');

            // 3. Show success toast
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Item removed',
                showConfirmButton: false,
                timer: 2000,
                background: isDark ? '#212529' : '#fff',
                color: isDark ? '#f8f9fa' : '#545454'
            });

            refreshCart();
        } catch (error) {
            // 4. Handle errors
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                background: isDark ? '#212529' : '#fff',
                color: isDark ? '#f8f9fa' : '#545454'
            });
        }
    }
}

// ================== RENDER ==================
function renderCart(cart) {
    const items = cart.items;

    // ===== MINI CART =====
    const mini = document.querySelector('.cart-body');

    if (mini) {
        mini.innerHTML = !items.length
            ? '<div class="text-center p-4">Your cart is empty</div>'
            : items.map(item => {
                const qty = item.quantity ?? 1;
                const p = item.product || {};

                // ✅ Use stock_quantity
                const stock = p.stock_quantity ?? 0;

                return `
                        <div class="cart-item" style="display:flex; gap:10px; margin-bottom:15px; align-items:center;">
                            <img src="${p.image ? ASSET_URL + '/' + p.image : '/noimage.png'}"
                                alt="${p.name}"
                                style="width:60px; height:60px; object-fit:cover; border-radius:4px;" />
                            <div style="flex:1">
                                <div class="cart-item-name" style="font-weight:bold; font-size:0.9rem;">${p.name}</div>
                                <div class="cart-item-price" style="color:var(--primary);">$${item.price}</div>

                                <div class="qty-control" style="display:flex; align-items:center; gap:8px; margin-top:5px;">
                                    <button class="qty-btn"
                                        onclick="updateItem(${item.id}, ${qty - 1}, ${stock})"
                                        ${qty <= 1 ? 'disabled' : ''}>−</button>

                                    <span class="qty-num">${qty}</span>

                                    <button class="qty-btn"
                                        onclick="updateItem(${item.id}, ${qty + 1}, ${stock})"
                                        ${qty >= stock ? 'disabled' : ''}>+</button>

                                    <button onclick="removeItem(${item.id})"
                                        style="background:none; border:none; color:red; font-size:.75rem; cursor:pointer; margin-left:auto;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                ${qty >= stock ? `<small style="color:red;">Only ${stock} left</small>` : ''}
                            </div>
                        </div>`;
            }).join('');

        const subtotal = items.reduce((sum, item) => {
            return sum + ((parseFloat(item.price) || 0) * (item.quantity ?? 1));
        }, 0);

        const totalEl = document.getElementById('cart-total');
        if (totalEl) totalEl.textContent = '$' + subtotal.toLocaleString();
    }


    // ===== FULL CART PAGE =====
    const list = document.getElementById('cartItems');

    // console.log(list);

    if (list) {
        const empty = document.getElementById('emptyCart');
        const sections = [
            document.querySelector('.cart-col-labels'),
            document.getElementById('promoSection'),
            document.querySelector('.cart-right')
        ];

        if (!items.length) {
            list.innerHTML = '';
            if (empty) empty.style.display = 'block';
            sections.forEach(function (s) {
                if (s) { // if the element exists
                    s.style.display = 'none';
                }
            });
            return;
        }

        if (empty) empty.style.display = 'none';
        sections.forEach(s => s && (s.style.display = ''));

        list.innerHTML = items.map(item => {
            const qty = item.quantity ?? 1;
            const p = item.product || {};

            // ✅ Use stock_quantity
            const stock = p.stock_quantity ?? 0;
            const imageUrl = p.image ? `${ASSET_URL}/${p.image}` : `/noimage.png`;

            return `
                    <div class="cart-item" id="item-${item.id}">

                        <!-- Product Info -->
                        <div class="cart-item-info">
                            <div class="cart-item-img">
                                <img src="${imageUrl}" alt="${p.name}"
                                    onerror="this.src='https://placehold.co/300x380/E8E0D5/7A6E65?text=${encodeURIComponent(p.name)}'" />
                            </div>
                            <div class="cart-item-details">
                                <div class="cart-item-brand">${item.brand ?? ''}</div>
                                <div class="cart-item-name">${p.name}</div>

                                ${stock <= 5 && stock > 0 ? `<small style="color:orange;">Only ${stock} left</small>` : ''}
                                ${stock <= 0 ? `<small style="color:red;">Out of stock</small>` : ''}

                                <div class="item-actions-row">
<button class="item-action-btn" onclick='saveForLater(${JSON.stringify(item).replace(/'/g, "&apos;")})'>
    <i class="bi bi-bookmark"></i> Save for later
</button>
                                    <button class="item-action-btn remove" onclick="removeItem(${item.id})">
                                        <i class="bi bi-trash3"></i> Remove
                                    </button>
                                </div>

                                <!-- Mobile qty + price -->
                                <div class="mobile-cart-bottom d-flex d-md-none" style="margin-top:10px; gap:12px; align-items:center; flex-wrap:wrap;">
                                    <div class="qty-control">
                                        <button class="qty-btn"
                                            onclick="updateItem(${item.id}, ${qty - 1}, ${stock})"
                                            ${qty <= 1 ? 'disabled' : ''}>
                                            <i class="bi bi-dash"></i>
                                        </button>

                                        <input class="qty-num" type="number"
                                            value="${qty}" min="1" max="${stock}"
                                            onchange="setQty(${item.id}, this.value, ${stock})" />

                                        <button class="qty-btn"
                                            onclick="updateItem(${item.id}, ${qty + 1}, ${stock})"
                                            ${qty >= stock ? 'disabled' : ''}>
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <div class="item-price-wrap">$${(item.price * qty).toFixed(2)}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Qty (desktop) -->
                        <div class="qty-wrap d-none d-md-flex">
                            <div class="qty-control">
                                <button class="qty-btn"
                                    onclick="updateItem(${item.id}, ${qty - 1}, ${stock})"
                                    ${qty <= 1 ? 'disabled' : ''}>
                                    <i class="bi bi-dash"></i>
                                </button>

                                <input class="qty-num" type="number"
                                    value="${qty}" min="1" max="${stock}"
                                    onchange="setQty(${item.id}, this.value, ${stock})" />

                                <button class="qty-btn"
                                    onclick="updateItem(${item.id}, ${qty + 1}, ${stock})"
                                    ${qty >= stock ? 'disabled' : ''}>
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Price (desktop) -->
                        <div class="item-price-wrap d-none d-md-block">
                            $${(item.price * qty).toFixed(2)}
                        </div>

                        <!-- Remove (desktop) -->
                        <div class="remove-wrap d-none d-md-flex">
                            <button class="remove-btn" onclick="removeItem(${item.id})" title="Remove item">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>`;
        }).join('');
        // ✅ Update mini cart subtotal
        const subtotal = items.reduce((sum, item) => {
            return sum + ((parseFloat(item.price) || 0) * (item.quantity ?? 1));
        }, 0);

        const summarySubtotal = document.getElementById('summarySubtotal');
        if (summarySubtotal) summarySubtotal.textContent = '$' + subtotal.toLocaleString();


        const totalValElement = document.getElementById('totalVal');
        const finalTotal = subtotal + 3;
        if (totalValElement) {
            totalValElement.textContent = '$' + finalTotal.toLocaleString();
        }

        const taxValElement = document.getElementById('taxVal');

        const taxRate = 0.03;

        const calculatedTax = subtotal * taxRate;
        if (taxValElement) {
            taxValElement.textContent = '$' + calculatedTax.toLocaleString(undefined, {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
        }

        renderSavedItems();

        updateCartSummary(items);
    }


    const product_review = document.getElementById('product-review');

    if (product_review) {

        product_review.innerHTML = !items.length
            ? '<div class="text-center p-4">Your cart is empty</div>'
            : items.map(item => {
                const qty = item.quantity ?? 1;
                const p = item.product || {};

                // ✅ Use stock_quantity
                const stock = p.stock_quantity ?? 0;

                return `


                            <!-- Items -->
                            <div class="review-item">
                                <img class="review-thumb"
                                    src="${p.image ? ASSET_URL + '/' + p.image : '/noimage.png'}" alt="${p.name}"/>
                                <div>
                                    <div class="review-name">${p.name}</div>
                                    <div class="review-meta">Éclat Paris · Size M · Ivory · Qty 1</div>
                                </div>
                                <div class="review-price">$245.00</div>
                            </div>

                `;
            }).join('');
    }

}


// Function to handle Save for Later
async function saveForLater(itemToSave) { // Receive the item directly
    const isDark = document.body.classList.contains('dark-mode');
    const id = itemToSave.id;

    try {
        // 1. Remove from API
        await apiCall(`${API_BASE}/items/${id}?cart_key=${cartKey}`, 'DELETE');

        // 2. Add to Local Storage
        const savedStore = JSON.parse(localStorage.getItem('savedItems')) || [];

        // Prevent duplicates
        if (!savedStore.find(s => s.id === id)) {
            savedStore.push(itemToSave);
            localStorage.setItem('savedItems', JSON.stringify(savedStore));
        }

        // 3. Success Alert
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Saved for later!',
            showConfirmButton: false,
            timer: 2000,
            background: isDark ? '#212529' : '#fff',
            color: isDark ? '#f8f9fa' : '#545454'
        });

        refreshCart();
        renderSavedItems();

    } catch (error) {
        console.error("Save error:", error);
    }
}

// Function to render the Saved items section
function renderSavedItems() {
    const container = document.getElementById('savedItems');
    const section = document.getElementById('savedSection');
    if (!container) return;

    const savedStore = JSON.parse(localStorage.getItem('savedItems')) || [];
    const isDark = document.body.classList.contains('dark-mode');

    // Toggle section visibility
    if (savedStore.length === 0) {
        section.style.display = 'none';
        return;
    }
    section.style.display = 'block';

    container.innerHTML = savedStore.map(item => {
        const p = item.product || {};
        // const imageUrl = p.image ? `${ASSET_URL}/${p.image}` : `/noimage.png`;
        const imgPath = p.image ? `${ASSET_URL}/${p.image}` : `/noimage.png`;
        const brand = item.brand || p.brand || '';
        return `
                <div class="col-6 col-md-3">
                                <div class="mini-product">
                                    <div class="mini-product-img">
                                        <img src="${imgPath}" alt="${p.name}"
                                            onerror="this.src='https://placehold.co/200x260/E8E0D5/7A6E65?text=${encodeURIComponent(p.name)}'" />
                                        <button class="mini-remove-btn" onclick="deleteSaved(${item.id})" title="Remove">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div>
                                        <div class="mini-product-brand">${brand}</div>
                                        <div class="mini-product-name text-truncate">${p.name}</div>
                                        <div class="mini-product-price">$${parseFloat(item.price).toLocaleString()}</div>
                                        <button class="btn-mini-add" onclick="moveToCart(${item.id})">Move to Bag</button>
                                    </div>
                                </div>
                            </div>`;
    }).join('');
}


async function moveToCart(id) {
    const isDark = document.body.classList.contains('dark-mode');

    // 1. Get the items currently in the Saved list
    let savedStore = JSON.parse(localStorage.getItem('savedItems')) || [];

    // 2. Find the specific item the user clicked
    const itemToMove = savedStore.find(s => s.id === id);

    if (itemToMove) {
        try {
            // 3. API Call: Add the item back to the actual cart database
            // Note: Adjust the endpoint/body based on your specific API
            await apiCall(`${API_BASE}/items`, 'POST', {
                product_id: itemToMove.product.id,
                quantity: 1 // Default to 1, or use itemToMove.quantity
            });

            // 4. Remove from Local Storage (Saved list)
            savedStore = savedStore.filter(s => s.id !== id);
            localStorage.setItem('savedItems', JSON.stringify(savedStore));

            // 5. Show Success Toast
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Moved back to cart',
                showConfirmButton: false,
                timer: 1500,
                background: isDark ? '#212529' : '#fff',
                color: isDark ? '#f8f9fa' : '#545454'
            });

            // 6. Refresh the UI
            refreshCart();      // Reloads the main cart list
            renderSavedItems(); // Reloads the saved section

        } catch (error) {
            console.error("Move to cart failed:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to add item back to cart.',
                background: isDark ? '#212529' : '#fff',
                color: isDark ? '#f8f9fa' : '#545454'
            });
        }
    }
}

// ================== CART SUMMARY (FULL PAGE) ==================
function updateCartSummary(items) {
    let subtotal = 0;

    items.forEach(item => {
        subtotal += (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0);
    });

    const tax = subtotal * 0.1;
    const total = subtotal + tax;

    const set = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.textContent = '$' + val.toFixed(2);
    };

    set('summary-subtotal', subtotal);
    set('summary-tax', tax);
    set('summary-total', total);
}


// ================== CALCULATE TOTALS ==================
function calculateCartTotals(items) {
    let subtotal = 0;

    items.forEach(item => {
        subtotal += (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0);
    });

    const tax = subtotal * 0.1;
    const total = subtotal + tax;

    return { count: items.length, subtotal, tax, total };
}


// ================== CUSTOM CONFIRM ==================
function customConfirm(message) {
    return new Promise((resolve) => {
        const modal = document.getElementById('myModal');
        const confirmBtn = document.getElementById('modalConfirm');
        const cancelBtn = document.getElementById('modalCancel');

        modal.classList.add('is-active');

        const cleanup = (value) => {
            modal.classList.remove('is-active');
            confirmBtn.removeEventListener('click', onConfirm);
            cancelBtn.removeEventListener('click', onCancel);
            resolve(value);
        };

        const onConfirm = () => cleanup(true);
        const onCancel = () => cleanup(false);

        confirmBtn.addEventListener('click', onConfirm);
        cancelBtn.addEventListener('click', onCancel);
    });
}



// ================== INIT ==================
document.addEventListener('DOMContentLoaded', refreshCart);

