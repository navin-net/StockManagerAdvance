<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LUXE — Premium E-Commerce</title>

    <!-- Bootstrap 5.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
</head>

<body>

    <!-- ============================================================
     TOPBAR
     ============================================================ -->
    <div id="topbar">
        <div class="container-fluid px-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="d-flex gap-4">
                        <a href="#"><i class="bi bi-geo-alt me-1"></i>Find a Store</a>
                        <a href="#"><i class="bi bi-truck me-1"></i>Order Tracking</a>
                    </div>
                </div>
                <div class="col text-center promo d-none d-md-block">
                    <span>15% Off $99+</span> when you buy online &amp; pick up in-store
                </div>
                <div class="col-auto">
                    <div class="d-flex align-items-center gap-3">
                        <select class="currency-select" id="currencySelect">
                            <option>USD $</option>
                            <option>EUR €</option>
                            <option>GBP £</option>
                            <option>JPY ¥</option>
                            <option>AUD A$</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
     NAVBAR
     ============================================================ -->
    <nav id="mainNav" class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">LUX<span>É</span></a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navCollapse">
                <ul class="navbar-nav mx-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>

                    <!-- Products mega -->
                    <li class="nav-item mega position-relative">
                        <a class="nav-link" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Shopping Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Checkout</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                </ul>

                <!-- Icons -->
                <div class="nav-icons d-flex align-items-center">
                    <a href="#" title="Account"><i class="bi bi-person"></i></a>
                    <a href="#" title="Wishlist"><i class="bi bi-heart"></i></a>
                    <a href="#" id="cartToggle" title="Cart" style="position:relative">
                        <i class="bi bi-bag"></i>
                        <span class="badge-dot">3</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================================
     HERO SLIDER
     ============================================================ -->
    <div id="heroSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <div class="hero-slide slide-1">
                    <div class="hero-art">
                        <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1400&q=80" alt=""
                            loading="eager" />
                    </div>
                    <div class="hero-content">
                        <div class="hero-eyebrow">New Collection — Spring 2026</div>
                        <h1 class="hero-title">Dress with<br><em>Intent.</em></h1>
                        <p class="hero-sub">Curated luxury pieces for those who understand that style is a language
                            spoken without words.</p>
                        <a href="#" class="btn-hero">Shop Collection <i class="bi bi-arrow-right"></i></a>
                        <a href="#" class="btn-hero-outline">Explore Lookbook</a>
                    </div>
                    <div class="slide-progress"></div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <div class="hero-slide slide-2">
                    <div class="hero-art">
                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1400&q=80" alt=""
                            loading="lazy" />
                    </div>
                    <div class="hero-content">
                        <div class="hero-eyebrow">Limited Edition — Capsule Drop</div>
                        <h1 class="hero-title">Beyond the<br><em>Ordinary.</em></h1>
                        <p class="hero-sub">Exclusive pieces that redefine the boundaries of contemporary fashion and
                            personal expression.</p>
                        <a href="#" class="btn-hero">View Capsule <i class="bi bi-arrow-right"></i></a>
                        <a href="#" class="btn-hero-outline">Learn More</a>
                    </div>
                    <div class="slide-progress"></div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">
                <div class="hero-slide slide-3">
                    <div class="hero-art">
                        <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=1400&q=80" alt=""
                            loading="lazy" />
                    </div>
                    <div class="hero-content">
                        <div class="hero-eyebrow">Sale — Up to 40% Off</div>
                        <h1 class="hero-title">Luxury at<br><em>Your Price.</em></h1>
                        <p class="hero-sub">Select pieces from our archive collections now available at exclusive
                            members-only pricing.</p>
                        <a href="#" class="btn-hero" style="background:#c0392b">Shop Sale <i
                                class="bi bi-arrow-right"></i></a>
                        <a href="#" class="btn-hero-outline">All Offers</a>
                    </div>
                    <div class="slide-progress"></div>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev"
            style="width:60px">
            <span style="background:rgba(255,255,255,.15);padding:12px;display:flex"><i class="bi bi-arrow-left"
                    style="color:#fff;font-size:1.1rem"></i></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next"
            style="width:60px">
            <span style="background:rgba(255,255,255,.15);padding:12px;display:flex"><i class="bi bi-arrow-right"
                    style="color:#fff;font-size:1.1rem"></i></span>
        </button>
    </div>

    <!-- ============================================================
     SHOP BY CATEGORY
     ============================================================ -->
    <section id="categories">
        <div class="container-fluid px-4">
            <div class="row mb-4 align-items-end">
                <div class="col">
                    <div class="section-label">Curated for You</div>
                    <h2 class="section-title">Shop by <strong>Category</strong></h2>
                    <div class="title-rule"></div>
                </div>
                <div class="col-auto">
                    <a href="#"
                        style="font-size:.8rem;letter-spacing:.08em;text-transform:uppercase;font-weight:600;color:var(--accent);text-decoration:none;">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <!-- Large card -->
                <div class="col-lg-4 col-md-6">
                    <div class="cat-card cat-card-lg">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80" alt="Women"
                            loading="lazy" />
                        <div class="cat-info">
                            <div class="cat-name">Women</div>
                            <div class="cat-count">342 Products</div>
                        </div>
                        <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>

                <!-- Stack of 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="row g-3 h-100">
                        <div class="col-12">
                            <div class="cat-card">
                                <img src="https://images.unsplash.com/photo-1617137968427-85924c800a22?w=800&q=80"
                                    alt="Men" loading="lazy" />
                                <div class="cat-info">
                                    <div class="cat-name">Men</div>
                                    <div class="cat-count">218 Products</div>
                                </div>
                                <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cat-card">
                                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&q=80"
                                    alt="Accessories" loading="lazy" />
                                <div class="cat-info">
                                    <div class="cat-name">Accessories</div>
                                    <div class="cat-count">196 Products</div>
                                </div>
                                <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two small -->
                <div class="col-lg-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="cat-card">
                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80"
                                    alt="Footwear" loading="lazy" />
                                <div class="cat-info">
                                    <div class="cat-name">Shoes</div>
                                    <div class="cat-count">124 Items</div>
                                </div>
                                <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="cat-card">
                                <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80"
                                    alt="Bags" loading="lazy" />
                                <div class="cat-info">
                                    <div class="cat-name">Bags</div>
                                    <div class="cat-count">89 Items</div>
                                </div>
                                <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="cat-card">
                                <img src="https://images.unsplash.com/photo-1586473219010-2ffc57b0d282?w=600&q=80"
                                    alt="Jewelry" loading="lazy" />
                                <div class="cat-info">
                                    <div class="cat-name">Jewelry</div>
                                    <div class="cat-count">67 Items</div>
                                </div>
                                <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="cat-card">
                                <img src="https://images.unsplash.com/photo-1541643600914-78b084683702?w=600&q=80"
                                    alt="Fragrance" loading="lazy" />
                                <div class="cat-info">
                                    <div class="cat-name">Fragrance</div>
                                    <div class="cat-count">43 Items</div>
                                </div>
                                <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
     FILTER BAR
     ============================================================ -->
    <div id="filterBar">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span
                    style="font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-right:8px;">Filter:</span>
                <button class="filter-btn active" onclick="filterProducts(this,'all')">All</button>
                <button class="filter-btn" onclick="filterProducts(this,'new')">New</button>
                <button class="filter-btn" onclick="filterProducts(this,'sale')">Sale</button>
                <button class="filter-btn" onclick="filterProducts(this,'trending')">Trending</button>

                <div class="ms-auto d-flex align-items-center gap-2">
                    <select class="filter-select">
                        <option>All Brands</option>
                        <option>Maison Noir</option>
                        <option>Éclat Paris</option>
                        <option>Studio Muse</option>
                        <option>BLOC</option>
                    </select>
                    <select class="filter-select">
                        <option>All Categories</option>
                        <option>Women</option>
                        <option>Men</option>
                        <option>Accessories</option>
                        <option>Footwear</option>
                    </select>
                    <select class="filter-select">
                        <option>Sort: Featured</option>
                        <option>Price: Low – High</option>
                        <option>Price: High – Low</option>
                        <option>Newest First</option>
                        <option>Best Rating</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

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

                <!-- Product 1 -->
                <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-tag="new">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=600&q=80"
                                alt="Silk Blazer" loading="lazy" />
                            <span class="product-badge new">New</span>
                            <div class="product-actions">
                                <button class="action-btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                <button class="action-btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                <button class="action-btn" title="Compare"><i
                                        class="bi bi-bar-chart-steps"></i></button>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-brand">Éclat Paris</div>
                            <div class="product-name">Oversized Silk Blazer</div>
                            <div class="product-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-half"></i> <span>(128)</span>
                            </div>
                            <div class="product-price">$245.00</div>
                            <button class="btn-cart"><i class="bi bi-bag-plus"></i> Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-tag="sale">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=600&q=80"
                                alt="Sneakers" loading="lazy" />
                            <span class="product-badge sale">−30%</span>
                            <div class="product-actions">
                                <button class="action-btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                <button class="action-btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                <button class="action-btn" title="Compare"><i
                                        class="bi bi-bar-chart-steps"></i></button>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-brand">Studio Muse</div>
                            <div class="product-name">Minimalist Runner Sneakers</div>
                            <div class="product-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i> <span>(94)</span>
                            </div>
                            <div class="product-price"><del>$180.00</del> <span class="sale-price">$126.00</span>
                            </div>
                            <button class="btn-cart"><i class="bi bi-bag-plus"></i> Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-tag="trending">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80"
                                alt="Leather Bag" loading="lazy" />
                            <span class="product-badge">Hot</span>
                            <div class="product-actions">
                                <button class="action-btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                <button class="action-btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                <button class="action-btn" title="Compare"><i
                                        class="bi bi-bar-chart-steps"></i></button>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-brand">Maison Noir</div>
                            <div class="product-name">Structured Leather Tote</div>
                            <div class="product-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star"></i> <span>(67)</span></div>
                            <div class="product-price">$398.00</div>
                            <button class="btn-cart"><i class="bi bi-bag-plus"></i> Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-tag="new">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80"
                                alt="Watch" loading="lazy" />
                            <span class="product-badge new">New</span>
                            <div class="product-actions">
                                <button class="action-btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                <button class="action-btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                <button class="action-btn" title="Compare"><i
                                        class="bi bi-bar-chart-steps"></i></button>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-brand">Voltaire Co.</div>
                            <div class="product-name">Heritage Chronograph Watch</div>
                            <div class="product-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-half"></i> <span>(211)</span>
                            </div>
                            <div class="product-price">$650.00</div>
                            <button class="btn-cart"><i class="bi bi-bag-plus"></i> Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-tag="trending">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=600&q=80"
                                alt="Perfume" loading="lazy" />
                            <span class="product-badge">Trend</span>
                            <div class="product-actions">
                                <button class="action-btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                <button class="action-btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                <button class="action-btn" title="Compare"><i
                                        class="bi bi-bar-chart-steps"></i></button>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-brand">Éclat Paris</div>
                            <div class="product-name">Noir Absolu Eau de Parfum</div>
                            <div class="product-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i> <span>(455)</span>
                            </div>
                            <div class="product-price">$185.00</div>
                            <button class="btn-cart"><i class="bi bi-bag-plus"></i> Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 6 -->
                <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-tag="sale">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600&q=80" alt="Jacket"
                                loading="lazy" />
                            <span class="product-badge sale">−25%</span>
                            <div class="product-actions">
                                <button class="action-btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                <button class="action-btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                <button class="action-btn" title="Compare"><i
                                        class="bi bi-bar-chart-steps"></i></button>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-brand">Raw Union</div>
                            <div class="product-name">Waxed Canvas Field Jacket</div>
                            <div class="product-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star"></i> <span>(83)</span></div>
                            <div class="product-price"><del>$320.00</del> <span class="sale-price">$240.00</span>
                            </div>
                            <button class="btn-cart"><i class="bi bi-bag-plus"></i> Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 7 -->
                <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-tag="new">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=600&q=80"
                                alt="Sunglasses" loading="lazy" />
                            <span class="product-badge new">New</span>
                            <div class="product-actions">
                                <button class="action-btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                <button class="action-btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                <button class="action-btn" title="Compare"><i
                                        class="bi bi-bar-chart-steps"></i></button>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-brand">Atelier 22</div>
                            <div class="product-name">Oversized Tortoise Sunglasses</div>
                            <div class="product-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-half"></i> <span>(59)</span>
                            </div>
                            <div class="product-price">$145.00</div>
                            <button class="btn-cart"><i class="bi bi-bag-plus"></i> Add to Cart</button>
                        </div>
                    </div>
                </div>

                <!-- Product 8 -->
                <div class="col-xl-3 col-lg-4 col-md-6 product-item" data-tag="trending">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="https://images.unsplash.com/photo-1611652022419-a9419f74343d?w=600&q=80"
                                alt="Dress" loading="lazy" />
                            <span class="product-badge">Hot</span>
                            <div class="product-actions">
                                <button class="action-btn" title="Wishlist"><i class="bi bi-heart"></i></button>
                                <button class="action-btn" title="Quick View"><i class="bi bi-eye"></i></button>
                                <button class="action-btn" title="Compare"><i
                                        class="bi bi-bar-chart-steps"></i></button>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-brand">Maison Noir</div>
                            <div class="product-name">Draped Midi Evening Dress</div>
                            <div class="product-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i> <span>(177)</span>
                            </div>
                            <div class="product-price">$520.00</div>
                            <button class="btn-cart"><i class="bi bi-bag-plus"></i> Add to Cart</button>
                        </div>
                    </div>
                </div>

            </div><!-- /row -->
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
            <div class="brands-track">
                <span class="brand-item">Maison Noir</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Éclat Paris</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Studio Muse</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Voltaire Co.</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Raw Union</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Atelier 22</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Canvas & Co</span>
                <span class="brand-item">·</span>
                <span class="brand-item">BLOC</span>
                <span class="brand-item">·</span>
                <!-- duplicate for seamless loop -->
                <span class="brand-item">Maison Noir</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Éclat Paris</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Studio Muse</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Voltaire Co.</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Raw Union</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Atelier 22</span>
                <span class="brand-item">·</span>
                <span class="brand-item">Canvas & Co</span>
                <span class="brand-item">·</span>
                <span class="brand-item">BLOC</span>
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
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i></div>
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
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i></div>
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
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-half"></i></div>
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
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i></div>
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
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i></div>
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
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-half"></i></div>
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

    <!-- ============================================================
     FOOTER
     ============================================================ -->
    <footer id="footer">
        <div class="container-fluid px-4">
            <div class="row g-5">
                <div class="col-lg-3">
                    <div class="footer-brand">LUX<span>É</span></div>
                    <div class="footer-tagline">Follow Your Idea</div>
                    <p style="font-size:.82rem;color:rgba(255,255,255,.4);margin-top:1rem;line-height:1.8">Curated
                        luxury fashion from the world's most distinctive designers. Every piece tells a story.</p>
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
                        <li><a href="#">About Luxé</a></li>
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
                        14 Boulevard Saint-Germain<br>Paris, 75006, France<br><br>
                        Mon – Sat: 10:00 – 20:00<br>
                        Sun: 12:00 – 18:00
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
                <span>© 2026 Luxé. All Rights Reserved.</span>
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
                        <button
                            style="background:none;border:none;color:var(--muted);font-size:.75rem;cursor:pointer;margin-left:auto">Remove</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* ── CART TOGGLE ── */
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

        /* ── SCROLL TO TOP ── */
        const scrollBtn = document.getElementById('scrollTop');
        window.addEventListener('scroll', () => {
            scrollBtn.style.display = window.scrollY > 400 ? 'flex' : 'none';
        });

        /* ── PRODUCT FILTER ── */
        function filterProducts(btn, tag) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.product-item').forEach(item => {
                if (tag === 'all' || item.dataset.tag === tag) {
                    item.style.display = '';
                    item.style.animation = 'fadeIn .4s ease both';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        /* ── COUNTDOWN ── */
        function startCountdown() {
            const end = new Date();
            end.setHours(end.getHours() + 8, end.getMinutes() + 34, end.getSeconds() + 0);
            const tick = () => {
                const now = new Date();
                const diff = Math.max(0, end - now);
                const h = Math.floor(diff / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                document.getElementById('cdHours').textContent = String(h).padStart(2, '0');
                document.getElementById('cdMins').textContent = String(m).padStart(2, '0');
                document.getElementById('cdSecs').textContent = String(s).padStart(2, '0');
                if (diff > 0) requestAnimationFrame(tick);
            };
            tick();
        }
        startCountdown();

        /* ── ADD TO CART FEEDBACK ── */
        document.querySelectorAll('.btn-cart').forEach(btn => {
            btn.addEventListener('click', function () {
                const orig = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-circle"></i> Added!';
                this.style.background = 'var(--accent)';
                setTimeout(() => {
                    this.innerHTML = orig;
                    this.style.background = '';
                }, 1800);
            });
        });

        /* ── WISHLIST FEEDBACK ── */
        document.querySelectorAll('.action-btn[title="Wishlist"]').forEach(btn => {
            btn.addEventListener('click', function () {
                this.style.background = 'var(--accent)';
                this.style.color = '#fff';
                this.innerHTML = '<i class="bi bi-heart-fill"></i>';
            });
        });

        /* ── NAV SCROLL EFFECT ── */
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('mainNav');
            nav.style.boxShadow = window.scrollY > 30 ?
                '0 4px 30px rgba(26,20,16,.12)' :
                '0 2px 20px rgba(26,20,16,.06)';
        });

        /* ── FADE IN ANIMATION ── */
        const style = document.createElement('style');
        style.textContent =
            '@keyframes fadeIn { from { opacity:0; transform:translateY(12px);} to { opacity:1; transform:translateY(0);} }';
        document.head.appendChild(style);

        /* ── INTERSECTION OBSERVER for reveal ── */
        const revealEls = document.querySelectorAll('.product-card, .testi-card, .cat-card');
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(20px)';
                    entry.target.style.transition =
                        `opacity .5s ease ${i * 0.05}s, transform .5s ease ${i * 0.05}s`;
                    requestAnimationFrame(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    });
                    revealObs.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        revealEls.forEach(el => revealObs.observe(el));
    </script>
</body>

</html>
