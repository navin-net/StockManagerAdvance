@extends('frontend-v2.app')

@section('title', 'product_detail')

@section('content')
    <!-- ── PAGE HERO ── -->
    <div class="page-hero">
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('shop/products') }}">Products</a></li>
                    {{-- <li class="breadcrumb-item"><a href="#">{{ $product->category->name }}</a></li> --}}
                    <li class="breadcrumb-item active">Products - {{ $product->name }}</li>
                </ol>
            </nav>
            <div class="page-hero-eyebrow">Curated Collection</div>
            <h1 class="page-hero-title"><em>Products - {{ $product->name }}</em></h1>
        </div>
    </div>
    <!-- BREADCRUMB -->
    <div class="container-fluid px-4">
        <div class="page-breadcrumb">
            <a href="{{ url('/') }}">Home</a><span class="sep">›</span>
            <a href="{{ url('shop/products') }}">Product</a><span class="sep">›</span>
            {{-- <a href="#">{{ $product->category->name }}</a><span class="sep">›</span> --}}
            <span style="color:var(--dark)">{{ $product->name }}</span>
        </div>
    </div>


    <!-- PRODUCT DETAIL -->
    <section id="productDetail">
        <div class="container-fluid px-4">
            <div class="row g-4">

                <!-- Gallery -->
                <div class="col-lg-6">
                    <div class="gallery-main" id="galleryMain">
                        <img id="mainImg"
                            src="{{ $product->image ? asset('storage/' . $product->image) : asset('noimage.png') }}"
                            alt="Oversized Silk Blazer" />
                        <span class="gallery-badge">New Arrival</span>
                        <button class="gallery-zoom" onclick="openLightbox()"><i class="bi bi-zoom-in"></i></button>
                    </div>
                    <div class="gallery-thumbs">
                        <div class="gallery-thumb active"
                            onclick="switchImg(this, '{{ $product->image ? asset('storage/' . $product->image) : asset('noimage.png') }}')">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('noimage.png') }}"
                                alt="" />
                        </div>
                        @foreach($product->images as $image)
                            <div class="gallery-thumb" onclick="switchImg(this, '{{ Storage::url($image->image_review) }}')">

                                <img src="{{ Storage::url($image->image_review) }}" alt="Product Review Image"
                                    style="width: 100%; height: auto; display: block;" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Info -->
                <div class="col-lg-6 product-info">
                    <div class="prod-brand">{{ $product->brand->name }}</div>
                    <h1 class="prod-name">{{ $product->name }}</h1>
                    <div class="prod-sku">{{ $product->code }}</div>
                    <div class="prod-rating">
                        <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                        </div>
                        <a href="#reviews" class="review-count">128 Reviews</a>
                        <span style="font-size:.72rem;color:var(--green);font-weight:600;"><i
                                class="bi bi-patch-check-fill me-1"></i>Verified Brand</span>
                    </div>
                    <div class="prod-price"><del>{{ $product->selling_price }}</del> <span
                            class="sale-price">{{ $product->cost_price }}</span></div>
                    <p class="prod-desc">{{ $product->description }}</p>

                    <!-- Color -->
                    <div class="option-label">Color: <span style="font-weight:400;color:var(--muted);"
                            id="colorLabel">Ivory</span></div>
                    <div class="color-swatches">
                        <div class="swatch swatch-ivory active" title="Ivory" onclick="selectColor(this,'Ivory')"></div>
                        <div class="swatch swatch-black" title="Black" onclick="selectColor(this,'Black')"></div>
                        <div class="swatch swatch-camel" title="Camel" onclick="selectColor(this,'Camel')"></div>
                        <div class="swatch swatch-navy" title="Navy" onclick="selectColor(this,'Navy')"></div>
                        <div class="swatch swatch-rose" title="Rose" onclick="selectColor(this,'Rose')"></div>
                    </div>

                    <!-- Size -->
                    <div class="option-label">Size: <span style="font-weight:400;color:var(--muted);"
                            id="sizeLabel">Select</span>
                        <a href="#" class="size-guide-link">Size Guide</a>
                    </div>
                    <div class="size-grid">
                        <button class="size-btn unavailable" title="Out of stock">XS</button>
                        <button class="size-btn" onclick="selectSize(this,'S')">S</button>
                        <button class="size-btn active" onclick="selectSize(this,'M')">M</button>
                        <button class="size-btn" onclick="selectSize(this,'L')">L</button>
                        <button class="size-btn" onclick="selectSize(this,'XL')">XL</button>
                        <button class="size-btn unavailable" title="Out of stock">XXL</button>
                    </div>

                    <!-- Qty + Add -->
                    <div class="qty-row">
                        <div class="qty-wrap">
                            <button class="qty-btn" onclick="changeQty(-1)"><i class="bi bi-dash"></i></button>
                            <div class="qty-num" id="prodQty">1</div>
                            <button class="qty-btn" onclick="changeQty(1)"><i class="bi bi-plus"></i></button>
                        </div>
                        <button class="btn-add-cart" onclick="addToCart()"><i class="bi bi-bag-plus"></i> Add to
                            Bag</button>
                        <button class="btn-wishlist" id="wishBtn" onclick="toggleWish()"><i
                                class="bi bi-heart"></i></button>
                    </div>
                    <button class="btn-buy-now"><i class="bi bi-lightning-fill"></i> Buy Now — Express Checkout</button>

                    <!-- Trust -->
                    <div class="trust-row">
                        <div class="trust-item"><i class="bi bi-shield-check"></i> Authentic</div>
                        <div class="trust-item"><i class="bi bi-arrow-return-left"></i> Free Returns</div>
                        <div class="trust-item"><i class="bi bi-truck"></i> Free Shipping</div>
                        <div class="trust-item"><i class="bi bi-lock"></i> Secure Payment</div>
                    </div>

                    <!-- Delivery -->
                    <div class="delivery-box">
                        <div class="delivery-row"><i class="bi bi-truck"></i><span><strong>Free Standard Delivery</strong> —
                                Arrives Apr 3–5, 2026</span></div>
                        <div class="delivery-row"><i class="bi bi-lightning"></i><span><strong>Express (1–2 days)</strong> —
                                Available at checkout</span></div>
                        <div class="delivery-row"><i class="bi bi-bag-check"></i><span><strong>In-Store Pickup</strong> —
                                Ready within 2 hours at Paris flagship</span></div>
                    </div>

                    <!-- Share -->
                    <div style="display:flex;align-items:center;gap:10px;font-size:.75rem;color:var(--muted);">
                        <span>Share:</span>
                        <a href="#" style="color:var(--muted);font-size:1rem;"><i class="bi bi-instagram"></i></a>
                        <a href="#" style="color:var(--muted);font-size:1rem;"><i class="bi bi-pinterest"></i></a>
                        <a href="#" style="color:var(--muted);font-size:1rem;"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" style="color:var(--muted);font-size:1rem;"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TABS -->
    <section id="productTabs">
        <div class="container-fluid px-4">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="switchTab(this,'desc')">Description</button>
                <button class="tab-btn" onclick="switchTab(this,'details')">Details & Care</button>
                <button class="tab-btn" onclick="switchTab(this,'reviews')">Reviews (128)</button>
                <button class="tab-btn" onclick="switchTab(this,'shipping')">Shipping & Returns</button>
            </div>

            <!-- Description -->
            <div class="tab-pane active" id="tab-desc">
                <div class="row">
                    <div class="col-lg-7">
                        <h3
                            style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:300;margin-bottom:1rem;">
                            The Art of the Perfect Blazer</h3>
                        <p style="font-size:.9rem;color:var(--muted);line-height:1.9;margin-bottom:1rem;">Every season,
                            Éclat Paris returns to the blazer — not out of habit, but out of conviction. The 2026 iteration
                            refines the silhouette further: shoulders that sit just off the bone, a back vent deep enough
                            for movement, and lapels that roll with a natural break you can't tailor into cheaper fabric.
                        </p>
                        <p style="font-size:.9rem;color:var(--muted);line-height:1.9;">The silk is sourced exclusively from
                            the Jiangsu province of China, where sericulture traditions stretch back millennia. Each bolt is
                            hand-graded before cutting. The result is a garment that improves with wear — loosening in all
                            the right places, developing a patina that is uniquely yours.</p>
                        <div class="feature-grid">
                            <div class="feature-item"><i class="bi bi-check2"></i> 100% Mulberry Silk, 22mm weight</div>
                            <div class="feature-item"><i class="bi bi-check2"></i> Unstructured, relaxed shoulder</div>
                            <div class="feature-item"><i class="bi bi-check2"></i> Single-button closure</div>
                            <div class="feature-item"><i class="bi bi-check2"></i> Two front welt pockets</div>
                            <div class="feature-item"><i class="bi bi-check2"></i> Fully silk-lined interior</div>
                            <div class="feature-item"><i class="bi bi-check2"></i> Back centre vent, 32cm</div>
                            <div class="feature-item"><i class="bi bi-check2"></i> Mother-of-pearl buttons</div>
                            <div class="feature-item"><i class="bi bi-check2"></i> Handfinished seams</div>
                        </div>
                    </div>
                    <div class="col-lg-4 offset-lg-1 mt-4 mt-lg-0">
                        <img src="https://images.unsplash.com/photo-1485968579580-b6d095142e6e?w=500&q=80"
                            style="width:100%;aspect-ratio:3/4;object-fit:cover;" alt="" />
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="tab-pane" id="tab-details">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h5
                            style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:700;margin-bottom:1rem;">
                            Product Details</h5>
                        <table style="width:100%;font-size:.85rem;">
                            <tr style="border-bottom:1px solid var(--border)">
                                <td style="padding:8px 0;color:var(--muted);width:45%">Material</td>
                                <td style="padding:8px 0;font-weight:500">100% Mulberry Silk</td>
                            </tr>
                            <tr style="border-bottom:1px solid var(--border)">
                                <td style="padding:8px 0;color:var(--muted)">Weight</td>
                                <td style="padding:8px 0;font-weight:500">22mm (heavyweight silk)</td>
                            </tr>
                            <tr style="border-bottom:1px solid var(--border)">
                                <td style="padding:8px 0;color:var(--muted)">Lining</td>
                                <td style="padding:8px 0;font-weight:500">Silk charmeuse</td>
                            </tr>
                            <tr style="border-bottom:1px solid var(--border)">
                                <td style="padding:8px 0;color:var(--muted)">Fit</td>
                                <td style="padding:8px 0;font-weight:500">Relaxed / Oversized</td>
                            </tr>
                            <tr style="border-bottom:1px solid var(--border)">
                                <td style="padding:8px 0;color:var(--muted)">Country of Origin</td>
                                <td style="padding:8px 0;font-weight:500">Made in France</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;color:var(--muted)">Item Number</td>
                                <td style="padding:8px 0;font-weight:500">EP-BL-2026-IV-M</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5
                            style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:700;margin-bottom:1rem;">
                            Care Instructions</h5>
                        <div class="feature-item mb-2"><i class="bi bi-droplet-half"></i> Dry clean only</div>
                        <div class="feature-item mb-2"><i class="bi bi-wind"></i> Do not tumble dry</div>
                        <div class="feature-item mb-2"><i class="bi bi-thermometer-low"></i> Iron on low — silk setting only
                        </div>
                        <div class="feature-item mb-2"><i class="bi bi-x-circle"></i> Do not bleach</div>
                        <div class="feature-item mb-2"><i class="bi bi-bag"></i> Store on a padded hanger</div>
                        <div
                            style="background:rgba(200,147,90,.08);border-left:3px solid var(--accent);padding:10px 14px;margin-top:1rem;font-size:.8rem;color:var(--muted);">
                            <i class="bi bi-info-circle me-1" style="color:var(--accent)"></i>
                            Complimentary dry-cleaning service included with first clean when purchased at a Luxé flagship.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="tab-pane" id="tab-reviews" id="reviews">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div style="text-align:center;padding:1.5rem;background:var(--cream);">
                            <div
                                style="font-family:'Cormorant Garamond',serif;font-size:4rem;font-weight:700;color:var(--dark);line-height:1;">
                                4.6</div>
                            <div class="stars" style="font-size:1rem;margin:.5rem 0;"><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
                            <div style="font-size:.78rem;color:var(--muted);">Based on 128 reviews</div>
                            <div style="margin-top:1.2rem;font-size:.78rem;">
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;"><span
                                        style="width:24px;text-align:right;color:var(--muted);">5</span>
                                    <div style="flex:1;height:6px;background:var(--border);">
                                        <div style="width:72%;height:100%;background:var(--accent);"></div>
                                    </div><span style="width:30px;color:var(--muted);">72%</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;"><span
                                        style="width:24px;text-align:right;color:var(--muted);">4</span>
                                    <div style="flex:1;height:6px;background:var(--border);">
                                        <div style="width:18%;height:100%;background:var(--accent);"></div>
                                    </div><span style="width:30px;color:var(--muted);">18%</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;"><span
                                        style="width:24px;text-align:right;color:var(--muted);">3</span>
                                    <div style="flex:1;height:6px;background:var(--border);">
                                        <div style="width:7%;height:100%;background:var(--accent);"></div>
                                    </div><span style="width:30px;color:var(--muted);">7%</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;"><span
                                        style="width:24px;text-align:right;color:var(--muted);">2</span>
                                    <div style="flex:1;height:6px;background:var(--border);">
                                        <div style="width:2%;height:100%;background:var(--accent);"></div>
                                    </div><span style="width:30px;color:var(--muted);">2%</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;"><span
                                        style="width:24px;text-align:right;color:var(--muted);">1</span>
                                    <div style="flex:1;height:6px;background:var(--border);">
                                        <div style="width:1%;height:100%;background:var(--accent);"></div>
                                    </div><span style="width:30px;color:var(--muted);">1%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="review-card">
                            <div class="review-meta"><img class="review-avatar"
                                    src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=100&q=80" alt="" />
                                <div>
                                    <div class="review-name">Sophie L.</div>
                                    <div class="review-date">March 18, 2026</div>
                                </div>
                                <div class="stars ms-auto"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i></div>
                            </div>
                            <div class="review-verified mb-1"><i class="bi bi-patch-check-fill me-1"></i>Verified Purchase
                            </div>
                            <p class="review-text">"Absolutely stunning. The silk quality is extraordinary — it has a weight
                                and drape unlike anything I own. I've been wearing it over everything from jeans to evening
                                dresses. Already ordering the navy."</p>
                        </div>
                        <div class="review-card">
                            <div class="review-meta"><img class="review-avatar"
                                    src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&q=80" alt="" />
                                <div>
                                    <div class="review-name">Amara O.</div>
                                    <div class="review-date">March 5, 2026</div>
                                </div>
                                <div class="stars ms-auto"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star"></i></div>
                            </div>
                            <div class="review-verified mb-1"><i class="bi bi-patch-check-fill me-1"></i>Verified Purchase
                            </div>
                            <p class="review-text">"The oversized cut is genuinely flattering. I was worried it might feel
                                boxy but the silk hangs in a way that creates a beautiful silhouette. Sizing was true to the
                                guide."</p>
                        </div>
                        <div class="review-card">
                            <div class="review-meta"><img class="review-avatar"
                                    src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&q=80" alt="" />
                                <div>
                                    <div class="review-name">Elena V.</div>
                                    <div class="review-date">Feb 28, 2026</div>
                                </div>
                                <div class="stars ms-auto"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                        class="bi bi-star-half"></i></div>
                            </div>
                            <div class="review-verified mb-1"><i class="bi bi-patch-check-fill me-1"></i>Verified Purchase
                            </div>
                            <p class="review-text">"Luxurious in every sense. The mother-of-pearl button is a beautiful
                                detail. My only minor note is the ivory shade runs very slightly warm — closer to champagne
                                than cool white. Still spectacular."</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping -->
            <div class="tab-pane" id="tab-shipping">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h5
                            style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:700;margin-bottom:1rem;">
                            Delivery Options</h5>
                        <div class="feature-item mb-3"><i class="bi bi-truck"></i><span><strong>Standard (3–5 days)</strong>
                                — Free on orders over $150</span></div>
                        <div class="feature-item mb-3"><i class="bi bi-lightning"></i><span><strong>Express (1–2
                                    days)</strong> — $18.00, or free for Elite members</span></div>
                        <div class="feature-item mb-3"><i class="bi bi-bag-check"></i><span><strong>In-Store Pickup</strong>
                                — Free, ready within 2 hours</span></div>
                        <div class="feature-item"><i class="bi bi-globe"></i><span><strong>International</strong> — 5–10
                                business days, rates at checkout</span></div>
                    </div>
                    <div class="col-md-6">
                        <h5
                            style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:700;margin-bottom:1rem;">
                            Returns Policy</h5>
                        <div class="feature-item mb-3"><i class="bi bi-arrow-return-left"></i><span><strong>30-day
                                    returns</strong> on all full-price items</span></div>
                        <div class="feature-item mb-3"><i class="bi bi-box-seam"></i><span>Must be unworn and in original
                                packaging</span></div>
                        <div class="feature-item mb-3"><i class="bi bi-currency-dollar"></i><span>Refund processed within
                                5–7 business days</span></div>
                        <div class="feature-item"><i class="bi bi-arrow-left-right"></i><span>Free exchanges on
                                size/colour</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- RELATED PRODUCTS -->
    <section id="relatedProducts">
        <div class="container-fluid px-4">
            <div class="row mb-4 align-items-end">
                <div class="col">
                    <div
                        style="font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;color:var(--accent);font-weight:600;">
                        Complete the Look</div>
                    <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:300;">
                        You May Also <strong>Like</strong></h2>
                </div>
                <div class="col-auto"><a href="{{ url('/shop/products') }}"
                        style="font-size:.8rem;letter-spacing:.08em;text-transform:uppercase;font-weight:600;color:var(--accent);text-decoration:none;">View
                        All <i class="bi bi-arrow-right"></i></a></div>
            </div>
            <div class="row g-4">
                @foreach($relatedProducts as $item)
                    @php
                        $image = $item->image
                            ? asset('storage/' . $item->image)
                            : asset('noimage.png');
                    @endphp

                    <div class="col-6 col-md-4 col-lg-3 ">
                        <div class="prod-card">
                            <div class="prod-img-wrap">
                                <img src="{{ $image }}" alt="{{ $item->name }}" loading="lazy" />
                            </div>

                            <div class="prod-actions">
                                <button class="act-btn"><i class="bi bi-heart"></i></button>
                                <button class="act-btn">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn view-detail-btn" title="View Detail" data-code="{{ $item->code }}">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </button>


                            </div>

                            <div class="prod-body">
                                <div class="prod-brand-sm">
                                    {{ $item->brand->name ?? 'Brand' }}
                                </div>

                                <div class="prod-name-sm">
                                    {{ $item->name }}
                                </div>

                                <div class="prod-price-sm">
                                    ${{ number_format($item->price, 2) }}
                                </div>

                                <button class="btn-add-sm" onclick="showToast('Added to bag!','bi-bag-check')">
                                    <i class="bi bi-bag-plus"></i> Add to Bag
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <button class="lightbox-close"><i class="bi bi-x-lg"></i></button>
        <img id="lightboxImg" src="" alt="" />
    </div>

    <!-- Toast -->
    <div class="luxe-toast" id="luxeToast"><i class="bi" id="toastIcon"></i><span id="toastMsg"></span></div>



