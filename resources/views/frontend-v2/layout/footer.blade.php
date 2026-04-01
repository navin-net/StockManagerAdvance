<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer id="footer">
    <div class="container-fluid px-4">
        <div class="row g-5">
            <div class="col-lg-3">
                <div class="footer-brand">{{ $shopDetail->name }}<span>'s</span></div>
                <div class="footer-tagline">Follow Your Idea</div>
                <p style="font-size:.82rem;color:rgba(255,255,255,.4);margin-top:1rem;line-height:1.8">
                    {{-- Curated luxury fashion from the world's most distinctive designers. Every piece tells a story. --}}
                {{ $shopDetail->description }}
                </p>
                <div class="social-icons mt-3">
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-pinterest"></i></a>
                    <a href="#"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <div class="footer-h">Shop</div>
                <ul class="footer-links">
                    <li><a href="#">New Arrivals</a></li>
                    <li><a href="#">Best Sellers</a></li>
                    <li><a href="#">Sale</a></li>
                    <li><a href="#">Collections</a></li>
                    <li><a href="#">Brands</a></li>
                    <li><a href="#">Gift Cards</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <div class="footer-h">Help</div>
                <ul class="footer-links">
                    <li><a href="#">Order Tracking</a></li>
                    <li><a href="#">Returns & Exchanges</a></li>
                    <li><a href="#">Shipping Info</a></li>
                    <li><a href="#">Size Guide</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <div class="footer-h">Company</div>
                <ul class="footer-links">
                    <li><a href="#">About {{ $shopDetail->name }}</a></li>
                    <li><a href="#">Sustainability</a></li>
                    <li><a href="#">Press</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Affiliate Program</a></li>
                    <li><a href="#">Investor Relations</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-3">
                <div class="footer-h">Visit a Store</div>
                <p style="font-size:.82rem;color:rgba(255,255,255,.4);line-height:1.8">
                    {{ $shopDetail->address }}<br>
                    Mon – Sat: {{ $shopDetail->open_shop_time }} – {{ $shopDetail->close_shop }}<br>
                    {{-- Sun: 12:00 – 18:00 --}}
                </p>
                <div class="footer-h mt-3">We Accept</div>
                <div class="d-flex gap-2 flex-wrap">
                    <span
                        style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);padding:4px 10px;font-size:.7rem;color:rgba(255,255,255,.5)">VISA</span>
                    <span
                        style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);padding:4px 10px;font-size:.7rem;color:rgba(255,255,255,.5)">MC</span>
                    <span
                        style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);padding:4px 10px;font-size:.7rem;color:rgba(255,255,255,.5)">AMEX</span>
                    <span
                        style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);padding:4px 10px;font-size:.7rem;color:rgba(255,255,255,.5)">PayPal</span>
                    <span
                        style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);padding:4px 10px;font-size:.7rem;color:rgba(255,255,255,.5)">Apple
                        Pay</span>
                </div>
            </div>
        </div>

        <hr class="footer-rule" />

        <div class="d-flex flex-wrap justify-content-between align-items-center footer-bottom gap-2">
            <span>&copy; {{ date('Y') }} {{ $shopDetail->name }}. All Rights Reserved.</span>
            <div class="d-flex gap-3">
                <a href="#" style="color:rgba(255,255,255,.3);text-decoration:none;font-size:.75rem">Privacy
                    Policy</a>
                <a href="#" style="color:rgba(255,255,255,.3);text-decoration:none;font-size:.75rem">Terms
                    of Service</a>
                <a href="#" style="color:rgba(255,255,255,.3);text-decoration:none;font-size:.75rem">Cookie
                    Preferences</a>
            </div>
        </div>
    </div>
</footer>

