@extends('frontend-v2.app')

@section('title', 'Contact Us')

@section('content')


    <!-- ── PAGE HERO ── -->
    <div class="page-hero">
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    {{-- <li class="breadcrumb-item"><a href="#">Women</a></li> --}}
                    <li class="breadcrumb-item active">Contact Us</li>
                </ol>
            </nav>
            <div class="page-hero-eyebrow">Curated Collection</div>
            <h1 class="page-hero-title">Contact <em>Us</em></h1>
        </div>
    </div>
    <!-- ============================================================
             INFO CARDS
        ============================================================ -->
    <section id="infoCards">
        <div class="container-fluid px-4">
            <div class="row g-3">
                <div class="col-xl-3 col-md-6">
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-telephone"></i></div>
                        <div>
                            <div class="info-card-title">Call Us</div>
                            <div class="info-card-value">+1 (800) 589-LUXE</div>
                            <div class="info-card-sub">Mon – Fri, 9am – 7pm EST</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-envelope"></i></div>
                        <div>
                            <div class="info-card-title">Email Us</div>
                            <div class="info-card-value">hello@luxe.com</div>
                            <div class="info-card-sub">We reply within 4 business hours</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <div class="info-card-title">Visit Us</div>
                            <div class="info-card-value">14 Blvd Saint-Germain</div>
                            <div class="info-card-sub">Paris, 75006, France</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-chat-dots"></i></div>
                        <div>
                            <div class="info-card-title">Live Chat</div>
                            <div class="info-card-value">Available Now</div>
                            <div class="info-card-sub">Average wait: &lt; 2 minutes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="contactMain">
        <div class="container-fluid px-4">
            <div class="row">

                <!-- LEFT: Info + Hours -->
                <div class="col-lg-4 contact-panel-left">
                    <div class="section-label">About Our Stores</div>
                    <h2 class="section-title">We'd Love to <strong>Hear From You</strong></h2>
                    <div class="title-rule"></div>
                    <p class="contact-description">Our client experience team combines deep product knowledge with genuine
                        passion for luxury fashion. Don't hesitate to reach out — every conversation matters to us.</p>

                    <!-- Store Hours -->
                    <div style="margin-bottom:2rem">
                        <div
                            style="font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;color:var(--accent);font-weight:600;margin-bottom:.8rem;">
                            <i class="bi bi-clock me-1"></i>Store Hours
                        </div>
                        <table class="hours-table">
                            <tr>
                                <td>Monday</td>
                                <td>10:00 – 20:00</td>
                            </tr>
                            <tr>
                                <td>Tuesday</td>
                                <td>10:00 – 20:00</td>
                            </tr>
                            <tr>
                                <td>Wednesday</td>
                                <td>10:00 – 20:00</td>
                            </tr>
                            <tr class="today">
                                <td>Thursday <span
                                        style="font-size:.65rem;background:var(--accent);color:#fff;padding:1px 6px;margin-left:4px;">Today</span>
                                </td>
                                <td>10:00 – 20:00</td>
                            </tr>
                            <tr>
                                <td>Friday</td>
                                <td>10:00 – 21:00</td>
                            </tr>
                            <tr>
                                <td>Saturday</td>
                                <td>09:00 – 21:00</td>
                            </tr>
                            <tr>
                                <td>Sunday</td>
                                <td>12:00 – 18:00</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Quick Actions -->
                    <div
                        style="font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;color:var(--accent);font-weight:600;margin-bottom:.8rem;">
                        <i class="bi bi-lightning me-1"></i>Quick Actions
                    </div>
                    <div class="d-flex flex-column gap-2" style="margin-bottom:2rem">
                        <a href="#"
                            style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:var(--white);border:1px solid var(--border);text-decoration:none;color:var(--dark);font-size:.82rem;transition:border-color .25s,color .25s;"
                            onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--dark)'">
                            <i class="bi bi-truck" style="color:var(--accent)"></i> Track My Order
                            <i class="bi bi-arrow-right ms-auto" style="font-size:.75rem"></i>
                        </a>
                        <a href="#"
                            style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:var(--white);border:1px solid var(--border);text-decoration:none;color:var(--dark);font-size:.82rem;transition:border-color .25s,color .25s;"
                            onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--dark)'">
                            <i class="bi bi-arrow-return-left" style="color:var(--accent)"></i> Start a Return
                            <i class="bi bi-arrow-right ms-auto" style="font-size:.75rem"></i>
                        </a>
                        <a href="#"
                            style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:var(--white);border:1px solid var(--border);text-decoration:none;color:var(--dark);font-size:.82rem;transition:border-color .25s,color .25s;"
                            onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--dark)'">
                            <i class="bi bi-shield-check" style="color:var(--accent)"></i> Authenticity Check
                            <i class="bi bi-arrow-right ms-auto" style="font-size:.75rem"></i>
                        </a>
                        <a href="#"
                            style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:var(--white);border:1px solid var(--border);text-decoration:none;color:var(--dark);font-size:.82rem;transition:border-color .25s,color .25s;"
                            onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--dark)'">
                            <i class="bi bi-person-badge" style="color:var(--accent)"></i> Book a Stylist
                            <i class="bi bi-arrow-right ms-auto" style="font-size:.75rem"></i>
                        </a>
                    </div>


                </div>

                <!-- RIGHT: Contact Form -->
                <div class="col-lg-8">
                    <div class="contact-form-wrap">
                        <div style="margin-bottom:1.8rem">
                            <div class="section-label">Send a Message</div>
                            <h3 class="section-title" style="font-size:1.8rem">How Can We <strong>Help?</strong></h3>
                        </div>

                        <!-- Topic selector -->
                        <div class="mb-4">
                            <label class="form-label">What's Your Enquiry About?</label>
                            {{-- <div class="topic-grid" id="topicGrid">
                                <button class="topic-pill active" onclick="selectTopic(this)">Order Issue</button>
                                <button class="topic-pill" onclick="selectTopic(this)">Returns & Exchanges</button>
                                <button class="topic-pill" onclick="selectTopic(this)">Product Question</button>
                                <button class="topic-pill" onclick="selectTopic(this)">Styling Advice</button>
                                <button class="topic-pill" onclick="selectTopic(this)">Wholesale / Press</button>
                                <button class="topic-pill" onclick="selectTopic(this)">Other</button>
                            </div> --}}
                        </div>

                        <form id="contactForm" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="firstName" placeholder="e.g. Sophie"
                                        required />
                                    <div class="invalid-msg">Please enter your first name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="e.g. Laurent"
                                        required />
                                    <div class="invalid-msg">Please enter your last name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" placeholder="hello@example.com"
                                        required />
                                    <div class="invalid-msg">Please enter a valid email address.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" placeholder="+1 (000) 000-0000" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Order Number</label>
                                    <input type="text" class="form-control" placeholder="e.g. LX-2026-00842" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Preferred Contact</label>
                                    <select class="form-select">
                                        <option>Email</option>
                                        <option>Phone Call</option>
                                        <option>WhatsApp</option>
                                        <option>Any</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Your Message *</label>
                                    <textarea class="form-control" id="message"
                                        placeholder="Tell us how we can help — the more detail, the faster we can assist you…"
                                        required></textarea>
                                    <div class="invalid-msg">Please write your message.</div>
                                </div>

                                <!-- File upload -->
                                <div class="col-12">
                                    <label class="form-label">Attach Files <span
                                            style="color:var(--muted);font-weight:400;text-transform:none;letter-spacing:0">(optional
                                            — photos help us assist faster)</span></label>
                                    <div class="file-drop" id="fileDrop"
                                        onclick="document.getElementById('fileInput').click()">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <div>Drag & drop files here, or <strong style="color:var(--accent)">browse</strong>
                                        </div>
                                        <div style="font-size:.7rem;margin-top:4px;color:#bbb">JPG, PNG, PDF up to 10 MB
                                            each</div>
                                    </div>
                                    <input type="file" id="fileInput" style="display:none" multiple accept="image/*,.pdf"
                                        onchange="handleFiles(this.files)" />
                                    <div id="fileList" style="margin-top:8px;font-size:.75rem;color:var(--muted)"></div>
                                </div>

                                <!-- Consent -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="consent"
                                            style="border-radius:0;border-color:var(--border)" />
                                        <label class="form-check-label" for="consent"
                                            style="font-size:.8rem;color:var(--muted)">
                                            I agree to the <a href="#" style="color:var(--accent)">Privacy Policy</a> and
                                            consent to Luxé storing my data to respond to this enquiry.
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 d-flex align-items-center gap-4 flex-wrap mt-2">
                                    <button type="button" class="btn-cart" onclick="submitForm()">
                                        Send Message <i class="bi bi-arrow-right"></i>
                                    </button>
                                    <div style="font-size:.75rem;color:var(--muted)">
                                        <i class="bi bi-lock me-1" style="color:var(--accent)"></i>Encrypted &amp; secure
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================================================
         MAP SECTION
    ============================================================ -->
