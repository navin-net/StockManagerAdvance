/* ── LOGIN DROPDOWN ───────────────── */
function toggleLogin(e) {
    e.stopPropagation();
    const trigger = document.getElementById("loginTrigger");
    const dropdown = document.getElementById("loginDropdown");
    trigger.classList.toggle("open");
    dropdown.classList.toggle("open");
}
function closeLogin() {
    document.getElementById("loginTrigger").classList.remove("open");
    document.getElementById("loginDropdown").classList.remove("open");
}
document.addEventListener("click", (e) => {
    const wrap = document.getElementById("loginWrap");
    if (wrap && !wrap.contains(e.target)) closeLogin();
});

/* ── THEME TOGGLE ─────────────────── */
function toggleTheme() {
    const h = document.documentElement;
    h.setAttribute(
        "data-theme",
        h.getAttribute("data-theme") === "dark" ? "light" : "dark",
    );
}



/* ── PROFILE UPLOAD ───────────────── */
// const diag = document.getElementById("profileDiag");
// const photoArea = document.getElementById("profilePhoto");
// diag.addEventListener("click", () => {
//     const inp = document.createElement("input");
//     inp.type = "file";
//     inp.accept = "image/*";
//     inp.onchange = (e) => {
//         const file = e.target.files[0];
//         if (!file) return;
//         const r = new FileReader();
//         r.onload = (ev) => {
//             photoArea.innerHTML = `<img src="${ev.target.result}" alt="Profile Photo" style="width:100%;height:100%;object-fit:cover;display:block;"/>`;
//         };
//         r.readAsDataURL(file);
//     };
//     inp.click();
// });

/* ── SCROLL FADE-IN ───────────────── */
const obs = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add("visible"), i * 70);
                obs.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.1, rootMargin: "0px 0px -40px 0px" },
);
document.querySelectorAll(".fade-in").forEach((el) => obs.observe(el));

/* ══════════════════════════════════════════════════
   3-STEP REGISTER MODAL
══════════════════════════════════════════════════ */
let regStep = 1;

function openRegister() {
    regStep = 1;
    renderRegStep();
    document.getElementById("regBackdrop").classList.add("open");
    document.body.style.overflow = "hidden";
}
function closeRegister() {
    document.getElementById("regBackdrop").classList.remove("open");
    document.body.style.overflow = "";
    // reset after close
    setTimeout(() => {
        regStep = 1;
        renderRegStep();
    }, 350);
}
function onBackdropClick(e) {
    if (e.target === document.getElementById("regBackdrop")) closeRegister();
}

function renderRegStep() {
    // Panels
    [1, 2, 3].forEach((i) => {
        document
            .getElementById("panel" + i)
            .classList.toggle("active", i === regStep);
        const node = document.getElementById("snode" + i);
        node.classList.remove("active", "done");
        if (i === regStep) node.classList.add("active");
        if (i < regStep) node.classList.add("done");
        // Checkmark in done circles
        const circ = document.getElementById("scirc" + i);
        circ.innerHTML =
            i < regStep
                ? '<i class="bi bi-check2" style="font-size:.75rem;"></i>'
                : i;
    });
    // Lines
    [1, 2].forEach((i) => {
        document
            .getElementById("sline" + i)
            .classList.toggle("done", i < regStep);
    });
    // Back button
    document.getElementById("regBackBtn").style.display =
        regStep > 1 ? "inline-flex" : "none";
    // Next/Finish button
    const nextBtn = document.getElementById("regNextBtn");
    const footLeft = document.getElementById("regFootLeft");
    if (regStep === 3) {
        nextBtn.innerHTML =
            '<i class="bi bi-box-arrow-in-right"></i> Go to Dashboard';
        nextBtn.onclick = closeRegister;
        footLeft.innerHTML = "";
    } else if (regStep === 2) {
        nextBtn.innerHTML =
            'Create Account <i class="bi bi-check2-circle"></i>';
        nextBtn.onclick = regNext;
        footLeft.innerHTML =
            'Already have an account? <a href="#" onclick="closeRegister()">Sign in</a>';
    } else {
        nextBtn.innerHTML = 'Continue <i class="bi bi-arrow-right"></i>';
        nextBtn.onclick = regNext;
        footLeft.innerHTML =
            'Already have an account? <a href="#" onclick="closeRegister()">Sign in</a>';
    }
}