<!-- ============================================================
     CART SIDEBAR
     ============================================================ -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <h6>Your Bag <span
                style="color:var(--muted);font-size:.85rem;font-family:'DM Sans',sans-serif;font-weight:400">(3
                items)</span></h6>
        <button class="cart-close" onclick="closeCart()"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="cart-body">
        <!-- Cart Item 1 -->
        <div class="cart-item">
            <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=200&q=80" alt="" />
            <div style="flex:1">
                <div class="cart-item-name">Oversized Silk Blazer</div>
                <div style="font-size:.72rem;color:var(--muted);margin-bottom:4px">Éclat Paris · Size M</div>
                <div class="cart-item-price">$245.00</div>
                <div class="qty-control">
                    <button class="qty-btn">−</button>
                    <span class="qty-num">1</span>
                    <button class="qty-btn">+</button>
                    <button
                        style="background:none;border:none;color:var(--muted);font-size:.75rem;cursor:pointer;margin-left:auto">Remove</button>
                </div>
            </div>
        </div>
        <!-- Cart Item 2 -->
        <div class="cart-item">
            <img src="https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=200&q=80" alt="" />
            <div style="flex:1">
                <div class="cart-item-name">Minimalist Runner Sneakers</div>
                <div style="font-size:.72rem;color:var(--muted);margin-bottom:4px">Studio Muse · EU 42</div>
                <div class="cart-item-price">$126.00</div>
                <div class="qty-control">
                    <button class="qty-btn">−</button>
                    <span class="qty-num">1</span>
                    <button class="qty-btn">+</button>
                    <button
                        style="background:none;border:none;color:var(--muted);font-size:.75rem;cursor:pointer;margin-left:auto">Remove</button>
                </div>
            </div>
        </div>
        <!-- Cart Item 3 -->
        <div class="cart-item">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&q=80" alt="" />
            <div style="flex:1">
                <div class="cart-item-name">Heritage Chronograph Watch</div>
                <div style="font-size:.72rem;color:var(--muted);margin-bottom:4px">Voltaire Co.</div>
                <div class="cart-item-price">$650.00</div>
                <div class="qty-control">
                    <button class="qty-btn">−</button>
                    <span class="qty-num">1</span>
                    <button class="qty-btn">+</button>
                    <button class="btn-remove">Remove</button>
                </div>
            </div>
        </div>
    </div>
    <div class="cart-footer">
        <div style="font-size:.75rem;color:var(--muted);margin-bottom:8px"><i class="bi bi-tag me-1"
                style="color:var(--accent)"></i>15% pickup discount applied</div>
        <div class="cart-total">
            <span>Subtotal</span>
            <span>$1,021.00</span>
        </div>
        <a href="#" class="btn-checkout">Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i></a>
        <a href="#"
            style="display:block;text-align:center;font-size:.75rem;color:var(--muted);margin-top:10px;text-decoration:none;">
            <i class="bi bi-bag me-1"></i>View Full Cart
        </a>
    </div>
</div>

<!-- Scroll to Top -->
<button id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-chevron-up"></i>
</button>

<!-- Bootstrap 5.3 JS -->
<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>

<script>
    function changeLanguage(lang) {
        window.location.href = `/lang/${lang}`;
    }


    const accountDropdown = document.querySelector('.account-dropdown');
    const accountBtn = accountDropdown.querySelector('a');

    accountBtn.addEventListener('click', function (e) {
        e.preventDefault();
        accountDropdown.classList.toggle('active');
    });



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
    const scrollBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
        scrollBtn.style.display = window.scrollY > 400 ? 'flex' : 'none';
    });




    const toggle = document.getElementById('themeToggle');
    const icon = document.getElementById('themeIcon');
    const badge = document.getElementById('themeBadge');

    function updateIcon() {
        if (document.body.classList.contains('dark-mode')) {
            icon.classList.remove('bi-sun-fill');
            icon.classList.add('bi-moon-fill');
            //   badge.textContent = '🌙 Dark';
        } else {
            icon.classList.remove('bi-moon-fill');
            icon.classList.add('bi-sun-fill');
            //   badge.textContent = '☀️ Light';
        }
    }

    toggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateIcon();
    });

    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
        updateIcon();
    }

</script>