<section id="mapSection">
  <div class="map-placeholder" style="position:relative; overflow:hidden;">

    <!-- REAL MAP (BACKGROUND) -->
    <iframe
      src="https://www.google.com/maps?q=Phnom Penh Cambodia&output=embed"
      style="
        position:absolute;
        top:0; left:0;
        width:100%;
        height:100%;
        border:0;
        filter: grayscale(100%) contrast(1.1) brightness(.9);
        pointer-events:none;
      "
      loading="lazy">
    </iframe>

    <!-- KEEP YOUR GRID OVERLAY -->
    <div class="map-grid"></div>

    <!-- STORE CARD (UNCHANGED STYLE) -->
    <div class="map-store-card">
      <h6>Luxé Phnom Penh — Flagship Store</h6>

      <div class="map-store-row">
        <i class="bi bi-geo-alt-fill"></i>
        <span>Phnom Penh, Cambodia</span>
      </div>

      <div class="map-store-row">
        <i class="bi bi-telephone-fill"></i>
        <span>+855 12 345 678</span>
      </div>

      <div class="map-store-row">
        <i class="bi bi-clock-fill"></i>
        <span>Mon–Sun: 10:00–21:00</span>
      </div>

      <div class="map-store-row">
        <i class="bi bi-car-front-fill"></i>
        <span>Valet parking available</span>
      </div>

      <a href="https://maps.google.com?q=Phnom Penh Cambodia"
         target="_blank"
         class="map-open-btn"
         style="margin-top:.8rem;display:inline-flex">
        <i class="bi bi-map"></i> Get Directions
      </a>
    </div>

    <!-- KEEP YOUR PIN DESIGN -->
    <div class="map-pin-wrap">
      <div class="map-pin"><i class="bi bi-geo-alt-fill"></i></div>

      <div class="map-address">
        Phnom Penh, <span>Cambodia</span>
      </div>

      <div>
        <a href="https://maps.google.com?q=Phnom Penh Cambodia"
           target="_blank"
           class="map-open-btn">
          <i class="bi bi-box-arrow-up-right"></i> Open in Google Maps
        </a>
      </div>
    </div>

  </div>
</section>

@endsection
