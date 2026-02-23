@extends('frontend.portfolio.layouts.app')
@section('title', __('messages.portfolio'))

@section('content')
    <section id="hero">
        <div class="container-xl">
            <div class="row align-items-center g-5">

                <!-- LEFT INFO PANEL -->
                <div class="col-12 col-lg-5 d-none d-lg-flex justify-content-lg-start">
                    <div class="hero-eyebrow"></div>
                    <div class="profile-wrap">
                        <div class="pe-lg-4">

                            <!-- Badge -->
                            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill border border-info-subtle text-info small mb-4">
                                <span class="fs-6">●</span>
                                <span>Free to start · No credit card</span>
                            </div>

                            <!-- Title -->
                            <h2 class="fw-bold lh-sm mb-3">
                                Launch your <br>
                                <span class="text-info">store</span> <br>
                                in minutes.
                            </h2>

                            <!-- Subtitle -->
                            <p class="text-secondary mb-4">
                                Three quick steps and you're live. Everything you need to start selling — no code, no friction.
                            </p>

                            <!-- Perks -->
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:40px;height:40px;">⚡</div>
                                    <div>
                                        <div class="fw-semibold small">Live in under 5 minutes</div>
                                        <div class="text-secondary small">Your storefront is ready the second you complete setup.</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:40px;height:40px;">💳</div>
                                    <div>
                                        <div class="fw-semibold small">No credit card needed</div>
                                        <div class="text-secondary small">Start free, upgrade when you're ready to scale.</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:40px;height:40px;">🔒</div>
                                    <div>
                                        <div class="fw-semibold small">Bank-grade security</div>
                                        <div class="text-secondary small">End-to-end encrypted. Your data is always yours.</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:40px;height:40px;">📞</div>
                                    <div>
                                        <div class="fw-semibold small">Human support 24/7</div>
                                        <div class="text-secondary small">Real people ready to help — not a chatbot.</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- RIGHT FORM PANEL -->
                <div class="col-12 col-lg-7">

                    <!-- PROGRESS STEPS -->
                    <div class="d-flex align-items-center justify-content-between mb-4" id="stepTrack">
                        <!-- Step 1 -->
                        <div class="text-center flex-fill">
                            <div class="rounded-circle bg-info text-white fw-semibold d-flex align-items-center justify-content-center mx-auto"
                                style="width:34px;height:34px;" id="sc1">1</div>
                            <div class="small text-info mt-1" id="sl1">Personal</div>
                        </div>
                        <div class="flex-fill border-top border-secondary mx-2" id="ln1"></div>

                        <!-- Step 2 -->
                        <div class="text-center flex-fill">
                            <div class="rounded-circle border border-secondary text-secondary fw-semibold d-flex align-items-center justify-content-center mx-auto"
                                style="width:34px;height:34px;" id="sc2">2</div>
                            <div class="small text-secondary mt-1" id="sl2">Password</div>
                        </div>
                        <div class="flex-fill border-top border-secondary mx-2" id="ln2"></div>

                        <!-- Step 3 -->
                        <div class="text-center flex-fill">
                            <div class="rounded-circle border border-secondary text-secondary fw-semibold d-flex align-items-center justify-content-center mx-auto"
                                style="width:34px;height:34px;" id="sc3">3</div>
                            <div class="small text-secondary mt-1" id="sl3">Store Info</div>
                        </div>
                    </div>

                    <!-- FORM -->
                    <form>

                        <!-- STEP 1 -->
                        <div id="step1" class="form-step">
                            <div class="text-uppercase small text-info fw-semibold mb-2">Step 1 of 3</div>
                            <h1 class="fw-bold mb-1">Personal Info</h1>
                            <p class="text-secondary mb-4">Let's start with the basics — who are you?</p>

                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">First name</label>
                                    <input type="text" name="first_name" class="form-control" placeholder="Jane" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Last name</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Doe">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Email address</label>
                                <input type="email" name="email" class="form-control" placeholder="you@example.com">
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Country</label>
                                <select name="country" class="form-select">
                                    <option selected disabled>Select your country</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="United States">United States</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                </select>
                            </div>

                            <button type="button" class="btn btn-info text-white w-100 mt-4" onclick="nextStep(1)">
                                Continue to Security <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>

                        <!-- STEP 2 -->
                        <div id="step2" class="form-step d-none">
                            <div class="text-uppercase small text-info fw-semibold mb-2">Step 2 of 3</div>
                            <h1 class="fw-bold mb-1">Secure Your Account</h1>
                            <p class="text-secondary mb-4">Create a strong password to protect your store.</p>

                            <label class="form-label">Password</label>
                            <input type="password" id="pwInput" class="form-control mb-2"
                                placeholder="Minimum 8 characters" oninput="checkPw(this.value)">

                            <!-- Strength bars -->
                            <div class="d-flex gap-1 mb-1" style="height:4px;">
                                <div class="flex-fill rounded" id="sb1" style="background:#dee2e6;transition:background .3s;"></div>
                                <div class="flex-fill rounded" id="sb2" style="background:#dee2e6;transition:background .3s;"></div>
                                <div class="flex-fill rounded" id="sb3" style="background:#dee2e6;transition:background .3s;"></div>
                                <div class="flex-fill rounded" id="sb4" style="background:#dee2e6;transition:background .3s;"></div>
                            </div>
                            <div class="small mb-3 text-secondary" id="pwLabel">Enter a password</div>

                            <label class="form-label mt-2">Confirm password</label>
                            <input type="password" class="form-control" id="pwConfirm">

                            <div class="d-flex gap-2 mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <button type="button" class="btn btn-info text-white flex-fill" onclick="nextStep(2)">
                                    Continue to Store <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- STEP 3 -->
                        <div id="step3" class="form-step d-none">
                            <div class="text-uppercase small text-info fw-semibold mb-2">Step 3 of 3</div>
                            <h1 class="fw-bold mb-1">Your Store</h1>
                            <p class="text-secondary mb-4">Almost there! Tell us about what you're selling.</p>

                            <label class="form-label">Store name</label>
                            <input type="text" id="storeName" class="form-control mb-3" placeholder="My Awesome Store">

                            <label class="form-label">Category</label>
                            <select id="storeCategory" class="form-select mb-3">
                                <option value="">Select a category</option>
                                <option>Fashion & Apparel</option>
                                <option>Electronics</option>
                                <option>Home & Living</option>
                            </select>

                            <div class="form-check mb-3">
                                <input type="checkbox" id="terms" class="form-check-input">
                                <label class="form-check-label small text-secondary" for="terms">
                                    I agree to Terms & Privacy Policy
                                </label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <button type="button" class="btn btn-info text-white flex-fill" onclick="goSuccess()">
                                    Launch My Store 🚀
                                </button>
                            </div>
                        </div>

                        <!-- SUCCESS -->
                        <div id="step-success" class="d-none text-center py-4">
                            <div class="display-5 mb-3">🎉</div>
                            <h2 class="fw-bold">You're live!</h2>
                            <p class="text-secondary">Your store is being set up.</p>
                            <a href="/" class="btn btn-info text-white w-100">
                                Go to Dashboard <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
   let currentStep = 1;

    window.showStep = function (step) {
        document.querySelectorAll('.form-step').forEach(el => el.classList.add('d-none'));
        document.getElementById('step' + step).classList.remove('d-none');

        for (let i = 1; i <= 3; i++) {
            const circle = document.getElementById('sc' + i);
            const label  = document.getElementById('sl' + i);
            const line   = i <= 2 ? document.getElementById('ln' + i) : null;

            if (i <= step) {
                // Active or completed
                circle.classList.add('bg-info', 'text-white');
                circle.classList.remove('border', 'border-secondary', 'text-secondary');
                label.classList.add('text-info');
                label.classList.remove('text-secondary');
                if (line) {
                    line.classList.add('border-info');
                    line.classList.remove('border-secondary');
                }
            } else {
                // Upcoming
                circle.classList.remove('bg-info', 'text-white');
                circle.classList.add('border', 'border-secondary', 'text-secondary');
                label.classList.remove('text-info');
                label.classList.add('text-secondary');
                if (line) {
                    line.classList.remove('border-info');
                    line.classList.add('border-secondary');
                }
            }
        }
    };

    window.nextStep = function (step) {
        const inputs = document.querySelectorAll('#step' + step + ' input, #step' + step + ' select');
        for (let input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                return;
            }
        }
        if (currentStep < 3) {
            currentStep++;
            window.showStep(currentStep);
        }
    };

    window.prevStep = function () {
        if (currentStep > 1) {
            currentStep--;
            window.showStep(currentStep);
        }
    };

    window.goSuccess = function () {
        document.querySelectorAll('.form-step').forEach(el => el.classList.add('d-none'));
        document.getElementById('step-success').classList.remove('d-none');
        document.getElementById('stepTrack').classList.add('d-none');
    };

    window.checkPw = function (v) {
        const bars   = [1, 2, 3, 4].map(i => document.getElementById('sb' + i));
        const lbl    = document.getElementById('pwLabel');
        const empty  = '#dee2e6';
        const colors = ['', '#f43f5e', '#f59e0b', '#f59e0b', '#10b981'];
        const labels = ['', 'Weak', 'Fair', 'Good', 'Strong ✓'];

        bars.forEach(b => b.style.background = empty);

        if (!v) { lbl.textContent = 'Enter a password'; lbl.style.color = ''; return; }

        let s = 0;
        if (v.length >= 8)                        s++;
        if (v.length >= 12)                       s++;
        if (/[A-Z]/.test(v) && /[0-9]/.test(v))  s++;
        if (/[^A-Za-z0-9]/.test(v))               s++;

        for (let i = 0; i < s; i++) bars[i].style.background = colors[s];
        lbl.textContent = labels[s];
        lbl.style.color = colors[s];
    };

    // Init on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        window.showStep(currentStep);
    });

</script>
@endpush
