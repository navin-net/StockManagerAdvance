<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Commercial Construction — IRONVEIL CONSTRUCTORS</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap"
        rel="stylesheet" />
    <style>
        :root {
            --black: #0a0a08;
            --steel: #1c1c18;
            --iron: #2e2e28;
            --rust: #c94b1a;
            --amber: #f5a623;
            --concrete: #8a8a7a;
            --chalk: #e8e4d8;
            --white: #f5f2ea;
            --success: #22c55e;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Barlow', sans-serif;
            background: var(--black);
            color: var(--chalk);
            overflow-x: hidden;
            cursor: none;
        }

        /* ─── CURSOR ─────────────────────────────────────────────────────── */
        #cur {
            position: fixed;
            width: 10px;
            height: 10px;
            background: var(--rust);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: width .15s, height .15s;
            mix-blend-mode: difference;
        }

        #cur2 {
            position: fixed;
            width: 34px;
            height: 34px;
            border: 1px solid var(--amber);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9998;
            transform: translate(-50%, -50%);
            transition: left .18s ease, top .18s ease, width .25s, height .25s;
            opacity: .55;
        }

        a:hover,
        button:hover {
            cursor: none;
        }

        /* ─── NOISE ──────────────────────────────────────────────────────── */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.035'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9997;
            opacity: .35;
        }

        /* ─── NAV ────────────────────────────────────────────────────────── */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 52px;
            background: rgba(10, 10, 8, .94);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(201, 75, 26, .18);
        }

        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 26px;
            letter-spacing: 4px;
            color: var(--white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .logo-dot {
            width: 7px;
            height: 7px;
            background: var(--rust);
            border-radius: 50%;
            animation: pdot 2s infinite;
        }

        @keyframes pdot {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.6);
                opacity: .5;
            }
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-links a {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--concrete);
            text-decoration: none;
            transition: color .2s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--rust);
            transition: width .3s;
        }

        .nav-links a:hover {
            color: var(--white);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-cta {
            background: var(--rust);
            color: var(--white) !important;
            padding: 9px 22px;
            font-size: 10px !important;
            letter-spacing: 3px;
            text-decoration: none;
            transition: background .2s !important;
            clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);
        }

        .nav-cta:hover {
            background: #e55820 !important;
        }

        .nav-cta::after {
            display: none !important;
        }

        /* ─── BREADCRUMB ─────────────────────────────────────────────────── */
        .breadcrumb {
            padding: 108px 52px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--concrete);
            font-weight: 600;
        }

        .breadcrumb a {
            color: var(--concrete);
            text-decoration: none;
            transition: color .2s;
        }

        .breadcrumb a:hover {
            color: var(--rust);
        }

        .breadcrumb span {
            color: var(--rust);
        }

        .bc-sep {
            opacity: .4;
        }

        /* ─── HERO ───────────────────────────────────────────────────────── */
        #hero {
            padding: 40px 52px 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: end;
            min-height: 72vh;
            position: relative;
        }

        .hero-left {
            padding-bottom: 72px;
        }

        .service-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 10px;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: var(--rust);
            font-weight: 700;
            margin-bottom: 24px;
            border: 1px solid rgba(201, 75, 26, .3);
            padding: 8px 16px;
        }

        .service-label-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--rust);
            animation: pdot 1.5s infinite;
        }

        .hero-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(60px, 7vw, 108px);
            line-height: .92;
            letter-spacing: 2px;
            color: var(--white);
            margin-bottom: 28px;
        }

        .hero-title em {
            font-style: normal;
            color: var(--rust);
        }

        .hero-desc {
            font-size: 17px;
            line-height: 1.75;
            color: var(--concrete);
            font-weight: 300;
            max-width: 480px;
            margin-bottom: 40px;
        }

        .hero-meta {
            display: flex;
            gap: 32px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .meta-item {}

        .meta-val {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 36px;
            color: var(--white);
            letter-spacing: 1px;
            line-height: 1;
        }

        .meta-val span {
            color: var(--rust);
        }

        .meta-key {
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--concrete);
            font-weight: 600;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--rust);
            color: var(--white);
            padding: 15px 34px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .2s;
            display: inline-block;
            clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 0 100%);
        }

        .btn-primary:hover {
            background: #e55820;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--chalk);
            padding: 14px 34px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            text-decoration: none;
            border: 1px solid rgba(232, 228, 216, .25);
            cursor: pointer;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline:hover {
            border-color: var(--amber);
            color: var(--amber);
        }

        /* Hero right — image stack */
        .hero-right {
            position: relative;
            height: 100%;
            min-height: 560px;
        }

        .img-main {
            position: absolute;
            right: 0;
            top: 0;
            width: 80%;
            height: 440px;
            object-fit: cover;
            filter: grayscale(25%) contrast(1.1) brightness(.85);
        }

        .img-accent {
            position: absolute;
            left: 0;
            bottom: 40px;
            width: 52%;
            height: 260px;
            object-fit: cover;
            filter: grayscale(20%) contrast(1.1) brightness(.8);
            border: 4px solid var(--black);
            box-shadow: 0 24px 48px rgba(0, 0, 0, .6);
        }

        .img-badge {
            position: absolute;
            right: 16px;
            bottom: 60px;
            background: var(--rust);
            padding: 20px 22px;
            clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);
        }

        .img-badge-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 42px;
            color: #fff;
            line-height: 1;
        }

        .img-badge-txt {
            font-size: 9px;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, .8);
            text-transform: uppercase;
            font-weight: 600;
        }

        /* ─── SECTION SHARED ─────────────────────────────────────────────── */
        section {
            padding: 100px 52px;
        }

        .stag {
            font-size: 10px;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: var(--rust);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .stag::before {
            content: '';
            width: 30px;
            height: 1px;
            background: var(--rust);
        }

        .sec-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(38px, 4.5vw, 64px);
            color: var(--white);
            letter-spacing: 2px;
            line-height: 1;
        }

        .sec-title em {
            font-style: normal;
            color: var(--rust);
        }

        /* ─── OVERVIEW ───────────────────────────────────────────────────── */
        #overview {
            background: var(--steel);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: start;
        }

        .overview-body {
            font-size: 16px;
            line-height: 1.8;
            color: var(--concrete);
            font-weight: 300;
            margin-bottom: 20px;
        }

        .overview-list {
            margin-top: 28px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .overview-list li {
            list-style: none;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: 14px;
            color: var(--chalk);
            font-weight: 400;
        }

        .overview-list li::before {
            content: '▶';
            color: var(--rust);
            font-size: 9px;
            margin-top: 5px;
            flex-shrink: 0;
        }

        .overview-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px;
        }

        .spec-box {
            background: var(--iron);
            padding: 28px 24px;
            transition: all .3s;
            position: relative;
            overflow: hidden;
            border-top: 2px solid transparent;
        }

        .spec-box::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(201, 75, 26, .07), transparent);
            opacity: 0;
            transition: opacity .3s;
        }

        .spec-box:hover {
            border-top-color: var(--rust);
        }

        .spec-box:hover::before {
            opacity: 1;
        }

        .spec-icon {
            font-size: 28px;
            margin-bottom: 12px;
            display: block;
        }

        .spec-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px;
            letter-spacing: 2px;
            color: var(--white);
            margin-bottom: 6px;
        }

        .spec-desc {
            font-size: 12px;
            color: var(--concrete);
            line-height: 1.6;
            font-weight: 300;
        }

        /* ─── CAPABILITIES ───────────────────────────────────────────────── */
        #capabilities {
            background: var(--black);
        }

        .cap-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3px;
            margin-top: 56px;
        }

        .cap-card {
            background: var(--steel);
            padding: 36px 28px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all .3s;
            border-bottom: 3px solid transparent;
        }

        .cap-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 40%, rgba(201, 75, 26, .12) 100%);
            opacity: 0;
            transition: opacity .3s;
        }

        .cap-card:hover {
            transform: translateY(-6px);
            border-bottom-color: var(--rust);
        }

        .cap-card:hover::after {
            opacity: 1;
        }

        .cap-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 56px;
            color: rgba(255, 255, 255, .04);
            position: absolute;
            top: 12px;
            right: 16px;
            line-height: 1;
            transition: color .3s;
        }

        .cap-card:hover .cap-num {
            color: rgba(201, 75, 26, .12);
        }

        .cap-icon {
            font-size: 32px;
            margin-bottom: 18px;
            display: block;
        }

        .cap-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 20px;
            letter-spacing: 2px;
            color: var(--white);
            margin-bottom: 10px;
        }

        .cap-text {
            font-size: 13px;
            line-height: 1.65;
            color: var(--concrete);
            font-weight: 300;
        }

        /* ─── PROJECT SHOWCASE ───────────────────────────────────────────── */
        #showcase {
            background: var(--steel);
        }

        .showcase-intro {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 56px;
            flex-wrap: wrap;
            gap: 24px;
        }

        .showcase-count {
            font-size: 13px;
            color: var(--concrete);
            letter-spacing: 2px;
        }

        .showcase-grid {
            display: grid;
            grid-template-columns: 5fr 3fr;
            gap: 3px;
        }

        .showcase-left {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .showcase-right {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .proj {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            background: var(--iron);
        }

        .proj-img {
            width: 100%;
            display: block;
            filter: grayscale(35%) brightness(.72) contrast(1.05);
            transition: all .5s ease;
            object-fit: cover;
        }

        .proj-big .proj-img {
            height: 420px;
        }

        .proj-wide .proj-img {
            height: 200px;
        }

        .proj-sm .proj-img {
            height: 200px;
        }

        .proj:hover .proj-img {
            filter: grayscale(10%) brightness(.88);
            transform: scale(1.04);
        }

        .proj-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(0deg, rgba(10, 10, 8, .92) 0%, rgba(10, 10, 8, .1) 55%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 24px 28px;
            transition: background .3s;
        }

        .proj:hover .proj-overlay {
            background: linear-gradient(0deg, rgba(10, 10, 8, .96) 0%, rgba(10, 10, 8, .3) 60%, transparent 100%);
        }

        .proj-cat {
            font-size: 9px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--rust);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .proj-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            letter-spacing: 2px;
            color: var(--white);
            margin-bottom: 4px;
        }

        .proj-loc {
            font-size: 12px;
            color: var(--concrete);
        }

        .proj-details {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            opacity: 0;
            transform: translateY(8px);
            transition: all .3s;
        }

        .proj:hover .proj-details {
            opacity: 1;
            transform: translateY(0);
        }

        .proj-detail-item {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--chalk);
        }

        .proj-detail-item strong {
            color: var(--amber);
        }

        .proj-arrow {
            position: absolute;
            top: 18px;
            right: 18px;
            width: 34px;
            height: 34px;
            background: var(--rust);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #fff;
            opacity: 0;
            transform: translate(10px, -10px);
            transition: all .3s;
        }

        .proj:hover .proj-arrow {
            opacity: 1;
            transform: translate(0, 0);
        }

        /* ─── PROCESS DETAIL ─────────────────────────────────────────────── */
        #process-detail {
            background: var(--black);
        }

        .process-accordion {
            margin-top: 56px;
        }

        .phase {
            border-top: 1px solid var(--iron);
            overflow: hidden;
        }

        .phase:last-child {
            border-bottom: 1px solid var(--iron);
        }

        .phase-header {
            display: flex;
            align-items: center;
            gap: 24px;
            padding: 28px 0;
            cursor: pointer;
            transition: color .2s;
            user-select: none;
        }

        .phase-header:hover .phase-title {
            color: var(--rust);
        }

        .phase-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 13px;
            letter-spacing: 3px;
            color: var(--concrete);
            width: 40px;
            flex-shrink: 0;
        }

        .phase-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            letter-spacing: 2px;
            color: var(--white);
            flex: 1;
            transition: color .2s;
        }

        .phase-duration {
            font-size: 11px;
            letter-spacing: 2px;
            color: var(--concrete);
            text-transform: uppercase;
            font-weight: 600;
            margin-right: 16px;
        }

        .phase-toggle {
            width: 32px;
            height: 32px;
            border: 1px solid var(--iron);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--concrete);
            transition: all .3s;
            flex-shrink: 0;
        }

        .phase.open .phase-toggle {
            background: var(--rust);
            border-color: var(--rust);
            color: #fff;
            transform: rotate(45deg);
        }

        .phase.open .phase-title {
            color: var(--rust);
        }

        .phase-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height .45s cubic-bezier(.4, 0, .2, 1);
            padding: 0 0 0 64px;
        }

        .phase.open .phase-body {
            max-height: 500px;
        }

        .phase-body-inner {
            padding-bottom: 32px;
        }

        .phase-desc {
            font-size: 15px;
            line-height: 1.8;
            color: var(--concrete);
            font-weight: 300;
            margin-bottom: 24px;
        }

        .phase-tasks {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .task {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            font-size: 13px;
            color: var(--chalk);
            background: var(--steel);
            padding: 12px 16px;
        }

        .task::before {
            content: '✓';
            color: var(--rust);
            font-size: 11px;
            margin-top: 2px;
            flex-shrink: 0;
            font-weight: 700;
        }

        /* ─── TEAM ───────────────────────────────────────────────────────── */
        #team {
            background: var(--steel);
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3px;
            margin-top: 56px;
        }

        .team-card {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            background: var(--iron);
        }

        .team-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
            filter: grayscale(60%) brightness(.75);
            transition: all .5s;
        }

        .team-card:hover .team-img {
            filter: grayscale(15%) brightness(.9);
            transform: scale(1.06);
        }

        .team-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(0deg, rgba(10, 10, 8, .95) 0%, transparent 100%);
            padding: 20px 20px 22px;
        }

        .team-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 20px;
            letter-spacing: 2px;
            color: var(--white);
            margin-bottom: 3px;
        }

        .team-role {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--rust);
            font-weight: 600;
        }

        .team-yrs {
            position: absolute;
            top: 14px;
            right: 14px;
            background: var(--rust);
            padding: 5px 10px;
            font-size: 11px;
            color: #fff;
            letter-spacing: 1px;
            font-weight: 700;
            opacity: 0;
            transform: translateY(-6px);
            transition: all .3s;
        }

        .team-card:hover .team-yrs {
            opacity: 1;
            transform: translateY(0);
        }

        /* ─── CERTIFICATIONS ─────────────────────────────────────────────── */
        #certifications {
            background: var(--iron);
            padding: 72px 52px;
        }

        .cert-grid {
            display: flex;
            gap: 3px;
            flex-wrap: wrap;
            margin-top: 48px;
        }

        .cert-badge {
            background: var(--steel);
            border: 1px solid var(--iron);
            padding: 28px 32px;
            text-align: center;
            flex: 1;
            min-width: 180px;
            transition: all .3s;
            border-top: 3px solid transparent;
        }

        .cert-badge:hover {
            border-top-color: var(--rust);
            transform: translateY(-4px);
            background: var(--iron);
        }

        .cert-icon {
            font-size: 36px;
            margin-bottom: 12px;
            display: block;
        }

        .cert-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 16px;
            letter-spacing: 2px;
            color: var(--white);
            margin-bottom: 5px;
        }

        .cert-body {
            font-size: 11px;
            color: var(--concrete);
            line-height: 1.5;
        }

        /* ─── TESTIMONIAL FEATURE ────────────────────────────────────────── */
        #testimonial-feature {
            background: var(--black);
            padding: 100px 52px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 72px;
            align-items: center;
        }

        .testi-quote-mark {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 140px;
            color: var(--rust);
            opacity: .15;
            line-height: .5;
            margin-bottom: 24px;
        }

        .testi-text {
            font-size: 24px;
            line-height: 1.6;
            color: var(--chalk);
            font-style: italic;
            font-weight: 300;
            margin-bottom: 36px;
        }

        .testi-author {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .testi-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--iron), var(--rust));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            color: #fff;
            flex-shrink: 0;
        }

        .testi-name {
            font-weight: 700;
            font-size: 16px;
            color: var(--white);
            margin-bottom: 3px;
        }

        .testi-role {
            font-size: 11px;
            color: var(--concrete);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .testi-right {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .testi-stat {
            background: var(--steel);
            padding: 24px 28px;
            border-left: 3px solid var(--rust);
        }

        .testi-stat-val {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 42px;
            color: var(--white);
            line-height: 1;
        }

        .testi-stat-val span {
            color: var(--rust);
        }

        .testi-stat-key {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--concrete);
            font-weight: 600;
        }

        /* ─── FAQ ────────────────────────────────────────────────────────── */
        #faq {
            background: var(--steel);
        }

        .faq-list {
            margin-top: 56px;
            max-width: 820px;
        }

        .faq-item {
            border-top: 1px solid var(--iron);
        }

        .faq-item:last-child {
            border-bottom: 1px solid var(--iron);
        }

        .faq-q {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 0;
            cursor: pointer;
            gap: 20px;
        }

        .faq-q-text {
            font-size: 16px;
            font-weight: 600;
            color: var(--chalk);
            transition: color .2s;
        }

        .faq-q:hover .faq-q-text {
            color: var(--rust);
        }

        .faq-icon {
            width: 28px;
            height: 28px;
            border: 1px solid var(--iron);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--concrete);
            flex-shrink: 0;
            transition: all .3s;
        }

        .faq-item.open .faq-icon {
            background: var(--rust);
            border-color: var(--rust);
            color: #fff;
            transform: rotate(45deg);
        }

        .faq-item.open .faq-q-text {
            color: var(--rust);
        }

        .faq-a {
            max-height: 0;
            overflow: hidden;
            transition: max-height .4s cubic-bezier(.4, 0, .2, 1);
        }

        .faq-item.open .faq-a {
            max-height: 300px;
        }

        .faq-a-inner {
            padding: 0 0 24px;
            font-size: 14px;
            line-height: 1.8;
            color: var(--concrete);
            font-weight: 300;
        }

        /* ─── PRICING TIERS ──────────────────────────────────────────────── */
        #pricing {
            background: var(--black);
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3px;
            margin-top: 56px;
        }

        .price-card {
            background: var(--steel);
            padding: 40px 32px;
            position: relative;
            border-top: 3px solid var(--iron);
            transition: all .3s;
            overflow: hidden;
        }

        .price-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(201, 75, 26, .06), transparent);
            opacity: 0;
            transition: opacity .3s;
        }

        .price-card.featured {
            border-top-color: var(--rust);
            background: var(--iron);
        }

        .price-card.featured::before {
            opacity: 1;
        }

        .price-card:hover {
            transform: translateY(-6px);
        }

        .price-card:hover::before {
            opacity: 1;
        }

        .price-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--rust);
            padding: 5px 14px;
            font-size: 9px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #fff;
            font-weight: 700;
        }

        .price-tier {
            font-size: 11px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--rust);
            font-weight: 700;
            margin-bottom: 16px;
        }

        .price-val {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 52px;
            color: var(--white);
            line-height: 1;
            margin-bottom: 4px;
        }

        .price-val span {
            font-size: 22px;
            color: var(--concrete);
        }

        .price-note {
            font-size: 12px;
            color: var(--concrete);
            margin-bottom: 28px;
        }

        .price-divider {
            height: 1px;
            background: var(--iron);
            margin-bottom: 24px;
        }

        .price-features {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 32px;
        }

        .price-feat {
            display: flex;
            gap: 10px;
            align-items: center;
            font-size: 13px;
            color: var(--chalk);
        }

        .price-feat::before {
            content: '✓';
            color: var(--rust);
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .price-feat.disabled {
            color: var(--concrete);
        }

        .price-feat.disabled::before {
            content: '✗';
            color: var(--iron);
        }

        /* ─── CONTACT CTA ────────────────────────────────────────────────── */
        #contact-cta {
            background: linear-gradient(135deg, var(--rust) 0%, #9b2d0e 100%);
            padding: 100px 52px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        #contact-cta::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-content {
            position: relative;
            z-index: 1;
            max-width: 640px;
            margin: 0 auto;
        }

        .cta-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(48px, 6vw, 88px);
            color: #fff;
            letter-spacing: 2px;
            line-height: .95;
            margin-bottom: 20px;
        }

        .cta-sub {
            font-size: 17px;
            color: rgba(255, 255, 255, .75);
            font-weight: 300;
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .cta-actions {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-white {
            background: #fff;
            color: var(--rust);
            padding: 15px 36px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .2s;
            display: inline-block;
            clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 0 100%);
        }

        .btn-white:hover {
            background: var(--amber);
            color: var(--black);
            transform: translateY(-2px);
        }

        .btn-white-outline {
            background: transparent;
            color: #fff;
            padding: 14px 36px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .4);
            cursor: pointer;
            transition: all .2s;
            display: inline-block;
        }

        .btn-white-outline:hover {
            border-color: #fff;
            background: rgba(255, 255, 255, .1);
        }

        /* ─── FOOTER ─────────────────────────────────────────────────────── */
        footer {
            background: var(--black);
            border-top: 1px solid var(--iron);
            padding: 52px 52px 28px;
        }

        .footer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 36px;
        }

        .footer-logo-wrap {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 24px;
            letter-spacing: 4px;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-logo-dot {
            width: 6px;
            height: 6px;
            background: var(--rust);
            border-radius: 50%;
        }

        .footer-links-row {
            display: flex;
            gap: 28px;
            flex-wrap: wrap;
        }

        .footer-links-row a {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--concrete);
            text-decoration: none;
            font-weight: 600;
            transition: color .2s;
        }

        .footer-links-row a:hover {
            color: var(--rust);
        }

        .footer-bottom {
            border-top: 1px solid var(--iron);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-copy {
            font-size: 10px;
            letter-spacing: 2px;
            color: var(--concrete);
            text-transform: uppercase;
        }

        .footer-legal {
            display: flex;
            gap: 20px;
        }

        .footer-legal a {
            font-size: 10px;
            letter-spacing: 1px;
            color: var(--concrete);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-legal a:hover {
            color: var(--rust);
        }

        /* ─── ANIMATIONS ─────────────────────────────────────────────────── */
        .fu {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity .7s cubic-bezier(.25, .46, .45, .94), transform .7s cubic-bezier(.25, .46, .45, .94);
        }

        .fu.in {
            opacity: 1;
            transform: translateY(0);
        }

        /* ─── PROGRESS BAR ───────────────────────────────────────────────── */
        #progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--rust), var(--amber));
            z-index: 600;
            transition: width .1s;
            width: 0%;
        }

        /* ─── STICKY QUOTE ───────────────────────────────────────────────── */
        #sticky-quote {
            position: fixed;
            bottom: 32px;
            right: 32px;
            z-index: 300;
            opacity: 0;
            transform: translateY(20px);
            transition: all .4s;
            pointer-events: none;
        }

        #sticky-quote.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .sticky-btn {
            background: var(--rust);
            color: #fff;
            border: none;
            padding: 14px 22px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 14px;
            letter-spacing: 3px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);
            transition: background .2s;
            box-shadow: 0 8px 32px rgba(201, 75, 26, .4);
        }

        .sticky-btn:hover {
            background: #e55820;
        }

        /* ─── RESPONSIVE ─────────────────────────────────────────────────── */
        @media(max-width:900px) {
            nav {
                padding: 16px 20px;
            }

            .nav-links {
                display: none;
            }

            .breadcrumb {
                padding: 88px 20px 0;
            }

            #hero {
                grid-template-columns: 1fr;
                padding: 20px 20px 0;
            }

            .hero-right {
                display: none;
            }

            .hero-left {
                padding-bottom: 48px;
            }

            #overview {
                grid-template-columns: 1fr;
                padding: 60px 20px;
            }

            .overview-specs {
                grid-template-columns: 1fr 1fr;
            }

            .cap-grid {
                grid-template-columns: 1fr 1fr;
            }

            .showcase-grid {
                grid-template-columns: 1fr;
            }

            .team-grid {
                grid-template-columns: 1fr 1fr;
            }

            .pricing-grid {
                grid-template-columns: 1fr;
            }

            #testimonial-feature {
                grid-template-columns: 1fr;
                padding: 60px 20px;
            }

            section {
                padding: 60px 20px;
            }

            #certifications {
                padding: 52px 20px;
            }

            #contact-cta {
                padding: 60px 20px;
            }

            footer {
                padding: 40px 20px 20px;
            }

            .phase-tasks {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- Progress bar -->
    <div id="progress-bar"></div>

    <!-- Cursor -->
    <div id="cur"></div>
    <div id="cur2"></div>

    <!-- Sticky Quote -->
    <div id="sticky-quote">
        <button class="sticky-btn" onclick="window.location.hash='#contact-cta'">
            📋 GET A QUOTE
        </button>
    </div>

    <!-- NAV -->
    <nav>
        <a href="constructor-services.html" class="logo">
            <span class="logo-dot"></span>IRONVEIL
        </a>
        <div class="nav-links">
            <a href="constructor-services.html#services">Services</a>
            <a href="constructor-services.html#projects">Projects</a>
            <a href="constructor-services.html#about">About</a>
            <a href="constructor-services.html#process">Process</a>
            <a href="#contact-cta" class="nav-cta">GET A QUOTE</a>
        </div>
    </nav>

    <!-- BREADCRUMB -->
    <div class="breadcrumb fu">
        <a href="constructor-services.html">Home</a>
        <span class="bc-sep">/</span>
        <a href="constructor-services.html#services">Services</a>
        <span class="bc-sep">/</span>
        <span>Commercial Construction</span>
    </div>

    <!-- HERO -->
    <section id="hero" style="padding-top:40px;">
        <div class="hero-left fu">
            <div class="service-label">
                <span class="service-label-dot"></span>
                Service Detail — 01
            </div>
            <h1 class="hero-title">
                COMMERCIAL<br />
                <em>CONSTRUCTION</em>
            </h1>
            <p class="hero-desc">
                From concept to handover, we engineer landmark commercial structures —
                office towers, retail destinations, mixed-use precincts, and hospitality
                complexes that reshape skylines and drive economic growth.
            </p>
            <div class="hero-meta">
                <div class="meta-item">
                    <div class="meta-val">180<span>+</span></div>
                    <div class="meta-key">Projects Delivered</div>
                </div>
                <div class="meta-item">
                    <div class="meta-val">$4.2<span>B</span></div>
                    <div class="meta-key">Total Contract Value</div>
                </div>
                <div class="meta-item">
                    <div class="meta-val">100<span>%</span></div>
                    <div class="meta-key">On-Budget Delivery</div>
                </div>
            </div>
            <div class="hero-actions">
                <a href="#contact-cta" class="btn-primary">Request a Quote</a>
                <a href="#showcase" class="btn-outline">▶ View Projects</a>
            </div>
        </div>

        <div class="hero-right fu" style="transition-delay:.15s;">
            <img src="https://images.unsplash.com/photo-1486325212027-8081e485255e?w=900&q=80" alt="Commercial tower"
                class="img-main" />
            <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&q=80" alt="Construction site"
                class="img-accent" />
            <div class="img-badge">
                <div class="img-badge-num">28</div>
                <div class="img-badge-txt">Avg. Floors<br />Per Project</div>
            </div>
        </div>
    </section>

    <!-- OVERVIEW -->
    <section id="overview">
        <div class="fu">
            <div class="stag">Overview</div>
            <h2 class="sec-title">WHY CHOOSE<br /><em>IRONVEIL</em></h2>
            <p class="overview-body" style="margin-top:24px;">
                Our commercial construction division combines decades of structural expertise
                with cutting-edge BIM technology, lean construction methodologies, and an
                unwavering commitment to safety and quality.
            </p>
            <p class="overview-body">
                We handle every phase of your project — from site investigation and feasibility
                through to commissioning and post-handover maintenance — with a single
                accountable team you can trust.
            </p>
            <ul class="overview-list">
                <li>Integrated design-build capability reduces coordination risk</li>
                <li>Dedicated BIM team with full 4D and 5D modelling</li>
                <li>ISO 9001:2015 and ISO 45001:2018 certified operations</li>
                <li>In-house structural steel fabrication facility</li>
                <li>Real-time progress dashboard accessible to all stakeholders</li>
                <li>Comprehensive 2-year structural warranty on all projects</li>
            </ul>
        </div>

        <div class="overview-specs fu" style="transition-delay:.15s;">
            <div class="spec-box">
                <span class="spec-icon">🏗️</span>
                <div class="spec-name">Structural Systems</div>
                <p class="spec-desc">RC frame, structural steel, post-tensioned slabs, hybrid systems tailored to your
                    height and span requirements.</p>
            </div>
            <div class="spec-box">
                <span class="spec-icon">🌱</span>
                <div class="spec-name">Sustainability</div>
                <p class="spec-desc">LEED Gold and Platinum delivery capability with integrated renewable energy systems
                    and waste reduction planning.</p>
            </div>
            <div class="spec-box">
                <span class="spec-icon">📡</span>
                <div class="spec-name">Smart Building</div>
                <p class="spec-desc">BMS, IoT sensor integration, and fibre-ready infrastructure built in from the
                    ground floor up.</p>
            </div>
            <div class="spec-box">
                <span class="spec-icon">🛡️</span>
                <div class="spec-name">Safety Record</div>
                <p class="spec-desc">1.4 million accident-free hours in this division. Our safety management systems
                    exceed local and international benchmarks.</p>
            </div>
        </div>
    </section>

    <!-- CAPABILITIES -->
    <section id="capabilities">
        <div class="fu">
            <div class="stag">What We Build</div>
            <h2 class="sec-title">BUILDING <em>TYPES</em></h2>
        </div>
        <div class="cap-grid">
            <div class="cap-card fu">
                <div class="cap-num">01</div>
                <span class="cap-icon">🏢</span>
                <div class="cap-name">Office Towers</div>
                <p class="cap-text">Grade-A commercial offices from 10 to 80 storeys. Curtain wall, raised floors, and
                    IBMS standard.</p>
            </div>
            <div class="cap-card fu" style="transition-delay:.08s">
                <div class="cap-num">02</div>
                <span class="cap-icon">🛍️</span>
                <div class="cap-name">Retail & Mixed-Use</div>
                <p class="cap-text">Large-format retail podiums, lifestyle malls, and mixed-use developments with
                    complex MEP coordination.</p>
            </div>
            <div class="cap-card fu" style="transition-delay:.16s">
                <div class="cap-num">03</div>
                <span class="cap-icon">🏨</span>
                <div class="cap-name">Hotels & Hospitality</div>
                <p class="cap-text">5-star hotel construction with premium fit-out capabilities, FF&E coordination, and
                    pre-opening support.</p>
            </div>
            <div class="cap-card fu" style="transition-delay:.24s">
                <div class="cap-num">04</div>
                <span class="cap-icon">🏫</span>
                <div class="cap-name">Education & Civic</div>
                <p class="cap-text">Universities, schools, civic centres, and cultural institutions built to DDA and
                    international accessibility standards.</p>
            </div>
            <div class="cap-card fu" style="transition-delay:.32s">
                <div class="cap-num">05</div>
                <span class="cap-icon">🏥</span>
                <div class="cap-name">Healthcare Facilities</div>
                <p class="cap-text">Hospitals, specialist clinics, and medical research centres with sterile environment
                    and HEPA MEP systems.</p>
            </div>
            <div class="cap-card fu" style="transition-delay:.40s">
                <div class="cap-num">06</div>
                <span class="cap-icon">🅿️</span>
                <div class="cap-name">Multi-Deck Carparks</div>
                <p class="cap-text">Above and below-grade structured parking — precast, post-tensioned, and automated
                    systems.</p>
            </div>
            <div class="cap-card fu" style="transition-delay:.48s">
                <div class="cap-num">07</div>
                <span class="cap-icon">⚡</span>
                <div class="cap-name">Data Centres</div>
                <p class="cap-text">Tier III and IV data centre construction with precision power, cooling, and security
                    infrastructure.</p>
            </div>
            <div class="cap-card fu" style="transition-delay:.56s">
                <div class="cap-num">08</div>
                <span class="cap-icon">🌿</span>
                <div class="cap-name">Green Precincts</div>
                <p class="cap-text">Net-zero commercial districts integrating solar canopies, green roofs, and EV
                    charging infrastructure.</p>
            </div>
        </div>
    </section>

    <!-- SHOWCASE -->
    <section id="showcase">
        <div class="showcase-intro fu">
            <div>
                <div class="stag">Portfolio</div>
                <h2 class="sec-title">RECENT <em>PROJECTS</em></h2>
            </div>
            <span class="showcase-count">SHOWING 4 OF 180+ PROJECTS</span>
        </div>

        <div class="showcase-grid fu" style="transition-delay:.1s">
            <div class="showcase-left">
                <div class="proj proj-big">
                    <img src="https://images.unsplash.com/photo-1486325212027-8081e485255e?w=900&q=80"
                        alt="Meridian Tower" class="proj-img" />
                    <div class="proj-overlay">
                        <div class="proj-cat">Office Tower · Singapore</div>
                        <div class="proj-name">MERIDIAN TOWER</div>
                        <div class="proj-loc">📍 Marina Bay Financial Centre · 2023</div>
                        <div class="proj-details">
                            <div class="proj-detail-item"><strong>36</strong> FLOORS</div>
                            <div class="proj-detail-item"><strong>$280M</strong> VALUE</div>
                            <div class="proj-detail-item"><strong>LEED</strong> PLATINUM</div>
                        </div>
                    </div>
                    <div class="proj-arrow">↗</div>
                </div>
                <div class="proj proj-wide">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=900&q=80"
                        alt="Atlas Campus" class="proj-img" />
                    <div class="proj-overlay">
                        <div class="proj-cat">Mixed-Use · Malaysia</div>
                        <div class="proj-name">ATLAS CAMPUS KL</div>
                        <div class="proj-loc">📍 Kuala Lumpur · 2024</div>
                        <div class="proj-details">
                            <div class="proj-detail-item"><strong>18</strong> FLOORS</div>
                            <div class="proj-detail-item"><strong>$95M</strong> VALUE</div>
                        </div>
                    </div>
                    <div class="proj-arrow">↗</div>
                </div>
            </div>
            <div class="showcase-right">
                <div class="proj proj-sm">
                    <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=600&q=80" alt="Nexus Hotel"
                        class="proj-img" />
                    <div class="proj-overlay">
                        <div class="proj-cat">Hospitality · Thailand</div>
                        <div class="proj-name">NEXUS GRAND HOTEL</div>
                        <div class="proj-loc">📍 Bangkok · 2023</div>
                        <div class="proj-details">
                            <div class="proj-detail-item"><strong>5-STAR</strong></div>
                        </div>
                    </div>
                    <div class="proj-arrow">↗</div>
                </div>
                <div class="proj proj-sm">
                    <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=600&q=80" alt="Data Centre"
                        class="proj-img" />
                    <div class="proj-overlay">
                        <div class="proj-cat">Data Centre · Indonesia</div>
                        <div class="proj-name">HELIX DC JAKARTA</div>
                        <div class="proj-loc">📍 Jakarta · 2022</div>
                        <div class="proj-details">
                            <div class="proj-detail-item"><strong>TIER IV</strong></div>
                        </div>
                    </div>
                    <div class="proj-arrow">↗</div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROCESS DETAIL -->
    <section id="process-detail">
        <div class="fu">
            <div class="stag">How We Deliver</div>
            <h2 class="sec-title">PROJECT <em>PHASES</em></h2>
        </div>

        <div class="process-accordion fu" style="transition-delay:.1s">

            <div class="phase open">
                <div class="phase-header" onclick="togglePhase(this)">
                    <span class="phase-num">PHASE 01</span>
                    <span class="phase-title">PRE-CONSTRUCTION</span>
                    <span class="phase-duration">4–12 Weeks</span>
                    <div class="phase-toggle">+</div>
                </div>
                <div class="phase-body">
                    <div class="phase-body-inner">
                        <p class="phase-desc">We invest heavily in pre-construction to eliminate risk and surprises.
                            This phase sets the foundation for a successful project with thorough planning,
                            coordination, and stakeholder alignment.</p>
                        <div class="phase-tasks">
                            <div class="task">Site investigation & geotechnical survey</div>
                            <div class="task">Feasibility study & budget validation</div>
                            <div class="task">BIM model creation (LOD 300+)</div>
                            <div class="task">Subcontractor prequalification</div>
                            <div class="task">Regulatory submission & permit management</div>
                            <div class="task">Construction programme (Primavera P6)</div>
                            <div class="task">Risk register & mitigation plan</div>
                            <div class="task">Stakeholder communication protocol</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="phase">
                <div class="phase-header" onclick="togglePhase(this)">
                    <span class="phase-num">PHASE 02</span>
                    <span class="phase-title">SUBSTRUCTURE</span>
                    <span class="phase-duration">8–20 Weeks</span>
                    <div class="phase-toggle">+</div>
                </div>
                <div class="phase-body">
                    <div class="phase-body-inner">
                        <p class="phase-desc">Reliable foundations are the bedrock of every great structure. Our
                            substructure teams specialize in challenging ground conditions, deep excavations, and
                            complex basement environments.</p>
                        <div class="phase-tasks">
                            <div class="task">Piling & deep foundation systems</div>
                            <div class="task">Excavation & earth retention</div>
                            <div class="task">Basement construction & waterproofing</div>
                            <div class="task">Ground floor slab & podium works</div>
                            <div class="task">Dewatering management</div>
                            <div class="task">Temporary works design</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="phase">
                <div class="phase-header" onclick="togglePhase(this)">
                    <span class="phase-num">PHASE 03</span>
                    <span class="phase-title">SUPERSTRUCTURE</span>
                    <span class="phase-duration">16–52 Weeks</span>
                    <div class="phase-toggle">+</div>
                </div>
                <div class="phase-body">
                    <div class="phase-body-inner">
                        <p class="phase-desc">The visible rise of your building. Our superstructure crews work with
                            precision cycle times, advanced formwork systems, and rigorous quality control at every
                            pour.</p>
                        <div class="phase-tasks">
                            <div class="task">Structural frame (RC / steel / hybrid)</div>
                            <div class="task">Post-tensioned slab systems</div>
                            <div class="task">Core & shear wall construction</div>
                            <div class="task">Structural steel erection</div>
                            <div class="task">Precast element installation</div>
                            <div class="task">Weekly cycle-time reporting</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="phase">
                <div class="phase-header" onclick="togglePhase(this)">
                    <span class="phase-num">PHASE 04</span>
                    <span class="phase-title">ENVELOPE & MEP</span>
                    <span class="phase-duration">20–40 Weeks</span>
                    <div class="phase-toggle">+</div>
                </div>
                <div class="phase-body">
                    <div class="phase-body-inner">
                        <p class="phase-desc">Façade, waterproofing, and building services — the systems that make a
                            building functional and beautiful. We manage complex MEP interfaces with precision
                            scheduling.</p>
                        <div class="phase-tasks">
                            <div class="task">Curtain wall & unitised façade</div>
                            <div class="task">Roof and waterproofing systems</div>
                            <div class="task">HVAC & mechanical systems</div>
                            <div class="task">Electrical & fire protection</div>
                            <div class="task">Plumbing & drainage</div>
                            <div class="task">Lifts & vertical transportation</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="phase">
                <div class="phase-header" onclick="togglePhase(this)">
                    <span class="phase-num">PHASE 05</span>
                    <span class="phase-title">FIT-OUT & COMMISSIONING</span>
                    <span class="phase-duration">12–24 Weeks</span>
                    <div class="phase-toggle">+</div>
                </div>
                <div class="phase-body">
                    <div class="phase-body-inner">
                        <p class="phase-desc">The final stretch — where raw structure becomes a finished, functioning
                            building. We manage rigorous commissioning and handover to ensure everything works perfectly
                            on day one.</p>
                        <div class="phase-tasks">
                            <div class="task">Interior finishes & joinery</div>
                            <div class="task">BMS commissioning & testing</div>
                            <div class="task">IBMS & smart building integration</div>
                            <div class="task">Defects rectification & snagging</div>
                            <div class="task">Certification & regulatory sign-off</div>
                            <div class="task">O&M manuals & as-built documentation</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- TEAM -->
    <section id="team">
        <div class="fu">
            <div class="stag">The People</div>
            <h2 class="sec-title">MEET THE <em>TEAM</em></h2>
        </div>
        <div class="team-grid">
            <div class="team-card fu" style="transition-delay:.05s">
                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&q=80" alt="Marcus Lim"
                    class="team-img" />
                <div class="team-info">
                    <div class="team-name">MARCUS LIM</div>
                    <div class="team-role">Division Director</div>
                </div>
                <div class="team-yrs">22 YRS</div>
            </div>
            <div class="team-card fu" style="transition-delay:.1s">
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80" alt="Priya Nair"
                    class="team-img" />
                <div class="team-info">
                    <div class="team-name">PRIYA NAIR</div>
                    <div class="team-role">Senior Project Manager</div>
                </div>
                <div class="team-yrs">14 YRS</div>
            </div>
            <div class="team-card fu" style="transition-delay:.15s">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&q=80" alt="David Chen"
                    class="team-img" />
                <div class="team-info">
                    <div class="team-name">DAVID CHEN</div>
                    <div class="team-role">Structural Lead Engineer</div>
                </div>
                <div class="team-yrs">18 YRS</div>
            </div>
            <div class="team-card fu" style="transition-delay:.2s">
                <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&q=80" alt="Siti Rahman"
                    class="team-img" />
                <div class="team-info">
                    <div class="team-name">SITI RAHMAN</div>
                    <div class="team-role">BIM & Digital Construction</div>
                </div>
                <div class="team-yrs">9 YRS</div>
            </div>
        </div>
    </section>

    <!-- CERTIFICATIONS -->
    <div id="certifications">
        <div class="fu">
            <div class="stag">Standards & Compliance</div>
            <h2 class="sec-title">OUR <em>CERTIFICATIONS</em></h2>
        </div>
        <div class="cert-grid fu" style="transition-delay:.1s">
            <div class="cert-badge">
                <span class="cert-icon">🏅</span>
                <div class="cert-name">ISO 9001:2015</div>
                <div class="cert-body">Quality Management System certified since 2002</div>
            </div>
            <div class="cert-badge">
                <span class="cert-icon">🦺</span>
                <div class="cert-name">ISO 45001:2018</div>
                <div class="cert-body">Occupational Health & Safety Management</div>
            </div>
            <div class="cert-badge">
                <span class="cert-icon">🌿</span>
                <div class="cert-name">LEED AP</div>
                <div class="cert-body">Accredited professionals for sustainable delivery</div>
            </div>
            <div class="cert-badge">
                <span class="cert-icon">📐</span>
                <div class="cert-name">BCA BIM e-submission</div>
                <div class="cert-body">Full BIM Level 2 compliant organisation</div>
            </div>
            <div class="cert-badge">
                <span class="cert-icon">🔒</span>
                <div class="cert-name">bizSAFE STAR</div>
                <div class="cert-body">Highest safety management system certification</div>
            </div>
        </div>
    </div>

    <!-- TESTIMONIAL FEATURE -->
    <section id="testimonial-feature">
        <div class="fu">
            <div class="testi-quote-mark">"</div>
            <p class="testi-text">
                "IRONVEIL's commercial team delivered our 36-storey headquarters three weeks ahead of schedule.
                Their attention to structural detail, their transparency through every phase, and the quality
                of the finished building exceeded every expectation we had. Truly in a class of their own."
            </p>
            <div class="testi-author">
                <div class="testi-avatar">JC</div>
                <div>
                    <div class="testi-name">James Chen</div>
                    <div class="testi-role">CEO, Meridian Corporation · Singapore</div>
                </div>
            </div>
        </div>
        <div class="testi-right fu" style="transition-delay:.15s">
            <div class="testi-stat">
                <div class="testi-stat-val">3<span>WKS</span></div>
                <div class="testi-stat-key">Ahead of Schedule</div>
            </div>
            <div class="testi-stat">
                <div class="testi-stat-val">0<span>%</span></div>
                <div class="testi-stat-key">Budget Overrun</div>
            </div>
            <div class="testi-stat">
                <div class="testi-stat-val">LEED<span>★</span></div>
                <div class="testi-stat-key">Platinum Certification</div>
            </div>
            <div class="testi-stat">
                <div class="testi-stat-val">36<span>F</span></div>
                <div class="testi-stat-key">Storeys Completed</div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section id="pricing">
        <div class="fu">
            <div class="stag">Engagement Models</div>
            <h2 class="sec-title">HOW WE <em>PRICE</em></h2>
        </div>
        <div class="pricing-grid">
            <div class="price-card fu" style="transition-delay:.05s">
                <div class="price-tier">Design & Build</div>
                <div class="price-val">Lump<span> Sum</span></div>
                <div class="price-note">Fixed-price contract with full scope certainty</div>
                <div class="price-divider"></div>
                <div class="price-features">
                    <div class="price-feat">Single point of responsibility</div>
                    <div class="price-feat">Fixed budget commitment</div>
                    <div class="price-feat">Accelerated programme</div>
                    <div class="price-feat">In-house design team</div>
                    <div class="price-feat disabled">Flexibility for variations</div>
                    <div class="price-feat disabled">Open-book cost visibility</div>
                </div>
                <a href="#contact-cta" class="btn-outline" style="display:block;text-align:center;">Enquire</a>
            </div>

            <div class="price-card featured fu" style="transition-delay:.1s">
                <div class="price-badge">MOST POPULAR</div>
                <div class="price-tier">Construction Management</div>
                <div class="price-val">GMP<span> + Fee</span></div>
                <div class="price-note">Guaranteed Maximum Price with open-book management</div>
                <div class="price-divider"></div>
                <div class="price-features">
                    <div class="price-feat">Full cost transparency</div>
                    <div class="price-feat">Savings share mechanism</div>
                    <div class="price-feat">Early contractor involvement</div>
                    <div class="price-feat">Flexibility for changes</div>
                    <div class="price-feat">Value engineering input</div>
                    <div class="price-feat">Shared risk/reward</div>
                </div>
                <a href="#contact-cta" class="btn-primary" style="display:block;text-align:center;">Get a Quote</a>
            </div>

            <div class="price-card fu" style="transition-delay:.15s">
                <div class="price-tier">Traditional Contract</div>
                <div class="price-val">Bill of<span> Qty</span></div>
                <div class="price-note">Competitive tender against employer's design</div>
                <div class="price-divider"></div>
                <div class="price-features">
                    <div class="price-feat">Competitive pricing</div>
                    <div class="price-feat">Detailed tender breakdown</div>
                    <div class="price-feat">Client retains design control</div>
                    <div class="price-feat">Variation management</div>
                    <div class="price-feat disabled">Early contractor input</div>
                    <div class="price-feat disabled">Guaranteed max price</div>
                </div>
                <a href="#contact-cta" class="btn-outline" style="display:block;text-align:center;">Enquire</a>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq">
        <div class="fu">
            <div class="stag">Common Questions</div>
            <h2 class="sec-title">FAQ</h2>
        </div>
        <div class="faq-list fu" style="transition-delay:.1s">
            <div class="faq-item open">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <span class="faq-q-text">What is the minimum project value IRONVEIL takes on?</span>
                    <div class="faq-icon">+</div>
                </div>
                <div class="faq-a">
                    <div class="faq-a-inner">Our commercial construction division typically engages on projects valued
                        at $5 million and above. For smaller scopes, we may refer you to our renovation and retrofit
                        team, or partner contractors within our trusted network.</div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <span class="faq-q-text">How long does it take to receive a preliminary estimate?</span>
                    <div class="faq-icon">+</div>
                </div>
                <div class="faq-a">
                    <div class="faq-a-inner">Following an initial consultation, we typically provide a Class D
                        (indicative) cost estimate within 5 business days. A detailed Class B estimate requires 3–4
                        weeks and is produced after receipt of concept design drawings and a geotechnical report.</div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <span class="faq-q-text">Do you operate outside of Southeast Asia?</span>
                    <div class="faq-icon">+</div>
                </div>
                <div class="faq-a">
                    <div class="faq-a-inner">Yes. While our primary markets are Singapore, Malaysia, Indonesia,
                        Thailand, and Vietnam, we have delivered projects in Australia, the Middle East, and West
                        Africa. International projects are assessed on a case-by-case basis depending on local
                        regulatory environments and logistics.</div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <span class="faq-q-text">What warranty do you provide on completed structures?</span>
                    <div class="faq-icon">+</div>
                </div>
                <div class="faq-a">
                    <div class="faq-a-inner">All commercial construction projects come with a 24-month structural
                        defects liability period from the date of practical completion. Specific structural elements
                        such as waterproofing membranes and curtain wall systems may carry extended warranties based on
                        manufacturer terms.</div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-q" onclick="toggleFaq(this)">
                    <span class="faq-q-text">Can IRONVEIL help with obtaining building permits?</span>
                    <div class="faq-icon">+</div>
                </div>
                <div class="faq-a">
                    <div class="faq-a-inner">Absolutely. We have a dedicated regulatory affairs team experienced in
                        navigating the planning and building control requirements across all markets we operate in.
                        Permit management can be included as a full-scope service or as a stand-alone engagement prior
                        to contractor appointment.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT CTA -->
    <section id="contact-cta">
        <div class="cta-content">
            <h2 class="cta-title">READY TO<br />BUILD?</h2>
            <p class="cta-sub">Tell us about your commercial project. Our team will respond within 24 hours with a
                preliminary assessment and next-step proposal.</p>
            <div class="cta-actions">
                <a href="mailto:commercial@ironveil.com" class="btn-white">📋 Request a Quote</a>
                <a href="tel:+6562345678" class="btn-white-outline">📞 Call Us Now</a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="footer-row">
            <div class="footer-logo-wrap">
                <span class="footer-logo-dot"></span>
                IRONVEIL
            </div>
            <div class="footer-links-row">
                <a href="constructor-services.html#services">All Services</a>
                <a href="constructor-services.html#projects">Projects</a>
                <a href="constructor-services.html#about">About</a>
                <a href="constructor-services.html#contact">Contact</a>
                <a href="#">Careers</a>
                <a href="#">News</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span class="footer-copy">© 2025 IRONVEIL CONSTRUCTORS PTE. LTD. — COMMERCIAL DIVISION</span>
            <div class="footer-legal">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Sitemap</a>
            </div>
        </div>
    </footer>

    <script>
        // ─── CURSOR ───────────────────────────────────────────────────────
        const cur = document.getElementById('cur');
        const cur2 = document.getElementById('cur2');
        let mx = 0, my = 0, rx = 0, ry = 0;
        document.addEventListener('mousemove', e => {
            mx = e.clientX; my = e.clientY;
            cur.style.left = mx + 'px'; cur.style.top = my + 'px';
        });
        (function animCur() {
            rx += (mx - rx) * .12; ry += (my - ry) * .12;
            cur2.style.left = rx + 'px'; cur2.style.top = ry + 'px';
            requestAnimationFrame(animCur);
        })();
        document.querySelectorAll('a,button,.proj,.team-card,.cap-card,.spec-box').forEach(el => {
            el.addEventListener('mouseenter', () => { cur.style.width = '18px'; cur.style.height = '18px'; cur2.style.width = '52px'; cur2.style.height = '52px'; });
            el.addEventListener('mouseleave', () => { cur.style.width = '10px'; cur.style.height = '10px'; cur2.style.width = '34px'; cur2.style.height = '34px'; });
        });

        // ─── SCROLL PROGRESS ─────────────────────────────────────────────
        window.addEventListener('scroll', () => {
            const pct = window.scrollY / (document.body.scrollHeight - window.innerHeight) * 100;
            document.getElementById('progress-bar').style.width = pct + '%';
            // Sticky quote button
            document.getElementById('sticky-quote').classList.toggle('show', window.scrollY > 600);
        });

        // ─── SCROLL REVEAL ───────────────────────────────────────────────
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('in'); });
        }, { threshold: 0.08 });
        document.querySelectorAll('.fu').forEach(el => obs.observe(el));

        // ─── PHASE ACCORDION ─────────────────────────────────────────────
        function togglePhase(header) {
            const phase = header.parentElement;
            const isOpen = phase.classList.contains('open');
            document.querySelectorAll('.phase').forEach(p => p.classList.remove('open'));
            if (!isOpen) phase.classList.add('open');
        }

        // ─── FAQ ACCORDION ───────────────────────────────────────────────
        function toggleFaq(header) {
            const item = header.parentElement;
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(f => f.classList.remove('open'));
            if (!isOpen) item.classList.add('open');
        }

        // ─── SMOOTH NAV ──────────────────────────────────────────────────
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const t = document.querySelector(a.getAttribute('href'));
                if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth' }); }
            });
        });

        // ─── NAV BORDER ON SCROLL ────────────────────────────────────────
        window.addEventListener('scroll', () => {
            document.querySelector('nav').style.borderBottomColor =
                window.scrollY > 60 ? 'rgba(201,75,26,.35)' : 'rgba(201,75,26,.18)';
        });
    </script>
</body>

</html>
