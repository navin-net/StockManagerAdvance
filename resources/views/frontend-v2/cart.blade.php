@extends('frontend-v2.app')

@section('title', 'Cart')

@section('content')
    <div class="page-hero">
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Shopping Bag</li>
                </ol>
            </nav>
            <div class="page-hero-eyebrow">Your Selection</div>
            <h1 class="page-hero-title">Shopping <em>Bag</em></h1>
            <p style="color:rgba(255,255,255,.5); font-size:.85rem; margin-top:.5rem;">
                <span id="heroItemCount">0</span> items awaiting checkout
            </p>
        </div>
    </div>

    <!-- ── CART PAGE ── -->
    <div class="cart-page">
        <div class="container-fluid px-4">
            <div class="cart-layout" id="cartLayout">

                <!-- LEFT: Items -->
                <div class="cart-left">
                    <div class="cart-header-row">
                        <div>
                            <h2>Your Bag</h2>
                            <span class="cart-item-count" id="itemCountLabel">2 items</span>
                        </div>
                        <a href="{{ url('shop/products') }}" class="continue-link">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>

                    <!-- Column labels (desktop) -->
                    <div class="cart-col-labels">
                        <span>Product</span>
                        <span class="col-qty">Quantity</span>
                        <span class="col-total">Price</span>
                        <span></span>
                    </div>

                    <!-- Cart items list -->
                    <div id="cartItems"></div>

                    <!-- Empty cart state -->
                    <div class="empty-cart" id="emptyCart">
                        <div class="empty-icon"><i class="bi bi-bag-x"></i></div>
                        <h4>Your bag is empty</h4>
                        <p>Looks like you haven't added anything to your bag
                            yet.<br>Discover our curated collection.</p>
                        <a href="luxe-products.html" class="btn-shop-now"><i class="bi bi-arrow-right"></i> Browse
                            Collection</a>
                    </div>

                    <!-- Promo code (hidden when empty) -->
                    <div id="promoSection" style="margin-top: 0;">
                        <hr class="section-divider" />
                        <div class="section-sub-label">Promo Code</div>
                        <div class="promo-row">
                            <input type="text" class="promo-input" id="promoInput" placeholder="Enter promo code…" />
                            <button class="btn-apply-promo" onclick="applyPromo()">Apply</button>
                        </div>
                        <div class="promo-success" id="promoSuccess"><i class="bi bi-check-circle-fill"></i> Code <strong
                                id="promoApplied"></strong> applied — 15% off!</div>
                        <div class="promo-error" id="promoError"><i class="bi bi-x-circle-fill"></i> Invalid code. Try
                            <em>LUXE15</em>
                        </div>
                    </div>

                    <!-- Recently viewed -->
                    <div id="savedSection">
                        <hr class="section-divider" style="margin-top: 32px;" />
                        <div class="section-sub-label">Saved For Later</div>
                        <div class="row g-3" id="savedItems"></div>
                    </div>

                </div>

                <!-- RIGHT: Order summary -->
                <div class="cart-right">
                    <div class="summary-box">
                        <div class="summary-header">Order Summary</div>
                        <div class="summary-body">

                            <div class="summary-line">
                                <span class="label">Subtotal (<span id="itemCountLabel">2</span>)</span>
                                <span class="value" id="summarySubtotal">$0.00</span>
                            </div>
                            <div class="summary-line discount" id="discountRow" style="display:none">
                                <span class="label"><i class="bi bi-tag-fill me-1"></i>Promo
                                    (LUXE15)</span>
                                <span class="value" id="discountAmount">–$0.00</span>
                            </div>
                            <div class="summary-line shipping">
                                <span class="label">Shipping</span>
                                <span class="value" id="shippingVal">Free</span>
                            </div>
                            <div class="summary-line" id="taxRow">
                                <span class="label">Estimated Tax (8%)</span>
                                <span class="value" id="taxVal">$0.00</span>
                            </div>

                            <hr class="summary-divider" />

                            <div class="summary-total">
                                <span class="total-label">Total</span>
                                <div style="text-align:right">
                                    <span class="total-value" id="totalVal">$0.00</span>
                                    <span class="total-tax">Incl. taxes &amp; duties</span>
                                </div>
                            </div>

                            <a href="#" class="btn-checkout" onclick="handleCheckout(event)">
                                <i class="bi bi-lock-fill"></i> Secure Checkout
                            </a>
                            <button class="btn-paypal" onclick="showToast('Redirecting to PayPal…', 'bi-paypal')">
                                <span style="font-size:.7rem;font-weight:400;margin-right:2px;">Pay
                                    with</span>
                                <span style="font-size:1rem;">Pay</span><span
                                    style="color:#009CDE;font-size:1rem;">Pal</span>
                            </button>

                            <div class="secure-badges">
                                <i class="bi bi-shield-lock-fill"></i> SSL Encrypted &amp;
                                Secure Checkout
                            </div>

                            <div class="payment-icons">
                                <span class="pay-icon">VISA</span>
                                <span class="pay-icon">Mastercard</span>
                                <span class="pay-icon">Amex</span>
                                <span class="pay-icon">Apple Pay</span>
                                <span class="pay-icon">G Pay</span>
                            </div>

                            <div class="delivery-est">
                                <i class="bi bi-calendar3"></i>
                                <div class="delivery-est-text">
                                    <strong>Estimated delivery:</strong> Order within <span id="deliveryCountdown"
                                        style="color:var(--accent);font-weight:600;"></span> for
                                    dispatch today.
                                </div>
                            </div>

                            <div class="trust-strip">
                                <div class="trust-item">
                                    <i class="bi bi-arrow-return-left"></i>
                                    <span>Free Returns</span>
                                </div>
                                <div class="trust-item">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Authentic Only</span>
                                </div>
                                <div class="trust-item">
                                    <i class="bi bi-headset"></i>
                                    <span>24/7 Support</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>



                <div class="upsell-section" id="upsellSection">
                    <div class="container-fluid px-4">
                        <div class="section-sub-label">Complete Your Look</div>
                        <h2 class="upsell-title">You May <strong>Also Like</strong></h2>
                        <div class="accent-rule"></div>
                        <div class="row g-3" id="upsellProducts"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
