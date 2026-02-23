@extends('frontend.portfolio.layouts.app')
@section('title', __('messages.portfolio'))

@section('content')
    <section id="hero">
        <div class="container-xl">
            <div class="row align-items-center g-5">
                <div class="col-12 col-lg-7">
                    <div class="hero-eyebrow">// Available for hire · Remote &amp; On-site</div>
                    <h1 class="hero-title">
                        <span id="typedName"></span><span class="cursor-name">|</span><br>
                        {{-- <em id="typedRole"></em><span class="cursor-role" id="cursorRole" style="display:none;">|</span> --}}
                    </h1>
                    <em id="typedRole"></em><span class="cursor-role" id="cursorRole" style="display:none;">|</span>

                    <p class="hero-sub">I architect scalable, high-performance systems. Specializing in distributed
                        architectures, cloud infrastructure, and API design that powers products at scale.</p>
                    <div class="hero-ctas d-flex flex-wrap gap-3">
                        <a href="#projects" class="btn-accent"><i class="bi bi-grid-3x3-gap-fill"></i> View My Work</a>
                        <a href="#" class="btn-ghost"><i class="bi bi-download"></i> Download CV</a>
                    </div>
                    <div class="hero-stats">
                        <div>
                            <div class="stat-num">1+</div>
                            <div class="stat-lbl">Years Exp.</div>
                        </div>
                        <div>
                            <div class="stat-num">40+</div>
                            <div class="stat-lbl">Projects</div>
                        </div>
                        <div>
                            <div class="stat-num">12</div>
                            <div class="stat-lbl">Open Source</div>
                        </div>
                        {{-- <div>
                            <div class="stat-num">99.9%</div>
                            <div class="stat-lbl">Uptime SLA</div>
                        </div> --}}
                    </div>
                </div>

                <div class="col-12 col-lg-5 d-flex justify-content-center justify-content-lg-end">
                    <div class="profile-wrap">
                        <div class="profile-diagonal" id="profileDiag" title="">


                            {{-- <div class="pd-upload-hint"><i class="bi bi-camera-fill"></i> Upload Photo</div> --}}

                            {{-- <div class="pd-glow"></div> --}}

                            <div class="pd-frame">
                                {{-- <div class="pd-panel"></div> --}}

                                <!-- Upper photo half (diagonally clipped) -->
                                <div class="pd-photo" id="profilePhoto">
                                    <div class="ph">
                                        <img src="{{ asset('frontend/img/kevin1.png') }}" alt="Profile Photo"
                                            style="width:100%;height:100%;object-fit:cover;display:block;" />
                                        {{-- <div class="ph-icon"></div>
                  <div class="ph-lbl">Your Photo Here</div> --}}
                                    </div>
                                </div>

                                <!-- Diagonal slash line -->
                                {{-- <div class="pd-slash"></div> --}}
                            </div>

                            <!-- Floating name card -->
                            <div class="pd-card">
                                <div>
                                    <div class="pd-name">Navin Kevin</div>
                                    <div class="pd-role">Backend Developer</div>
                                </div>
                                <div class="pd-status">
                                    <span class="status-dot"></span> Available
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- ── END PROFILE ── -->

            </div>
        </div>
    </section>
    <div class="section-divider"></div>


    <section id="about">
        <div class="container-xl">
            <div class="section-label fade-in">01 — About Me</div>
            <h2 class="fade-in mb-5">Building robust systems<br>that scale.</h2>
            <div class="row g-5 align-items-center">
                <div class="col-12 col-lg-7 fade-in">
                    <p style="color:var(--text2);line-height:1.85;margin-bottom:1.2rem;">I'm a backend developer with 1
                        years of experience building the invisible infrastructure that makes great products possible. I care
                        deeply about reliability, security, and performance.</p>
                    <p style="color:var(--text2);line-height:1.85;margin-bottom:1.2rem;">From designing microservices
                        architectures to optimizing database query performance at scale, I approach every problem with a
                        systems-thinking mindset. My work lives in the terminal, in dashboards, and in the confidence of
                        teams who know their infrastructure is solid.</p>
                    <p style="color:var(--text2);line-height:1.85;margin-bottom:2rem;">When I'm not writing code, I'm
                        writing about it — contributing to technical blogs and open-source documentation to share what I've
                        learned.</p>
                    <a href="#contact" class="btn-accent"><i class="bi bi-send-fill"></i> Let's Work Together</a>
                </div>
                <div class="col-12 col-lg-5 fade-in">
                    <div class="about-info-card">
                        <div class="about-info-title">// Quick Info</div>
                        <div class="info-row"><span class="info-key">Location</span><span class="info-val">Phnom Penh,
                                Cambodia</span></div>
                        <div class="info-row"><span class="info-key">Role</span><span class="info-val">Backend
                                Developer</span></div>
                        <div class="info-row"><span class="info-key">Experience</span><span class="info-val">1+ Years</span>
                        </div>
                        <div class="info-row"><span class="info-key">Focus</span><span class="info-val">Systems &amp;
                                APIs</span></div>
                        <div class="info-row"><span class="info-key">Education</span><span class="info-val">B.Sc.
                                Information Technology</span></div>
                        <div class="info-row"><span class="info-key">Availability</span><span class="info-val"
                                style="color:var(--cyan)">● Open to Offers</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <section id="skills">
        <div class="container-xl">
            <div class="section-label fade-in">02 — Skills</div>
            <h2 class="fade-in mb-5">Technologies I work with.</h2>
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="skill-card fade-in">
                        <div class="skill-icon">⚙️</div>
                        <div class="skill-cat">Languages</div>
                        <div class="skill-name">Core Programming</div>
                        <div class="d-flex flex-wrap gap-2"><span class="tag">Python</span><span
                                class="tag">Go</span><span class="tag">Rust</span><span
                                class="tag">TypeScript</span><span class="tag">Java</span></div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="skill-card fade-in">
                        <div class="skill-icon">🔌</div>
                        <div class="skill-cat">APIs &amp; Frameworks</div>
                        <div class="skill-name">API Design</div>
                        <div class="d-flex flex-wrap gap-2"><span class="tag">REST</span><span
                                class="tag">gRPC</span><span class="tag">GraphQL</span><span
                                class="tag">FastAPI</span><span class="tag">Gin</span><span
                                class="tag">Express</span></div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="skill-card fade-in">
                        <div class="skill-icon">🗄️</div>
                        <div class="skill-cat">Databases</div>
                        <div class="skill-name">Data Storage</div>
                        <div class="d-flex flex-wrap gap-2"><span class="tag">PostgreSQL</span><span
                                class="tag">Redis</span><span class="tag">MongoDB</span><span
                                class="tag">Cassandra</span><span class="tag">Elasticsearch</span></div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="skill-card fade-in">
                        <div class="skill-icon">☁️</div>
                        <div class="skill-cat">Cloud &amp; DevOps</div>
                        <div class="skill-name">Infrastructure</div>
                        <div class="d-flex flex-wrap gap-2"><span class="tag">AWS</span><span
                                class="tag">GCP</span><span class="tag">Docker</span><span
                                class="tag">Kubernetes</span><span class="tag">Terraform</span><span
                                class="tag">CI/CD</span></div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="skill-card fade-in">
                        <div class="skill-icon">📨</div>
                        <div class="skill-cat">Messaging</div>
                        <div class="skill-name">Async Systems</div>
                        <div class="d-flex flex-wrap gap-2"><span class="tag">Kafka</span><span
                                class="tag">RabbitMQ</span><span class="tag">NATS</span><span
                                class="tag">Celery</span></div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="skill-card fade-in">
                        <div class="skill-icon">📊</div>
                        <div class="skill-cat">Observability</div>
                        <div class="skill-name">Monitoring &amp; Logging</div>
                        <div class="d-flex flex-wrap gap-2"><span class="tag">Prometheus</span><span
                                class="tag">Grafana</span><span class="tag">Datadog</span><span
                                class="tag">OpenTelemetry</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>
    <section id="projects">
        <div class="container-xl">
            <div class="section-label fade-in">03 — Projects</div>
            <h2 class="fade-in mb-5">Things I've built.</h2>
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="project-card fade-in">
                        <div class="proj-header">
                            <span class="proj-badge">Full Stack</span>
                            <div class="proj-title">StockManagerAdvance</div>
                        </div>
                        <div class="proj-body">
                            <p class="proj-desc">
                                A comprehensive inventory and POS solution featuring multi-warehouse support,
                                real-time stock tracking, and automated tax calculation. Integrated with
                                detailed financial reporting and purchase management.
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="tag">PHP</span>
                                <span class="tag">Laravel</span>
                                <span class="tag">MySQL</span>
                                <span class="tag">jQuery</span>
                            </div>
                            <div class="d-flex gap-3">
                                <a href="https://github.com/navin-net/" class="proj-link"><i class="bi bi-github"></i> GitHub</a>

                                <a href="#" class="proj-link"><i class="bi bi-laptop"></i> Live Demo</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="project-card fade-in">
                        <div class="proj-header"><span class="proj-badge">Professional</span>
                            <div class="proj-title">AuthVault</div>
                        </div>
                        <div class="proj-body">
                            <p class="proj-desc">Enterprise identity management platform serving 500K+ users. OIDC/SAML2
                                compliant with hardware token support. Zero-downtime migrations across 3 data centers.</p>
                            <div class="d-flex flex-wrap gap-2 mb-3"><span class="tag">Python</span><span
                                    class="tag">PostgreSQL</span><span class="tag">AWS</span><span
                                    class="tag">Terraform</span></div>
                            <div class="d-flex gap-3"><a href="#" class="proj-link"><i
                                        class="bi bi-file-earmark-text"></i> Case Study</a></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="project-card fade-in">
                        <div class="proj-header"><span class="proj-badge">Side Project</span>
                            <div class="proj-title">QueryScope</div>
                        </div>
                        <div class="proj-body">
                            <p class="proj-desc">CLI tool that analyzes slow PostgreSQL queries and suggests optimized
                                indexes. Integrates with EXPLAIN ANALYZE and outputs actionable recommendations.</p>
                            <div class="d-flex flex-wrap gap-2 mb-3"><span class="tag">Rust</span><span
                                    class="tag">PostgreSQL</span><span class="tag">CLI</span></div>
                            <div class="d-flex gap-3"><a href="https://github.com/navin-net/" class="proj-link"><i class="bi bi-github"></i>
                                    GitHub</a><a href="#" class="proj-link"><i class="bi bi-play-circle"></i>
                                    Demo</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>
    <section id="blog">
        <div class="container-xl">
            <div class="section-label fade-in">04 — Blog</div>
            <h2 class="fade-in mb-5">Thoughts &amp; write-ups.</h2>
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="#" class="blog-card fade-in">
                        <div class="blog-meta"><span>Jan 2025</span><span class="blog-dot"></span><span>8 min read</span>
                        </div>
                        <div class="blog-title">Designing Idempotent APIs for Distributed Systems</div>
                        <p class="blog-excerpt">Why idempotency keys matter, how to implement them correctly, and the edge
                            cases nobody talks about.</p>
                        <div class="blog-read"><i class="bi bi-arrow-right"></i> Read Article</div>
                    </a>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="#" class="blog-card fade-in">
                        <div class="blog-meta"><span>Dec 2024</span><span class="blog-dot"></span><span>12 min read</span>
                        </div>
                        <div class="blog-title">PostgreSQL at Scale: Index Strategies That Actually Work</div>
                        <p class="blog-excerpt">From partial indexes to BRIN — a practical guide to optimizing Postgres for
                            high-write workloads at production scale.</p>
                        <div class="blog-read"><i class="bi bi-arrow-right"></i> Read Article</div>
                    </a>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="#" class="blog-card fade-in">
                        <div class="blog-meta"><span>Nov 2024</span><span class="blog-dot"></span><span>6 min read</span>
                        </div>
                        <div class="blog-title">Migrating from Monolith to Microservices Without the Chaos</div>
                        <p class="blog-excerpt">A battle-tested playbook for strangling a monolith incrementally — without
                            burning down your on-call rotation.</p>
                        <div class="blog-read"><i class="bi bi-arrow-right"></i> Read Article</div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <section id="contact">
        <div class="container-xl">
            <div class="section-label fade-in">05 — Contact</div>
            <h2 class="fade-in mb-5">Let's build something<br>together.</h2>
            <div class="row g-5">
                <div class="col-12 col-lg-5 fade-in">
                    <p style="color:var(--text2);line-height:1.8;margin-bottom:2rem;">I'm currently open to senior backend
                        roles, architecture consulting, and interesting open-source collaborations. Drop me a message and
                        I'll get back within 48 hours.</p>
                    <div class="d-flex flex-column gap-3">
                        <a href="mailto:kevin@example.com" class="contact-link"><i class="bi bi-envelope-fill"
                                style="color:var(--cyan)"></i> kevin@example.com</a>
                        <a href="#" class="contact-link"><i class="bi bi-linkedin" style="color:var(--cyan)"></i>
                            linkedin.com/in/kevin</a>
                        <a href="https://github.com/navin-net/" class="contact-link"><i class="bi bi-github" style="color:var(--cyan)"></i>
                            github.com/kevin</a>
                        <a href="#" class="contact-link"><i class="bi bi-twitter-x" style="color:var(--cyan)"></i>
                            @kevin_dev</a>
                        <a href="#" class="btn-accent mt-1"><i class="bi bi-download"></i> Download CV (PDF)</a>
                    </div>
                </div>
                <div class="col-12 col-lg-7 fade-in">
                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Jane Smith" />
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="jane@company.com" />
                        </div>

                        <div class="col-12">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Project Inquiry" />
                        </div>

                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5"
                                placeholder="Tell me about your project..."></textarea>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn-accent">
                                <i class="bi bi-send-fill"></i> Send Message
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')

