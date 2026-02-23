@extends('frontend.portfolio.layouts.app')
@section('title', __('messages.register'))
@section('content')
@push('style')
<style>

        .registration-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .registration-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            max-width: 1200px;
            width: 100%;
            align-items: center;
        }

        /* Form Section */
        .form-section {
            background: white;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Progress Bar */
        .progress-container {
            margin-bottom: 40px;
        }

        .step-indicator {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .step-counter {
            font-size: 12px;
            font-weight: 600;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .progress-bar-wrapper {
            width: 100%;
            height: 3px;
            background-color: var(--border);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background-color: var(--accent);
            width: 20%;
            transition: width 0.3s ease;
        }

        /* Form Content */
        .form-content {
            position: relative;
        }

        .form-step {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .form-step.active {
            display: block;
        }

        .form-step:not(.active) {
            display: none;
        }

        .step-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--primary);
        }

        .step-subtitle {
            font-size: 14px;
            color: var(--secondary);
            margin-bottom: 32px;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 24px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s ease;
            background-color: white;
            color: var(--primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .form-control::placeholder {
            color: #bbb;
        }

        /* Password Input Wrapper */
        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            font-size: 14px;
            padding: 0;
            transition: color 0.2s ease;
        }

        .toggle-password:hover {
            color: var(--accent);
        }

        /* Validation Messages */
        .validation-message {
            display: block;
            font-size: 12px;
            color: var(--danger);
            margin-top: 4px;
        }

        .validation-success {
            display: block;
            font-size: 12px;
            color: var(--success);
            margin-top: 4px;
        }

        /* Password Strength */
        .strength-container {
            margin-top: 16px;
            margin-bottom: 16px;
        }

        .strength-meter {
            width: 100%;
            height: 4px;
            background-color: var(--border);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-text {
            font-size: 12px;
            font-weight: 600;
            display: block;
            color: var(--danger);
        }

        /* Requirements List */
        .requirements {
            background-color: #f9f9f9;
            padding: 12px;
            border-radius: 6px;
            margin-top: 12px;
        }

        .requirement {
            font-size: 12px;
            color: var(--secondary);
            padding: 4px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .requirement i {
            font-size: 6px;
            opacity: 0.5;
        }

        .requirement.met {
            color: var(--success);
        }

        .requirement.met i {
            opacity: 1;
        }

        /* Verification Codes */
        .verification-codes {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px;
            margin-bottom: 24px;
        }

        .verification-input {
            width: 100%;
            height: 50px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            border: 1px solid var(--border);
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .verification-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        /* Social Login */
        .social-login-divider {
            text-align: center;
            margin: 32px 0;
            position: relative;
            font-size: 12px;
            color: var(--secondary);
        }

        .social-login-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: var(--border);
            z-index: 0;
        }

        .social-login-divider span {
            position: relative;
            background-color: white;
            padding: 0 12px;
            z-index: 1;
        }

        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }

        .social-btn {
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background-color: white;
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .social-btn:hover {
            border-color: var(--accent);
            background-color: #f9f9f9;
        }


        /* Buttons */
        .btn-continue,
        .btn-back {
            width: 100%;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-continue {
            background-color: var(--accent);
            color: white;
            margin-bottom: 20px;
        }

        .btn-continue:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.25);
        }

        .btn-continue:active {
            transform: translateY(0);
        }

        .btn-back {
            background-color: var(--border);
            color: var(--primary);
            flex: 1;
        }

        .btn-back:hover {
            background-color: #d4d4d4;
        }

        /* Step Navigation */
        .step-navigation {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        /* Terms & Newsletter */
        .terms-check,
        .newsletter-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 16px;
            font-size: 13px;
            color: var(--secondary);
        }

        .terms-check input,
        .newsletter-check input {
            margin-top: 2px;
            cursor: pointer;
            width: 16px;
            height: 16px;
            min-width: 16px;
        }

        .terms-check label,
        .newsletter-check label {
            cursor: pointer;
            margin: 0;
        }

        .terms-check a,
        .newsletter-check a {
            color: var(--accent);
            text-decoration: none;
        }

        .terms-check a:hover {
            text-decoration: underline;
        }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Login Link & Resend */
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--secondary);
        }

        .login-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .resend-code {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }

        .resend-btn {
            background: none;
            border: none;
            color: var(--accent);
            cursor: pointer;
            font-weight: 600;
            padding: 0;
            text-decoration: underline;
        }

        .resend-btn:hover {
            opacity: 0.8;
        }

        #resendTimer {
            display: block;
            color: var(--secondary);
            margin-top: 4px;
        }

        /* Benefits Section */
        .benefits-section {
            display: flex;
            align-items: center;
        }

        .benefits-content {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .benefits-content h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 32px;
            color: var(--primary);
        }

        .benefit-item {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .benefit-item:last-child {
            margin-bottom: 0;
        }

        .benefit-icon {
            min-width: 48px;
            width: 48px;
            height: 48px;
            background-color: #e8f0ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 20px;
        }

        .benefit-text h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--primary);
        }

        .benefit-text p {
            font-size: 12px;
            color: var(--secondary);
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .registration-wrapper {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .form-section {
                padding: 40px;
            }

            .benefits-section {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .form-section {
                padding: 24px;
            }

            .step-title {
                font-size: 22px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .verification-codes {
                grid-template-columns: repeat(3, 1fr);
            }

            .verification-input {
                height: 40px;
                font-size: 16px;
            }

            .social-buttons {
                grid-template-columns: 1fr;
            }
        }

</style>
@endpush

    <section id="hero">
        <div class="hero-inner">
            <div class="registration-container">
                <div class="registration-wrapper">
                    <!-- Left Section: Form -->
                    <div class="form-section">
                        <!-- Progress Bar -->
                        <div class="progress-container">
                            <div class="step-indicator">
                                <div class="step-counter">Step <span id="currentStep">1</span> of 5</div>
                                <div class="progress-bar-wrapper">
                                    <div class="progress-bar-fill" id="progressFill"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Content -->
                        <div class="form-content">
                            <!-- Step 1: Email -->
                            <div class="form-step active" id="step1">
                                <h2 class="step-title">Let's get started</h2>
                                <p class="step-subtitle">Enter your email address to begin</p>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" placeholder="you@example.com"
                                        required>
                                    <small class="validation-message d-none" id="emailError"></small>
                                    <small class="validation-success d-none" id="emailSuccess"><i class="fas fa-check"></i>
                                        Email looks good</small>
                                </div>

                                <div class="social-login-divider">
                                    <span>or continue with</span>
                                </div>

                                <div class="social-buttons">
                                    <button type="button" class="social-btn" onclick="socialLogin('google')">
                                        <i class="fab fa-google"></i> Google
                                    </button>
                                    <button type="button" class="social-btn" onclick="socialLogin('facebook')">
                                        <i class="fab fa-facebook"></i> Facebook
                                    </button>
                                </div>

                                <button type="button" class="btn-continue" onclick="nextStep(1)">
                                    Continue <i class="fas fa-arrow-right"></i>
                                </button>

                                <div class="login-link">
                                    Already have an account? <a href="login.html">Sign in</a>
                                </div>
                            </div>

                            <!-- Step 2: Email Verification -->
                            <div class="form-step" id="step2">
                                <h2 class="step-title">Verify your email</h2>
                                <p class="step-subtitle">We've sent a code to <span id="verifyEmail"></span></p>

                                <div class="verification-codes">
                                    <input type="text" class="verification-input" maxlength="1" placeholder="0"
                                        required>
                                    <input type="text" class="verification-input" maxlength="1" placeholder="0"
                                        required>
                                    <input type="text" class="verification-input" maxlength="1" placeholder="0"
                                        required>
                                    <input type="text" class="verification-input" maxlength="1" placeholder="0"
                                        required>
                                    <input type="text" class="verification-input" maxlength="1" placeholder="0"
                                        required>
                                    <input type="text" class="verification-input" maxlength="1" placeholder="0"
                                        required>
                                </div>

                                <small class="validation-message d-none" id="verificationError"></small>

                                <div class="resend-code">
                                    <p>Didn't receive a code? <button type="button" class="resend-btn"
                                            onclick="resendCode()">Resend</button></p>
                                    <small id="resendTimer"></small>
                                </div>

                                <div class="step-navigation">
                                    <button type="button" class="btn-back" onclick="prevStep(2)">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button>
                                    <button type="button" class="btn-continue" onclick="nextStep(2)">
                                        Verify <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Personal Information -->
                            <div class="form-step" id="step3">
                                <h2 class="step-title">Tell us about yourself</h2>
                                <p class="step-subtitle">We'll use this information to personalize your experience</p>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="firstName" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="firstName" placeholder="John"
                                            required>
                                        <small class="validation-message d-none" id="firstNameError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lastName" placeholder="Doe"
                                            required>
                                        <small class="validation-message d-none" id="lastNameError"></small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone"
                                        placeholder="+1 (555) 000-0000" required>
                                    <small class="validation-message d-none" id="phoneError"></small>
                                </div>

                                <div class="form-group">
                                    <label for="dateOfBirth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dateOfBirth" required>
                                    <small class="validation-message d-none" id="dobError"></small>
                                </div>

                                <div class="step-navigation">
                                    <button type="button" class="btn-back" onclick="prevStep(3)">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button>
                                    <button type="button" class="btn-continue" onclick="nextStep(3)">
                                        Continue <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 4: Address Information -->
                            <div class="form-step" id="step4">
                                <h2 class="step-title">Where do you live?</h2>
                                <p class="step-subtitle">This helps us provide better service and shipping options</p>

                                <div class="form-group">
                                    <label for="address" class="form-label">Street Address</label>
                                    <input type="text" class="form-control" id="address"
                                        placeholder="123 Main Street" required>
                                    <small class="validation-message d-none" id="addressError"></small>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" placeholder="New York"
                                            required>
                                        <small class="validation-message d-none" id="cityError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="state" class="form-label">State/Province</label>
                                        <input type="text" class="form-control" id="state" placeholder="NY"
                                            required>
                                        <small class="validation-message d-none" id="stateError"></small>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="zipcode" class="form-label">ZIP Code</label>
                                        <input type="text" class="form-control" id="zipcode" placeholder="10001"
                                            required>
                                        <small class="validation-message d-none" id="zipcodeError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="country" class="form-label">Country</label>
                                        <select class="form-control" id="country" required>
                                            <option value="">Select a country</option>
                                            <option value="USA">United States</option>
                                            <option value="CAN">Canada</option>
                                            <option value="UK">United Kingdom</option>
                                            <option value="AUS">Australia</option>
                                            <option value="IND">India</option>
                                            <option value="OTHER">Other</option>
                                        </select>
                                        <small class="validation-message d-none" id="countryError"></small>
                                    </div>
                                </div>

                                <div class="step-navigation">
                                    <button type="button" class="btn-back" onclick="prevStep(4)">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button>
                                    <button type="button" class="btn-continue" onclick="nextStep(4)">
                                        Continue <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 5: Password & Security -->
                            <div class="form-step" id="step5">
                                <h2 class="step-title">Secure your account</h2>
                                <p class="step-subtitle">Create a strong password to protect your account</p>

                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control" id="password"
                                            placeholder="Create a strong password" required>
                                        <button type="button" class="toggle-password"
                                            onclick="togglePasswordVisibility('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="validation-message d-none" id="passwordError"></small>

                                    <!-- Password Strength Indicator -->
                                    <div class="strength-container">
                                        <div class="strength-meter">
                                            <div class="strength-bar" id="strengthBar"></div>
                                        </div>
                                        <small class="strength-text" id="strengthText">Very Weak</small>
                                    </div>

                                    <!-- Password Requirements -->
                                    <div class="requirements">
                                        <div class="requirement" id="reqLength">
                                            <i class="fas fa-circle"></i> At least 8 characters
                                        </div>
                                        <div class="requirement" id="reqUppercase">
                                            <i class="fas fa-circle"></i> One uppercase letter
                                        </div>
                                        <div class="requirement" id="reqLowercase">
                                            <i class="fas fa-circle"></i> One lowercase letter
                                        </div>
                                        <div class="requirement" id="reqNumber">
                                            <i class="fas fa-circle"></i> One number
                                        </div>
                                        <div class="requirement" id="reqSpecial">
                                            <i class="fas fa-circle"></i> One special character (!@#$%^&*)
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control" id="confirmPassword"
                                            placeholder="Confirm your password" required>
                                        <button type="button" class="toggle-password"
                                            onclick="togglePasswordVisibility('confirmPassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="validation-message d-none" id="confirmPasswordError"></small>
                                </div>

                                <!-- Terms & Conditions -->
                                <div class="terms-check">
                                    <input type="checkbox" id="terms" required>
                                    <label for="terms">
                                        I agree to the <a href="#">Terms of Service</a> and <a
                                            href="#">Privacy
                                            Policy</a>
                                    </label>
                                    <small class="validation-message d-none" id="termsError"></small>
                                </div>

                                <!-- Newsletter -->
                                <div class="newsletter-check">
                                    <input type="checkbox" id="newsletter" checked>
                                    <label for="newsletter">
                                        I'd like to receive emails about new products and special offers
                                    </label>
                                </div>

                                <!-- Alerts -->
                                <div id="successAlert" class="alert alert-success d-none">
                                    <i class="fas fa-check-circle"></i> Account created successfully! Redirecting...
                                </div>
                                <div id="errorAlert" class="alert alert-danger d-none">
                                    <i class="fas fa-exclamation-circle"></i> <span id="errorMessage">An error
                                        occurred</span>
                                </div>

                                <div class="step-navigation">
                                    <button type="button" class="btn-back" onclick="prevStep(5)">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button>
                                    <button type="button" class="btn-continue" onclick="completeRegistration()">
                                        Create Account <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section: Benefits -->
                    <div class="benefits-section">
                        <div class="benefits-content">
                            <h3>Why join TechHub?</h3>

                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="benefit-text">
                                    <h4>Fast Shipping</h4>
                                    <p>Get your orders delivered quickly</p>
                                </div>
                            </div>

                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="benefit-text">
                                    <h4>Secure Shopping</h4>
                                    <p>Your data is protected with SSL encryption</p>
                                </div>
                            </div>

                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-undo"></i>
                                </div>
                                <div class="benefit-text">
                                    <h4>Easy Returns</h4>
                                    <p>30-day money-back guarantee</p>
                                </div>
                            </div>

                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div class="benefit-text">
                                    <h4>24/7 Support</h4>
                                    <p>Our team is always here to help</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


@endsection