@endsection
@push('scripts')
    <script>
        let qty = 1,
            wished = false;


        function changeQty(d) {
            qty = Math.max(1, qty + d);
            document.getElementById('prodQty').textContent = qty;
        }

        function addToCart() {
            showToast('Oversized Silk Blazer added to bag!', 'bi-bag-check');
        }

        function toggleWish() {
            wished = !wished;
            const b = document.getElementById('wishBtn');
            b.classList.toggle('active', wished);
            b.innerHTML = wished ? '<i class="bi bi-heart-fill"></i>' : '<i class="bi bi-heart"></i>';
            showToast(wished ? 'Added to Wishlist' : 'Removed from Wishlist', wished ? 'bi-heart-fill' : 'bi-heart');
        }

        function selectColor(el, name) {
            document.querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('colorLabel').textContent = name;
        }

        function selectSize(el, name) {
            document.querySelectorAll('.size-btn:not(.unavailable)').forEach(s => s.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('sizeLabel').textContent = name;
        }

        function switchImg(el, src) {
            document.getElementById('mainImg').src = src;
            document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
        }

        function switchTab(btn, id) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + id).classList.add('active');
        }

        function openLightbox() {
            const lb = document.getElementById('lightbox');
            document.getElementById('lightboxImg').src = document.getElementById('mainImg').src;
            lb.classList.add('open');
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('open');
        }

        function handleLogin() {
            showToast('Signed in successfully!', 'bi-person-check');
        }

        function showToast(msg, icon) {
            const t = document.getElementById('luxeToast');
            document.getElementById('toastMsg').textContent = msg;
            document.getElementById('toastIcon').className = 'bi ' + icon;
            t.style.display = 'flex';
            clearTimeout(window._tt);
            window._tt = setTimeout(() => t.style.display = 'none', 3000);
        }
        window.addEventListener('scroll', () => {
            document.getElementById('mainNav').style.boxShadow = window.scrollY > 30 ? '0 4px 30px rgba(26,20,16,.12)' : '0 2px 20px rgba(26,20,16,.06)';
        });
        document.getElementById('sizeLabel').textContent = 'M';

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.view-detail-btn');
            if (!btn) return;

            // console.log(btn.dataset);

            const code = btn.dataset.code;
            // console.log(code);

            window.open(`/shop/products/${code}`, '_blank');
        });



    </script>
@endpush
