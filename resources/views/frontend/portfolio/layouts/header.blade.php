<!-- ═══════════════════════════════════════════════════
     TOP HEADER BAR
═══════════════════════════════════════════════════ -->
<div class="top-bar">
  <!-- Left: contact info & status -->
  <div class="top-bar-left">
    <div class="top-avail">
      <span class="top-avail-dot"></span>
      <span>Available for hire</span>
    </div>
    <div class="top-bar-divider top-bar-hide-sm"></div>
    <a href="mailto:navin@example.com" class="top-bar-item top-bar-hide-sm">
      <i class="bi bi-envelope-fill"></i> navin@example.com
    </a>
    <div class="top-bar-divider top-bar-hide-sm"></div>
    <a href="#" class="top-bar-item top-bar-hide-sm">
      <i class="bi bi-geo-alt-fill"></i> Phnom Penh, Cambodia
    </a>
  </div>

  <!-- Right: socials + login -->
  <div class="top-bar-right">
    <a href="https://github.com/navin-net/" class="top-bar-item" title="GitHub" target="_blank"><i class="bi bi-github"></i></a>
    <a href="https://t.me/Kee_vinn" class="top-bar-item" title="Telegram" target="_blank">
    <i class="bi bi-telegram"></i></a>
    <a href="#" class="top-bar-item" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
    <a href="#" class="top-bar-item" title="Twitter/X"><i class="bi bi-twitter-x"></i></a>
    <div class="top-bar-divider"></div>

    <!-- Login Dropdown -->
    {{-- <div class="login-wrap" id="loginWrap">
      <button class="login-trigger" id="loginTrigger" onclick="toggleLogin(event)">
        <i class="bi bi-person-fill"></i>
        Login
        <i class="bi bi-chevron-down chevron"></i>
      </button>

      <div class="login-dropdown" id="loginDropdown">

        <!-- Profile header (shown when "logged in") -->
        <div class="dd-header">
          <div class="dd-avatar">👨‍💻</div>
          <div>
            <div class="dd-name">Navin Kevin</div>
            <div class="dd-email">navin@example.com</div>
          </div>
        </div>

        <!-- Section: Account -->
        <div class="dd-section">Account</div>
        <button class="dd-item" onclick="closeLogin()">
          <i class="bi bi-person-circle"></i>
          <div>
            <div>My Profile</div>
            <div class="dd-item-sub">View &amp; edit your info</div>
          </div>
        </button>
        <button class="dd-item" onclick="closeLogin()">
          <i class="bi bi-grid-1x2-fill"></i>
          <div>
            <div>Dashboard</div>
            <div class="dd-item-sub">Overview &amp; analytics</div>
          </div>
        </button>
        <button class="dd-item" onclick="closeLogin()">
          <i class="bi bi-briefcase-fill"></i>
          <div>
            <div>Projects</div>
            <div class="dd-item-sub">Manage your work</div>
          </div>
        </button>

        <!-- Section: Settings -->
        <div class="dd-section">Settings</div>
        <button class="dd-item" onclick="closeLogin()">
          <i class="bi bi-gear-fill"></i>
          <div>
            <div>Preferences</div>
            <div class="dd-item-sub">Theme, language &amp; more</div>
          </div>
        </button>
        <button class="dd-item" onclick="closeLogin()">
          <i class="bi bi-shield-lock-fill"></i>
          <div>
            <div>Security</div>
            <div class="dd-item-sub">Password &amp; 2FA</div>
          </div>
        </button>

        <!-- Divider + Danger -->
        <div class="dd-section">Session</div>
        <button class="dd-item danger" onclick="closeLogin()">
          <i class="bi bi-box-arrow-right"></i>
          <div>Sign Out</div>
        </button>

        <!-- Footer -->
        <div class="dd-footer">
          <span class="dd-footer-text">v2.4.1 · Portfolio OS</span>
          <button class="dd-badge" style="background:var(--accent-grad);color:#fff;border:none;cursor:pointer;border-radius:20px;padding:.15rem .65rem;font-family:'DM Mono',monospace;font-size:.58rem;letter-spacing:.06em;" onclick="closeLogin(); openRegister();">Register →</button>
        </div>
      </div>
    </div> --}}
    <!-- End Login Dropdown -->
  </div>
</div>

<!-- ═══════════════════════════════════════════════════
     NAVBAR — Bootstrap 5.3 Navbar
═══════════════════════════════════════════════════ -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-xl">
    <a class="navbar-brand" href="#hero">KV<span>.</span>DEV</a>

    <div class="d-flex align-items-center gap-2 ms-auto ms-lg-0 order-lg-last">
      <button class="theme-toggle border-0" onclick="toggleTheme()" aria-label="Toggle theme">
        <div class="t-icon sun">☀️</div>
        <div class="t-icon moon">🌙</div>
      </button>
      <button class="navbar-toggler ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto gap-lg-1 py-2 py-lg-0">
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#skills">Skills</a></li>
        <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
        <li class="nav-item"><a class="nav-link" href="#blog">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>
