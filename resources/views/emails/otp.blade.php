<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>OTP Verification</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background: #0d0d12;
      font-family: 'DM Sans', sans-serif;
      padding: 40px 16px;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .email-wrapper {
      max-width: 560px;
      width: 100%;
      margin: 0 auto;
    }

    .email-card {
      background: #13131a;
      border: 1px solid #2a2a3a;
      border-radius: 24px;
      overflow: hidden;
      position: relative;
    }

    /* ── Header ── */
    .header {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
      padding: 44px 48px 36px;
      position: relative;
      overflow: hidden;
    }

    .header::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse 60% 80% at 80% -10%, rgba(99,179,255,0.12) 0%, transparent 70%);
    }

    .header-grid {
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(99,179,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99,179,255,0.03) 1px, transparent 1px);
      background-size: 32px 32px;
    }

    .logo-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 28px;
      position: relative;
    }

    .logo-icon {
      width: 38px; height: 38px;
      background: linear-gradient(135deg, #63b3ff, #7c3aed);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px;
    }

    .logo-name {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      color: #e8e8f0;
      letter-spacing: 0.02em;
    }

    .header-title {
      font-family: 'Playfair Display', serif;
      font-size: 30px;
      color: #ffffff;
      line-height: 1.2;
      position: relative;
    }

    .header-sub {
      margin-top: 8px;
      font-size: 14px;
      color: rgba(200,210,255,0.55);
      font-weight: 300;
      letter-spacing: 0.02em;
      position: relative;
    }

    /* ── Body ── */
    .body {
      padding: 40px 48px;
    }

    .greeting {
      font-size: 15px;
      color: #9090a8;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .greeting strong {
      color: #c8c8e8;
      font-weight: 500;
    }

    /* ── OTP Block ── */
    .otp-label {
      font-size: 11px;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: #63b3ff;
      font-family: 'DM Mono', monospace;
      margin-bottom: 14px;
    }

    .otp-block {
      background: #0d0d14;
      border: 1px solid #2c2c44;
      border-radius: 16px;
      padding: 28px 32px;
      margin-bottom: 28px;
      position: relative;
      overflow: hidden;
      text-align: center;
    }

    .otp-block::before {
      content: '';
      position: absolute;
      top: -1px; left: 32px; right: 32px; height: 1px;
      background: linear-gradient(90deg, transparent, #63b3ff66, transparent);
    }

    /* Single $otp display */
    .otp {
      font-family: 'DM Mono', monospace;
      font-size: 42px;
      font-weight: 500;
      color: #e0e8ff;
      letter-spacing: 0.35em;
      padding-right: 0; /* compensate trailing letter-spacing */
      display: inline-block;
      margin-bottom: 16px;
    }

    .otp-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 6px;
    }

    .expires-badge {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      color: #9090a8;
      font-family: 'DM Mono', monospace;
    }

    .dot-pulse {
      width: 7px; height: 7px;
      background: #22c55e;
      border-radius: 50%;
      display: inline-block;
    }

    .expires-time {
      font-family: 'DM Mono', monospace;
      font-size: 13px;
      color: #f97316;
      background: rgba(249,115,22,0.08);
      border: 1px solid rgba(249,115,22,0.2);
      border-radius: 20px;
      padding: 3px 12px;
    }

    /* ── Info ── */
    .info-text {
      font-size: 13.5px;
      color: #6a6a80;
      line-height: 1.7;
      margin-bottom: 24px;
    }

    .warning-box {
      background: rgba(239,68,68,0.05);
      border: 1px solid rgba(239,68,68,0.15);
      border-radius: 12px;
      padding: 14px 18px;
      display: flex;
      gap: 12px;
      align-items: flex-start;
      margin-bottom: 32px;
    }

    .warning-icon {
      font-size: 16px;
      flex-shrink: 0;
      margin-top: 1px;
    }

    .warning-text {
      font-size: 12.5px;
      color: #f87171;
      line-height: 1.6;
    }

    /* ── CTA ── */
    .cta-btn {
      display: block;
      text-align: center;
      background: linear-gradient(135deg, #2563eb, #7c3aed);
      color: #fff;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      letter-spacing: 0.04em;
      padding: 15px 24px;
      border-radius: 12px;
      margin-bottom: 36px;
    }

    /* ── Divider ── */
    .divider {
      border: none;
      border-top: 1px solid #1e1e2e;
      margin-bottom: 28px;
    }

    /* ── Footer ── */
    .footer {
      padding: 0 48px 36px;
    }

    .footer-links {
      display: flex;
      gap: 20px;
      justify-content: center;
      margin-bottom: 16px;
    }

    .footer-links a {
      font-size: 12px;
      color: #44445a;
      text-decoration: none;
    }

    .footer-copy {
      text-align: center;
      font-size: 11.5px;
      color: #33334a;
      line-height: 1.7;
    }
  </style>
</head>
<body>
  <div class="email-wrapper">
    <div class="email-card">
        <div class="header">
        <div class="header-grid"></div>
        <div class="header-title">Verification<br>Code</div>
        <div class="header-sub">One-time passcode for your account</div>
      </div>
      <div class="body">
        <p class="greeting">
          Hello, <strong>{{ $name ?? 'there' }}</strong> 👋<br>
          We received a sign-in request for your account. Use the code below to complete verification.
        </p>
        <div class="otp-label">Your one-time password</div>
        <div class="otp-block">
          <div class="otp">{{ $otp }}</div>
        </div>
        <p class="info-text">
          This code is valid for <strong style="color:#c8c8e8">10 minutes</strong> and can only be used once.
          If you didn't attempt to sign in, you can safely ignore this email — your account remains secure.
        </p>
        <a href="{{ config('app.url') }}" class="cta-btn">Open {{ config('app.name') }} &rarr;</a>
      </div>

      <hr class="divider" />

      <!-- Footer -->
      {{-- <div class="footer">
        <div class="footer-links">
          <a href="#">Help Center</a>
          <a href="#">Privacy Policy</a>
          <a href="#">Unsubscribe</a>
        </div>
        <div class="footer-copy">
          &copy; {{ date('Y') }} {{ config('app.name') }}<br>
          This is an automated message — please do not reply directly to this email.
        </div>
      </div> --}}

    </div>
  </div>
</body>
</html>
