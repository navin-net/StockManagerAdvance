@extends('frontend-v2.app')

@section('title', 'About Us | LUXE Cambodia')

@section('content')

    <div class="page-hero">
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Our Story</li>
                </ol>
            </nav>
            <div class="page-hero-eyebrow">The LUXE Philosophy</div>
            <h1 class="page-hero-title">Cambodia's #1 <em>Premium Curator</em></h1>
        </div>
    </div>


    <section class="story-section">
            <div class="container-fluid px-4">
                <div class="row g-5 align-items-center mb-5">
                    <div class="col-lg-5">
                        <div class="story-eyebrow">Our Beginning</div>
                        <h2 class="story-title">Defining the <strong>New Standard of Trust</strong></h2>

                        <div class="story-body">
                            <p>Founded in Phnom Penh, our platform was built to solve a single problem: the difficulty of finding guaranteed quality in a crowded digital market. We believed that our community deserved a shopping experience where "Premium" isn't just a label, but a promise.</p>

                            <p>We rejected the model of mass-selling unverified items. Instead, we built a secure ecosystem focused on 100% genuine products, local warranties, and a seamless shopping journey from the first click to your doorstep.</p>

                            <blockquote class="pull-quote">
                                "We don't just list products; we audit every vendor and inspect every item to ensure your investment is always protected."
                            </blockquote>

                            <p>Today, we are the Kingdom's rising destination for tech, lifestyle, and home essentials—bringing global excellence to Cambodia with the heart of local service.</p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="row g-3">
                            <div class="col-7">
                                <div class="story-img" style="height: 480px;">
                                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=700&h=900&fit=crop"
                                        alt="Modern Retail Operations" />
                                    <div class="story-img-caption">Operations Hub, Est. 2024</div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="story-img mb-3" style="height: 230px;">
                                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=460&fit=crop"
                                        alt="Curated Collection" />
                                </div>
                                <div class="story-img" style="height: 234px;">
                                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=400&h=460&fit=crop"
                                        alt="Quality Curation" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 align-items-center" style="margin-top:20px">
                    <div class="col-lg-6 order-lg-2">
                        <div class="story-eyebrow">The Expert Edit</div>
                        <h2 class="story-title">Sourced Globally, <strong>Verified Locally</strong></h2>
                        <div class="accent-dash"></div>
                        <div class="story-body">
                            <p>Our specialists bridge the gap between international brands and the modern Cambodian home. Every product on our shelf must pass a rigorous inspection—checking performance, durability, and authenticity before it ever goes live.</p>

                            <p>We prioritize <strong>Quality over Quantity</strong>. This strict vetting process is why we maintain one of the highest customer satisfaction rates in the region. When you shop here, you are buying a product that has been "Market-Tested" for local needs.</p>

                            <p>Your peace of mind is our priority. That is why every purchase is backed by our dedicated support team and simplified local return policies.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="story-img" style="height: 440px;">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzOuYrDnpVoanZ4YUXrZEocjjQewMCVzbcLQ&s"
                                alt="Logistics and Quality Control" />
                            <div class="story-img-caption">Quality Inspection Hub, 2026</div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <section class="values-section">
        <div class="values-bg"></div>
        <div class="container-fluid px-4" style="position:relative;z-index:1">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="values-eyebrow">Our Core Values</div>
                    <h2 class="values-title">Local Heart, <strong>Global Standards</strong></h2>
                    <p style="font-size:.88rem;color:var(--dark);line-height:1.7;max-width:320px">
                        Our principles guide every partnership we form and every order we ship—ensuring honesty and transparency in the Kingdom's digital market.
                    </p>
                </div>
                <div class="col-lg-8">
                    <div class="row g-4" id="valuesGrid">
                        {{-- <div class="col-md-6">
                            <div class="value-item mb-4">
                                <h4 class="fw-bold"><i class="bi bi-shield-lock me-2"></i> Secure Shopping</h4>
                                <p class="text-muted small">Encryption and data privacy are at our core, ensuring every transaction is safe.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="value-item mb-4">
                                <h4 class="fw-bold"><i class="bi bi-truck me-2"></i> Reliable Logistics</h4>
                                <p class="text-muted small">Real-time tracking and careful handling so your items arrive exactly as expected.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="value-item mb-4">
                                <h4 class="fw-bold"><i class="bi bi-award me-2"></i> Authentic Products</h4>
                                <p class="text-muted small">We work directly with brands and authorized distributors to eliminate fakes.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="value-item mb-4">
                                <h4 class="fw-bold"><i class="bi bi-headset me-2"></i> 24/7 Support</h4>
                                <p class="text-muted small">Our local team is always ready to assist you via chat, phone, or email.</p>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sustain-section">
        <div class="container-fluid px-4">
            <div class="story-eyebrow">Our Commitment</div>
            <h2 class="story-title">Sustainable <strong>Growth</strong></h2>
            <div class="accent-dash"></div>
            <p style="font-size:.9rem;color:var(--muted);max-width:540px;line-height:1.7">We believe in a cleaner future for Cambodia. We are optimizing our delivery routes to reduce emissions and moving toward biodegradable packaging for all local orders.</p>
            <div class="sustain-strip">
                <div class="sustain-item">
                    <i class="sustain-icon bi bi-shield-check"></i>
                    <div class="sustain-title">Strict Verification</div>
                    <div class="sustain-desc">A zero-tolerance policy for replicas. Verified genuine or a full refund.</div>
                </div>
                <div class="sustain-item">
                    <i class="sustain-icon bi bi-box-seam"></i>
                    <div class="sustain-title">Eco-Packaging</div>
                    <div class="sustain-desc">Reducing plastic waste through sustainable boxing and recycled materials.</div>
                </div>
                <div class="sustain-item">
                    <i class="sustain-icon bi bi-lightning-charge"></i>
                    <div class="sustain-title">Fast Delivery</div>
                    <div class="sustain-desc">Optimized local shipping to get your items home faster and more efficiently.</div>
                </div>
                <div class="sustain-item">
                    <i class="sustain-icon bi bi-heart-pulse"></i>
                    <div class="sustain-title">Built to Last</div>
                    <div class="sustain-desc">We curate items built for durability, reducing the environmental impact of disposables.</div>
                </div>
            </div>
        </div>
    </section>

    <div class="about-cta">
        <h2 class="cta-title">Shop With <em>Confidence</em></h2>
        <p class="cta-sub">Join thousands of happy shoppers across the Kingdom.</p>
        <div class="cta-btns">
            <a href="/shop" class="btn-cta-primary"><i class="bi bi-cart3"></i> Browse Collections</a>
            <a href="/contact" class="btn-cta-outline">Contact Support</a>
        </div>
    </div>


@endsection
