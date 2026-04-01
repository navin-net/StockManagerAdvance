@extends('frontend-v2.app')

@section('title', 'Home')

@section('content')
    <!-- ============================================================
                             HERO SLIDER
                             ============================================================ -->
    <div id="heroSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators"></div>
        <div class="carousel-inner"></div>

        <!-- controls keep same -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev"
            style="width:60px">
            <span style="background:rgba(255,255,255,.15);padding:12px;display:flex">
                <i class="bi bi-arrow-left" style="color:#fff;font-size:1.1rem"></i>
            </span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next"
            style="width:60px">
            <span style="background:rgba(255,255,255,.15);padding:12px;display:flex">
                <i class="bi bi-arrow-right" style="color:#fff;font-size:1.1rem"></i>
            </span>
        </button>
    </div>


    <!-- ============================================================
                             SHOP BY CATEGORY
                             ============================================================ -->
    <section id="categories">
        <div class="container-fluid px-4">
            <div class="row g-3">
                @if($categories->count() > 0)
                    @php
                        function getCatInitials($name)
                        {
                            $words = explode(' ', trim($name));
                            if (count($words) >= 2) {
                                return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                            }
                            return strtoupper(substr($name, 0, 2));
                        }

                        $bgColors = ['#1a1a2e', '#16213e', '#0f3460', '#533483', '#2b2d42', '#1b4332', '#6d2b3d'];
                        $txtColors = ['#e94560', '#e2b04a', '#4cc9f0', '#f72585', '#ef233c', '#95d5b2', '#f4a261'];
                        $cat1 = $categories->get(0);
                    @endphp

                    {{-- Reusable macro for the initials block --}}
                    @php
                        $catInitialBlock = function ($cat, $index) use ($bgColors, $txtColors) {
                            $initials = getCatInitials($cat->name);
                            $colorIndex = $index % count($bgColors);
                            $bg = $bgColors[$colorIndex];
                            $txt = $txtColors[$colorIndex];
                            return "
                                                <div style='
                                                    width:100%;height:100%;min-height:120px;
                                                    display:flex;align-items:center;justify-content:center;
                                                    background:{$bg};
                                                    font-size:2.5rem;font-weight:700;
                                                    font-family:Cormorant Garamond,serif;
                                                    letter-spacing:.05em;color:{$txt};
                                                '>{$initials}</div>
                                            ";
                        };
                    @endphp

                    {{-- Column 1: cat 1 (large card) --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="cat-card cat-card-lg" style="cursor:pointer" onclick="goToCategory({{ $cat1->id }})">
                            {!! $catInitialBlock($cat1, 0) !!}
                            <div class="cat-info">
                                <div class="cat-name">{{ $cat1->name }}</div>
                                <div class="cat-count">{{ $cat1->products_count }} Products</div>
                            </div>
                            <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>

                    {{-- Column 2: cat 2 & 3 stacked --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="row g-3 h-100">
                            @foreach($categories->slice(1, 2)->values() as $index => $cat)
                                <div class="col-12">
                                    <div class="cat-card" style="cursor:pointer" onclick="goToCategory({{ $cat->id }})">
                                        {!! $catInitialBlock($cat, $index + 1) !!}
                                        <div class="cat-info">
                                            <div class="cat-name">{{ $cat->name }}</div>
                                            <div class="cat-count">{{ $cat->products_count }} Products</div>
                                        </div>
                                        <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Column 3: cat 4 & 5 side by side --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="row g-3 h-100">
                            @foreach($categories->slice(3, 2)->values() as $index => $cat)
                                <div class="col-6">
                                    <div class="cat-card" style="cursor:pointer" onclick="goToCategory({{ $cat->id }})">
                                        {!! $catInitialBlock($cat, $index + 3) !!}
                                        <div class="cat-info">
                                            <div class="cat-name">{{ $cat->name }}</div>
                                            <div class="cat-count">{{ $cat->products_count }} Products</div>
                                        </div>
                                        <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Remaining cats grouped: 1 large + 2 stacked + 2 side-by-side --}}
                    @php $remaining = $categories->slice(5)->values(); @endphp

                    @if($remaining->count() > 0)
                        @foreach($remaining->chunk(5) as $chunk)
                            @php $chunk = $chunk->values(); @endphp

                            {{-- Large card --}}
                            @if($chunk->get(0))
                                @php $cat = $chunk->get(0);
                                $i = $remaining->search(fn($c) => $c->id === $cat->id) + 5; @endphp
                                <div class="col-lg-4 col-md-6">
                                    <div class="cat-card cat-card-lg" style="cursor:pointer" onclick="goToCategory({{ $cat->id }})">
                                        {!! $catInitialBlock($cat, $i) !!}
                                        <div class="cat-info">
                                            <div class="cat-name">{{ $cat->name }}</div>
                                            <div class="cat-count">{{ $cat->products_count }} Products</div>
                                        </div>
                                        <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                                    </div>
                                </div>
                            @endif

                            {{-- 2 stacked --}}
                            @if($chunk->get(1) || $chunk->get(2))
                                <div class="col-lg-4 col-md-6">
                                    <div class="row g-3 h-100">
                                        @foreach([$chunk->get(1), $chunk->get(2)] as $cat)
                                            @if($cat)
                                                @php $i = $remaining->search(fn($c) => $c->id === $cat->id) + 5; @endphp
                                                <div class="col-12">
                                                    <div class="cat-card" style="cursor:pointer" onclick="goToCategory({{ $cat->id }})">
                                                        {!! $catInitialBlock($cat, $i) !!}
                                                        <div class="cat-info">
                                                            <div class="cat-name">{{ $cat->name }}</div>
                                                            <div class="cat-count">{{ $cat->products_count }} Products</div>
                                                        </div>
                                                        <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- 2 side by side --}}
                            @if($chunk->get(3) || $chunk->get(4))
                                <div class="col-lg-4 col-md-6">
                                    <div class="row g-3 h-100">
                                        @foreach([$chunk->get(3), $chunk->get(4)] as $cat)
                                            @if($cat)
                                                @php $i = $remaining->search(fn($c) => $c->id === $cat->id) + 5; @endphp
                                                <div class="col-6">
                                                    <div class="cat-card" style="cursor:pointer" onclick="goToCategory({{ $cat->id }})">
                                                        {!! $catInitialBlock($cat, $i) !!}
                                                        <div class="cat-info">
                                                            <div class="cat-name">{{ $cat->name }}</div>
                                                            <div class="cat-count">{{ $cat->products_count }} Products</div>
                                                        </div>
                                                        <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        @endforeach
                    @endif

                @endif
            </div>
        </div>
    </section>

    <!-- ============================================================
                             FILTER BAR
                             ============================================================ -->


    <!-- ============================================================
                             TRENDING PRODUCTS
                             ============================================================ -->
    <section id="trending">
        <div class="container-fluid px-4 py-5">
            <div class="row mb-4 align-items-end">
                <div class="col">
                    <div class="section-label">What's Hot Right Now</div>
                    <h2 class="section-title">Trending <strong>Products</strong></h2>
                    <div class="title-rule"></div>
                </div>
                <div class="col-auto">
                    <a href="#"
                        style="font-size:.8rem;letter-spacing:.08em;text-transform:uppercase;font-weight:600;color:var(--accent);text-decoration:none;">
                        View All Products <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="row g-4" id="productsGrid">
                <!-- API Products Here -->
            </div>
        </div>
    </section>

    <!-- ============================================================
                             PROMO COUNTDOWN BANNER
                             ============================================================ -->
    <section id="promoBanner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section-label" style="color:var(--accent-light)">Flash Sale — Ends Soon</div>
                    <div class="promo-offer">
                        Extra <strong>20% Off</strong><br>
                        Sitewide Today Only
                    </div>
                    <div class="promo-countdown">
                        <div class="countdown-unit">
                            <div class="countdown-num" id="cdHours">08</div>
                            <div class="countdown-lbl">Hours</div>
                        </div>
                        <div class="countdown-unit">
                            <div class="countdown-num" id="cdMins">34</div>
                            <div class="countdown-lbl">Minutes</div>
                        </div>
                        <div class="countdown-unit">
                            <div class="countdown-num" id="cdSecs">00</div>
                            <div class="countdown-lbl">Seconds</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="#" class="btn-hero" style="font-size:.8rem;">Claim Offer <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0 d-none d-lg-block" style="text-align:center">
                    <div
                        style="font-family:'Cormorant Garamond',serif;font-size:9rem;font-weight:700;color:var(--accent);line-height:1;opacity:.25;letter-spacing:.05em">
                        20%</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
                             BRANDS MARQUEE
                             ============================================================ -->
    <section id="brands">
        <div style="overflow:hidden">
            <div id="brand-track" class="brands-track">
            </div>
        </div>
    </section>

    <!-- ============================================================
                             TESTIMONIALS
                             ============================================================ -->
    <section id="testimonials">
        <div class="container-fluid px-4">
            <div class="text-center mb-5">
                <div class="section-label">What Our Clients Say</div>
                <h2 class="section-title">Happy Clients <strong>Love</strong> Us</h2>
                <div class="title-rule mx-auto"></div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="testi-card">
                        <div class="big-quote">"</div>
                        <div class="testi-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-quote">The quality of the pieces I received far exceeded my expectations. The
                            packaging was exquisite and delivery was incredibly prompt. Luxé has completely changed how
                            I shop for fashion.</p>
                        <div class="testi-meta">
                            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=100&q=80"
                                class="testi-avatar" alt="Sophie" loading="lazy" />
                            <div>
                                <div class="testi-name">Sophie Laurent</div>
                                <div class="testi-role">Fashion Stylist, Paris</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="testi-card">
                        <div class="big-quote">"</div>
                        <div class="testi-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-quote">I've tried many luxury e-tailers, but none come close to the curation
                            and customer service at Luxé. Every single item tells a story, and the in-store pickup
                            experience is seamlessly elegant.</p>
                        <div class="testi-meta">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=80"
                                class="testi-avatar" alt="Marcus" loading="lazy" />
                            <div>
                                <div class="testi-name">Marcus Chen</div>
                                <div class="testi-role">Creative Director, NYC</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="testi-card">
                        <div class="big-quote">"</div>
                        <div class="testi-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                        </div>
                        <p class="testi-quote">The 15% in-store pickup offer sealed the deal for me. Finding my dream
                            coat here, at that price, with same-day pick-up — I'm a lifelong customer now. The brand
                            selection is outstanding.</p>
                        <div class="testi-meta">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&q=80"
                                class="testi-avatar" alt="Amara" loading="lazy" />
                            <div>
                                <div class="testi-name">Amara Osei</div>
                                <div class="testi-role">Architect & Style Blogger</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="testi-card">
                        <div class="big-quote">"</div>
                        <div class="testi-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-quote">A truly effortless shopping experience. The mega menu helps me navigate
                            collections instantly and the product photography is magazine-worthy. Returns were handled
                            with zero fuss.</p>
                        <div class="testi-meta">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&q=80"
                                class="testi-avatar" alt="Elena" loading="lazy" />
                            <div>
                                <div class="testi-name">Elena Vasquez</div>
                                <div class="testi-role">Brand Consultant, Milan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="testi-card">
                        <div class="big-quote">"</div>
                        <div class="testi-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="testi-quote">Ordered three items, all arrived perfectly packaged in branded boxes.
                            The Voltaire watch I bought is a conversation starter everywhere I go. Luxé has genuinely
                            elevated my wardrobe.</p>
                        <div class="testi-meta">
                            <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=100&q=80"
                                class="testi-avatar" alt="James" loading="lazy" />
                            <div>
                                <div class="testi-name">James Whitfield</div>
                                <div class="testi-role">Investment Banker, London</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="testi-card">
                        <div class="big-quote">"</div>
                        <div class="testi-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                        </div>
                        <p class="testi-quote">I used to dread online shopping for luxury goods — the fear of
                            counterfeits was real. Luxé authenticated every piece and the authenticity cards were a
                            thoughtful touch. Completely trustworthy.</p>
                        <div class="testi-meta">
                            <img src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=100&q=80"
                                class="testi-avatar" alt="Yuki" loading="lazy" />
                            <div>
                                <div class="testi-name">Yuki Tanaka</div>
                                <div class="testi-role">Photographer, Tokyo</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
                             NEWSLETTER
                             ============================================================ -->
    <section id="newsletter">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center text-lg-start">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="section-label" style="color:var(--accent-light)">Stay in the Know</div>
                    <h2 class="section-title" style="color:#fff">Follow Your <strong
                            style="color:var(--accent)">Idea.</strong></h2>
                    <p style="color:rgba(255,255,255,.55);font-size:.9rem;margin-top:.8rem">Join 50,000+
                        style-conscious subscribers. Exclusive drops, early access, and member-only deals — delivered to
                        your inbox.</p>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="newsletter-form d-flex">
                        <input type="email" placeholder="Your email address..." />
                        <button class="btn-subscribe">Subscribe <i class="bi bi-arrow-right ms-1"></i></button>
                    </div>
                    <p style="font-size:.72rem;color:rgba(255,255,255,.35);margin-top:.8rem"><i
                            class="bi bi-shield-check me-1" style="color:var(--accent)"></i>No spam. Unsubscribe any
                        time. Your data is safe with us.</p>
                </div>
            </div>
        </div>
    </section>


