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
                    {{-- Curated luxury fashion from the world's most distinctive designers. Every piece tells a story.
                    --}}
                    {{ $shopDetail->description }}
                </p>
                <div class="social-icons mt-3">
                    <a href="{{ $shopDetail->instagram }}"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    {{-- <a href="#"><i class="bi bi-pinterest"></i></a> --}}
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
        <h6>Your Bag <span class="cart-count-display"
                style="color:var(--muted);font-size:.85rem;font-family:'DM Sans',sans-serif;font-weight:400">(3
                items)</span></h6>
        <button class="cart-close" onclick="closeCart()"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="cart-body">
        <!-- Cart Item 1 -->
        <div id="cart-item"></div>





    </div>
    <div class="cart-footer">
        <div style="font-size:.75rem;color:var(--muted);margin-bottom:8px"><i class="bi bi-tag me-1"
                style="color:var(--accent)"></i>15% pickup discount applied</div>
        <div class="cart-total">
            <span>Subtotal</span>
            <span id="cart-total">$1,021.00</span>
        </div>
        <a href="{{ route('shop.checkout') }}" class="btn-checkout">Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i></a>
        <a href="{{ route('shop.cart') }}"
            style="display:block;text-align:center;font-size:.75rem;color:var(--muted);margin-top:10px;text-decoration:none;">
            <i class="bi bi-bag me-1"></i>View Full Cart
        </a>
    </div>
</div>

<!-- Scroll to Top -->
<button id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="bi bi-chevron-up"></i>
</button>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove Item?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this item from your cart?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Remove</button>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap 5.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/js/core.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        // ── Mobile: toggle main dropdown panel ──
        const megaDropdowns = document.querySelectorAll(".mega-category-dropdown");

        megaDropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector(".dropdown-toggle-custom");

            if (toggle) {
                toggle.addEventListener("click", function (e) {
                    if (window.innerWidth <= 991) {
                        e.preventDefault();
                        dropdown.classList.toggle("open");

                        // Close other dropdowns
                        megaDropdowns.forEach(other => {
                            if (other !== dropdown) {
                                other.classList.remove("open");
                            }
                        });
                    }
                });
            }
        });

        // ── Close dropdown when clicking outside ──
        document.addEventListener("click", function (e) {
            if (!e.target.closest(".mega-category-dropdown")) {
                megaDropdowns.forEach(d => d.classList.remove("open"));
            }
        });

    });

    // ── Toggle subcategories (desktop + mobile) ──
    function toggleSubcategories(titleEl) {
        const column = titleEl.closest('.mega-column');
        const subList = column.querySelector('.subcategory-list');
        const isOpen = subList.classList.contains('open');

        // Close all first
        document.querySelectorAll('.subcategory-list').forEach(l => l.classList.remove('open'));
        document.querySelectorAll('.mega-title').forEach(t => t.classList.remove('active'));

        // Open current if it was closed
        if (!isOpen) {
            subList.classList.add('open');
            titleEl.classList.add('active');
        }
    }

    function goToCategory(categoryId) {
        sessionStorage.removeItem('filter_subcategory_id');
        sessionStorage.setItem('filter_category_id', categoryId);
        window.location.href = "{{ route('shop.products') }}";
    }

    function goToSubCategory(subCategoryId) {
        sessionStorage.removeItem('filter_category_id');
        sessionStorage.setItem('filter_subcategory_id', subCategoryId);
        window.location.href = "{{ route('shop.products') }}";
    }

    function changeLanguage(lang) {
        window.location.href = `/lang/${lang}`;
    }

    const accountDropdown = document.querySelector('.account-dropdown');
    const accountBtn = accountDropdown.querySelector('a');

    accountBtn.addEventListener('click', function (e) {
        e.preventDefault();
        accountDropdown.classList.toggle('active');
    });

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