function regNext() {
    if (regStep === 1 && !validateStep1()) return;
    if (regStep === 2) populateSuccess();
    regStep = Math.min(regStep + 1, 3);
    renderRegStep();
}
function regBack() {
    regStep = Math.max(regStep - 1, 1);
    renderRegStep();
}

function validateStep1() {
    let ok = true;
    const fname = document.getElementById("r-fname");
    const lname = document.getElementById("r-lname");
    const email = document.getElementById("r-email");
    const pw = document.getElementById("r-pw");
    const terms = document.getElementById("r-terms");

    [fname, lname, pw].forEach((el) => {
        el.classList.toggle("error", el.value.trim().length < 1);
        if (el.value.trim().length < 1) ok = false;
    });
    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
    email.classList.toggle("error", !emailOk);
    if (!emailOk) ok = false;

    if (pw.value.length < 8) {
        pw.classList.add("error");
        ok = false;
    }

    if (!terms.checked) {
        terms.closest(".reg-check").style.outline =
            "1px solid rgba(255,80,80,.5)";
        terms.closest(".reg-check").style.borderRadius = "6px";
        ok = false;
    } else {
        terms.closest(".reg-check").style.outline = "";
    }
    return ok;
}

function populateSuccess() {
    const fname = document.getElementById("r-fname").value || "Dev";
    const email = document.getElementById("r-email").value || "";
    const role =
        document.querySelector(".role-card.selected .role-card-label")
            ?.textContent || "Developer";
    const icon =
        document.querySelector(".role-card.selected .role-card-icon")
            ?.textContent || "⚙️";
    document.getElementById("welcomeName").textContent = fname;
    document.getElementById("successEmail").textContent = email;
    document.getElementById("successRole").textContent = icon + " " + role;
}

/* Password strength */
function checkPwStrength(val) {
    const bars = [1, 2, 3, 4].map((i) => document.getElementById("pwb" + i));
    const label = document.getElementById("pwLabel");
    const levels = [
        { test: (v) => v.length >= 1, cls: "weak", txt: "Too weak" },
        { test: (v) => v.length >= 6, cls: "fair", txt: "Fair" },
        {
            test: (v) => v.length >= 8 && /[A-Z]/.test(v),
            cls: "strong",
            txt: "Strong",
        },
        {
            test: (v) => v.length >= 10 && /[^a-zA-Z0-9]/.test(v),
            cls: "great",
            txt: "Excellent!",
        },
    ];
    let score = 0;
    levels.forEach((l, i) => {
        if (l.test(val)) score = i + 1;
    });
    bars.forEach((b, i) => {
        b.className = "pw-bar";
        if (i < score) b.classList.add(levels[score - 1].cls);
    });
    label.textContent =
        val.length === 0
            ? "Enter a password"
            : levels[Math.max(score - 1, 0)].txt;
    label.style.color = [
        "var(--text3)",
        "#ff5555",
        "#ffaa00",
        "#00d4ff",
        "#00e676",
    ][score];
}

/* Toggle password visibility */
function togglePwVis(id, el) {
    const inp = document.getElementById(id);
    const showing = inp.type === "text";
    inp.type = showing ? "password" : "text";
    el.innerHTML = `<i class="bi bi-eye${showing ? "" : "-slash"}"></i>`;
}

/* Role card single-select */
function selectRole(el) {
    document
        .querySelectorAll(".role-card")
        .forEach((c) => c.classList.remove("selected"));
    el.classList.add("selected");
}

/* Keyboard: Escape closes modal */
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeRegister();
});

const sections = document.querySelectorAll("section[id]");
const navLinks = document.querySelectorAll(".nav-link");
window.addEventListener(
    "scroll",
    () => {
        let cur = "";
        sections.forEach((s) => {
            if (window.scrollY >= s.offsetTop - 100) cur = s.id;
        });
        navLinks.forEach((l) => {
            l.classList.remove("active");
            if (l.getAttribute("href") === "#" + cur) l.classList.add("active");
        });
    },
    { passive: true },
);