@endsection
@push('scripts')
    <script>
        const indicators = document.querySelector("#heroSlider .carousel-indicators");
        const inner = document.querySelector("#heroSlider .carousel-inner");

        async function loadSlides() {
            try {
                const res = await fetch('/api/slides');
                const SLIDES = await res.json();

                inner.innerHTML = SLIDES.map((s, i) => `
                                <div class="carousel-item ${i === 0 ? 'active' : ''}">
                                    <div class="hero-slide ${s.slideClass}">
                                        <div class="hero-art">
                                            <img src="${s.img}" loading="${i === 0 ? 'eager' : 'lazy'}" />
                                        </div>
                                        <div class="hero-content">
                                            <div class="hero-eyebrow">${s.eyebrow}</div>
                                            <h1 class="hero-title">${s.title}</h1>
                                            <p class="hero-sub">${s.sub}</p>
                                            <a href="#" class="btn-hero" style="${s.btn1Style}">
                                                ${s.btn1} <i class="bi bi-arrow-right"></i>
                                            </a>
                                            <a href="#" class="btn-hero-outline">${s.btn2}</a>
                                        </div>
                                        <div class="slide-progress"></div>
                                    </div>
                                </div>
                            `).join('');

                indicators.innerHTML = SLIDES.map((_, i) => `
                                <button type="button"
                                    data-bs-target="#heroSlider"
                                    data-bs-slide-to="${i}"
                                    class="${i === 0 ? 'active' : ''}">
                                </button>
                            `).join('');

                // 🔥 IMPORTANT: re-init carousel after dynamic render
                new bootstrap.Carousel('#heroSlider', {
                    interval: 5000,
                    ride: 'carousel'
                });

            } catch (err) {
                console.error('Slider load failed:', err);
            }
        }

        loadSlides();

        const brandTrack = document.getElementById('brand-track');

        async function loadBrands() {
            try {
                const response = await fetch('api/getBrands');
                const brands = await response.json();
                const brandItems = brands.map(brand => `
                            <span class="brand-item">${brand.name}</span>

                            <span class="brand-item">·</span>`).join('');

                brandTrack.innerHTML = brandItems + brandItems;

            } catch (error) {
                console.error('Error loading brands:', error);
                brandTrack.innerHTML = "<span>Check back soon for our partners</span>";
            }
        }

        loadBrands();
        function goToCategory(categoryId) {
            sessionStorage.setItem('filter_category_id', categoryId);
            window.location.href = "{{ route('shop.products') }}";
        }


        async function loadTrendingProducts() {
            try {
                const response = await fetch('/api/getTrending');
                const products = await response.json();

                const productItems = products.map(product => {
                    const image = product.image
                        ? `/storage/${product.image}`
                        : `/noimage.png`;

                    const brandName = product.brand?.name ?? 'No Brand';
                    const tag = product.tag ?? 'trending';
                    const badge = product.badge ?? 'Hot';

                    const badgeClass =
                        tag === 'new' ? 'new' :
                            tag === 'sale' ? 'sale' : '';

                    const priceHtml = product.old_price
                        ? `<del>$${parseFloat(product.old_price).toFixed(2)}</del> <span class="sale-price">$${parseFloat(product.selling_price).toFixed(2)}</span>`
                        : `$${parseFloat(product.selling_price).toFixed(2)}`;

                    return `
                                <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-tag="${tag}">
                                    <div class="product-card">
                                        <div class="product-img-wrap">
                                            <img src="${image}" alt="${product.name}" loading="lazy" />
                                            <div class="product-actions">
                                                <button class="action-btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                                <button class="action-btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                                <button class="action-btn" title="Compare"><i class="bi bi-bar-chart-steps"></i></button>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <div class="product-brand">${brandName}</div>
                                            <div class="product-name">${product.name}</div>
                                            <div class="product-stars">
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-fill"></i>
                                                <i class="bi bi-star-half"></i>
                                                <span>(${product.reviews_count ?? 128})</span>
                                            </div>
                                            <div class="product-price">${priceHtml}</div>
                                            <button class="btn-cart">
                                                <i class="bi bi-bag-plus"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                }).join('');

                productsGrid.innerHTML = productItems;

            } catch (error) {
                console.error('Error loading trending products:', error);
                productsGrid.innerHTML = `
                            <div class="col-12 text-center text-danger">
                                Failed to load trending products.
                            </div>
                        `;
            }
        }

        loadTrendingProducts();
    </script>
@endpush
