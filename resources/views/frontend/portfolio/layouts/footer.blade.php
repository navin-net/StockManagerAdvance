
<!-- ═══════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════ -->
<footer>
  <div class="container-xl">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div class="footer-copy">© 2025 Navin Kevin · Powered by <span style="color:var(--cyan)">Bootstrap 5.3</span></div>
      <div class="footer-links d-flex gap-4">
        <a href="#hero">Top ↑</a>
        <a href="#">GitHub</a>
        <a href="#">LinkedIn</a>
        <a href="#">RSS</a>
      </div>
    </div>
  </div>
</footer>

<!-- ═══════════════════════════════════════════════════
     3-STEP REGISTER MODAL
═══════════════════════════════════════════════════ -->
<div class="reg-backdrop" id="regBackdrop" onclick="onBackdropClick(event)">
  <div class="reg-modal" id="regModal">

    <!-- Header -->
    <div class="reg-head">
      <div class="reg-head-left">
        <div class="reg-logo">// AM.DEV</div>
        <div class="reg-title">Create Account</div>
      </div>
      <button class="reg-close" onclick="closeRegister()" title="Close"><i class="bi bi-x-lg"></i></button>
    </div>

    <!-- Step Progress -->
    <div class="reg-progress">
      <div class="reg-step-node active" id="snode1">
        <div class="reg-step-circle" id="scirc1">1</div>
        <div class="reg-step-label">Account</div>
      </div>
      <div class="reg-step-line" id="sline1"></div>
      <div class="reg-step-node" id="snode2">
        <div class="reg-step-circle" id="scirc2">2</div>
        <div class="reg-step-label">Profile</div>
      </div>
      <div class="reg-step-line" id="sline2"></div>
      <div class="reg-step-node" id="snode3">
        <div class="reg-step-circle" id="scirc3">3</div>
        <div class="reg-step-label">Done</div>
      </div>
    </div>

    <!-- ── STEP 1: Account Details ── -->
    <div class="reg-body">
      <div class="reg-panel active" id="panel1">
        <p class="reg-subtitle">Enter your credentials to create your developer account.</p>

        <div class="reg-row">
          <div class="reg-field">
            <label>First Name</label>
            <div class="input-icon-wrap">
              <i class="bi bi-person"></i>
              <input type="text" id="r-fname" placeholder="Kevin"/>
            </div>
            <div class="field-error">Required field</div>
          </div>
          <div class="reg-field">
            <label>Last Name</label>
            <div class="input-icon-wrap">
              <i class="bi bi-person"></i>
              <input type="text" id="r-lname" placeholder="Morgan"/>
            </div>
            <div class="field-error">Required field</div>
          </div>
        </div>

        <div class="reg-field">
          <label>Email Address</label>
          <div class="input-icon-wrap">
            <i class="bi bi-envelope"></i>
            <input type="email" id="r-email" placeholder="kevin@example.com"/>
          </div>
          <div class="field-error">Enter a valid email</div>
        </div>

        <div class="reg-field">
          <label>Password</label>
          <div class="input-icon-wrap" style="position:relative;">
            <i class="bi bi-lock"></i>
            <input type="password" id="r-pw" placeholder="Min. 8 characters" oninput="checkPwStrength(this.value)"/>
            <span class="toggle-pw" onclick="togglePwVis('r-pw', this)"><i class="bi bi-eye"></i></span>
          </div>
          <div class="pw-strength">
            <div class="pw-bars">
              <div class="pw-bar" id="pwb1"></div>
              <div class="pw-bar" id="pwb2"></div>
              <div class="pw-bar" id="pwb3"></div>
              <div class="pw-bar" id="pwb4"></div>
            </div>
            <div class="pw-label" id="pwLabel">Enter a password</div>
          </div>
        </div>

        <label class="reg-check">
          <input type="checkbox" id="r-terms"/>
          <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
        </label>
      </div>

      <!-- ── STEP 2: Profile Setup ── -->
      <div class="reg-panel" id="panel2">
        <p class="reg-subtitle">Tell us about yourself so we can personalise your experience.</p>

        <div class="reg-field">
          <label>Your Role</label>
        </div>
        <div class="role-grid">
          <div class="role-card selected" onclick="selectRole(this)">
            <div class="role-card-icon">⚙️</div>
            <div class="role-card-label">Backend Dev</div>
          </div>
          <div class="role-card" onclick="selectRole(this)">
            <div class="role-card-icon">🎨</div>
            <div class="role-card-label">Frontend Dev</div>
          </div>
          <div class="role-card" onclick="selectRole(this)">
            <div class="role-card-icon">☁️</div>
            <div class="role-card-label">DevOps / Cloud</div>
          </div>
          <div class="role-card" onclick="selectRole(this)">
            <div class="role-card-icon">📊</div>
            <div class="role-card-label">Data / ML</div>
          </div>
        </div>

        <div class="reg-field">
          <label>Top Skills <span style="color:var(--text3);font-size:.58rem;">(pick any)</span></label>
        </div>
        <div class="skill-pills">
          <span class="skill-pill selected" onclick="this.classList.toggle('selected')">Python</span>
          <span class="skill-pill selected" onclick="this.classList.toggle('selected')">Go</span>
          <span class="skill-pill" onclick="this.classList.toggle('selected')">Rust</span>
          <span class="skill-pill" onclick="this.classList.toggle('selected')">TypeScript</span>
          <span class="skill-pill" onclick="this.classList.toggle('selected')">PostgreSQL</span>
          <span class="skill-pill" onclick="this.classList.toggle('selected')">Redis</span>
          <span class="skill-pill" onclick="this.classList.toggle('selected')">Docker</span>
          <span class="skill-pill" onclick="this.classList.toggle('selected')">Kubernetes</span>
          <span class="skill-pill" onclick="this.classList.toggle('selected')">AWS</span>
          <span class="skill-pill" onclick="this.classList.toggle('selected')">GraphQL</span>
        </div>

        <div class="reg-field">
          <label>Years of Experience</label>
          <select id="r-exp">
            <option value="">Select range…</option>
            <option>0 – 1 year</option>
            <option selected>2 – 4 years</option>
            <option>5 – 8 years</option>
            <option>9+ years</option>
          </select>
        </div>
      </div>

      <!-- ── STEP 3: Success ── -->
      <div class="reg-panel" id="panel3">
        <div class="reg-success">
          <div class="reg-success-icon">✓</div>
          <h3>You're all set!</h3>
          <p>Welcome to AM.DEV, <strong id="welcomeName" style="color:var(--cyan)">Kevin</strong>. Your account has been created and is ready to use.</p>
          <div class="reg-success-details">
            <span class="reg-success-tag" id="successRole">⚙️ Backend Dev</span>
            <span class="reg-success-tag" id="successEmail">kevin@example.com</span>
            <span class="reg-success-tag">🟢 Active</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer Nav -->
    <div class="reg-foot" id="regFoot">
      <div class="reg-foot-left" id="regFootLeft">
        Already have an account? <a href="#" onclick="closeRegister()">Sign in</a>
      </div>
      <div class="reg-foot-right">
        <button class="reg-btn ghost" id="regBackBtn" onclick="regBack()" style="display:none;"><i class="bi bi-arrow-left"></i> Back</button>
        <button class="reg-btn primary" id="regNextBtn" onclick="regNext()">Continue <i class="bi bi-arrow-right"></i></button>
      </div>
    </div>

  </div>
</div>
<!-- ── END REGISTER MODAL ── -->

<script src="{{ asset('frontend/js/script.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