<script>
/* ── TYPEWRITER ───────────────────── */
const NAME = "Navin Kevin.";
const ROLES = [
    "Backend Dev.",
    // "Systems Architect.",
    "API Designer.",
    // "Cloud Engineer.",
];
const TS = 80,
    DS = 45,
    PA = 1800,
    PW = 350;
const elN = document.getElementById("typedName");
const elR = document.getElementById("typedRole");
const cN = document.querySelector(".cursor-name");
const cR = document.getElementById("cursorRole");
let ri = 0;

function typeName() {
    let i = 0;
    cN.style.display = "inline";
    const iv = setInterval(() => {
        elN.textContent = NAME.slice(0, ++i);
        if (i === NAME.length) {
            clearInterval(iv);
            setTimeout(() => {
                cN.style.display = "none";
                cR.style.display = "inline";
                typeRole();
            }, PW);
        }
    }, TS);
}
function typeRole() {
    const role = ROLES[ri % ROLES.length];
    let i = 0;
    const iv = setInterval(() => {
        elR.textContent = role.slice(0, ++i);
        if (i === role.length) {
            clearInterval(iv);
            setTimeout(delRole, PA);
        }
    }, TS);
}
function delRole() {
    const iv = setInterval(() => {
        elR.textContent = elR.textContent.slice(0, -1);
        if (!elR.textContent.length) {
            clearInterval(iv);
            ri++;
            setTimeout(typeRole, PW);
        }
    }, DS);
}
setTimeout(typeName, 500);
const photoArea = document.getElementById("profilePhoto");

photoArea.addEventListener("click", () => {

    const imageSrc = photoArea.querySelector("img")?.src;
    if (!imageSrc) return;

    const modal = document.createElement("div");
    modal.style = `
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.85);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        cursor: pointer;
    `;

    modal.innerHTML = `
        <img src="${imageSrc}"
             style="max-width:90%; max-height:90%; object-fit:contain;">
    `;

    modal.onclick = () => modal.remove();
    document.body.appendChild(modal);
});
</script>
@endpush
