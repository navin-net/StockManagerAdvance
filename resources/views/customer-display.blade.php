<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Display</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Syne+Mono&family=Crimson+Pro:ital,wght@0,300;0,400;1,300;1,400&display=swap"
        rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════════
   WORLDWIDE — Global Cosmopolitan Customer Display
   Palette: deep space navy · electric cyan · warm amber
   Mood: premium airport lounge × modern art museum
═══════════════════════════════════════════════ */

        :root {
            --space: #07080f;
            --space2: #0d0f1c;
            --space3: #131629;
            --space4: #1c2038;
            --space5: #252a45;
            --aurora1: #00c9b1;
            --aurora2: #0099ff;
            --aurora3: #7b5ea7;
            --amber: #f0a500;
            --amber2: #ffc84a;
            --ivory: #f0eee8;
            --ivory2: #c8c4bc;
            --ivory3: #7a7670;
            --ivory4: #3a3830;
            --wire: rgba(0, 201, 177, 0.12);
            --wire2: rgba(0, 201, 177, 0.06);
            --font-disp: 'Syne', sans-serif;
            --font-mono: 'Syne Mono', monospace;
            --font-serif: 'Crimson Pro', serif;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: var(--font-disp);
            background: var(--space);
            color: var(--ivory);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* ── Animated dot-grid world map ── */
        #world-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            opacity: 0.18;
        }

        /* Aurora gradient overlay */
        .aurora {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                radial-gradient(ellipse 60% 40% at 20% 80%, rgba(0, 201, 177, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse 50% 35% at 80% 20%, rgba(0, 153, 255, 0.07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 30% at 50% 50%, rgba(123, 94, 167, 0.05) 0%, transparent 70%);
            animation: aurora-shift 12s ease-in-out infinite alternate;
        }

        @keyframes aurora-shift {
            0% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.7;
            }
        }

        /* Scanline texture */
        .scanlines {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background: repeating-linear-gradient(0deg,
                    transparent,
                    transparent 2px,
                    rgba(0, 0, 0, 0.03) 2px,
                    rgba(0, 0, 0, 0.03) 4px);
        }

        /* everything above bg */
        header,
        #idle,
        #order,
        #thankyou {
            position: relative;
            z-index: 1;
        }

        /* ══════════════ HEADER ══════════════ */
        header {
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            border-bottom: 1px solid var(--wire);
            background: rgba(7, 8, 15, 0.85);
            backdrop-filter: blur(16px);
            flex-shrink: 0;
        }

        .hd-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Animated globe icon */
        .globe-wrap {
            width: 34px;
            height: 34px;
            border: 1px solid rgba(0, 201, 177, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
        }

        .globe-wrap::before {
            content: '';
            position: absolute;
            inset: 3px;
            border-radius: 50%;
            border: 1px solid rgba(0, 201, 177, 0.15);
        }

        .globe-wrap::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 1px;
            background: rgba(0, 201, 177, 0.25);
            top: 50%;
        }

        .globe-meridian {
            position: absolute;
            width: 1px;
            height: 100%;
            background: rgba(0, 201, 177, 0.2);
            left: 50%;
            border-radius: 50%;
        }

        .globe-dot {
            width: 5px;
            height: 5px;
            background: var(--aurora1);
            border-radius: 50%;
            animation: globe-orbit 4s linear infinite;
            position: absolute;
        }

        @keyframes globe-orbit {
            0% {
                transform: translateX(12px) translateY(0px);
                opacity: 1;
            }

            25% {
                transform: translateX(0px) translateY(-8px);
                opacity: 0.7;
            }

            50% {
                transform: translateX(-12px) translateY(0px);
                opacity: 0.3;
            }

            75% {
                transform: translateX(0px) translateY(8px);
                opacity: 0.7;
            }

            100% {
                transform: translateX(12px) translateY(0px);
                opacity: 1;
            }
        }

        .shop-name {
            font-family: var(--font-disp);
            font-size: 15px;
            font-weight: 700;
            color: var(--ivory);
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .shop-tagline {
            font-size: 9px;
            color: var(--ivory3);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            font-weight: 400;
            margin-top: 2px;
        }

        /* Timezone strip */
        .tz-strip {
            display: flex;
            gap: 0;
        }

        .tz-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 14px;
            border-left: 1px solid var(--wire2);
        }

        .tz-city {
            font-family: var(--font-mono);
            font-size: 8px;
            color: var(--ivory3);
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .tz-time {
            font-family: var(--font-mono);
            font-size: 13px;
            color: var(--ivory2);
            letter-spacing: 0.06em;
        }

        .tz-time.local {
            color: var(--aurora1);
        }

        .hd-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .live-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            border: 1px solid var(--wire);
            border-radius: 999px;
            padding: 5px 13px;
            background: rgba(0, 201, 177, 0.05);
        }

        .live-dot {
            width: 5px;
            height: 5px;
            background: var(--aurora1);
            border-radius: 50%;
            animation: blink 2.5s ease infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }

        .live-text {
            font-size: 9px;
            font-weight: 600;
            color: var(--aurora1);
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        /* ══════════════ IDLE SCREEN ══════════════ */
        #idle {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Rotating multilingual greeting */
        .greeting-ring {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            margin-bottom: 8px;
        }

        .greeting-word {
            font-family: var(--font-serif);
            font-size: 96px;
            font-weight: 300;
            font-style: italic;
            color: var(--ivory);
            letter-spacing: -0.01em;
            line-height: 1;
            text-align: center;
            opacity: 0;
            transform: translateY(16px);
            transition: opacity 0.8s ease, transform 0.8s ease;
            position: absolute;
        }

        .greeting-word.visible {
            opacity: 1;
            transform: translateY(0);
            position: relative;
        }

        .greeting-lang {
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--aurora1);
            letter-spacing: 0.28em;
            text-transform: uppercase;
            margin-top: 10px;
            opacity: 0;
            transition: opacity 0.6s ease 0.3s;
        }

        .greeting-lang.visible {
            opacity: 1;
        }

        .idle-sub {
            font-family: var(--font-disp);
            font-size: 11px;
            font-weight: 500;
            color: var(--ivory3);
            letter-spacing: 0.24em;
            text-transform: uppercase;
            margin-top: 28px;
        }

        /* Animated arc decoration */
        .idle-arcs {
            position: absolute;
            width: 500px;
            height: 500px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .arc {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 1px solid transparent;
            animation: arc-spin linear infinite;
        }

        .arc1 {
            border-top-color: rgba(0, 201, 177, 0.15);
            animation-duration: 18s;
        }

        .arc2 {
            inset: 40px;
            border-right-color: rgba(0, 153, 255, 0.1);
            animation-duration: 24s;
            animation-direction: reverse;
        }

        .arc3 {
            inset: 80px;
            border-bottom-color: rgba(123, 94, 167, 0.12);
            animation-duration: 32s;
        }

        @keyframes arc-spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* World lang ticker at bottom of idle */
        .lang-ticker {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 36px;
            display: flex;
            align-items: center;
            overflow: hidden;
            border-top: 1px solid var(--wire2);
        }

        .ticker-track {
            display: flex;
            gap: 0;
            animation: slide-ticker 30s linear infinite;
            white-space: nowrap;
        }

        @keyframes slide-ticker {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .ticker-item {
            padding: 0 28px;
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--ivory4);
            letter-spacing: 0.1em;
            border-right: 1px solid var(--wire2);
        }

        .ticker-item.hi {
            color: var(--aurora1);
        }

        /* ══════════════ ORDER SCREEN ══════════════ */
        #order {
            flex: 1;
            display: none;
            overflow: hidden;
        }

        .order-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            height: 100%;
        }

        /* Items panel */
        .items-panel {
            padding: 30px 44px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--space5) transparent;
            display: flex;
            flex-direction: column;
        }

        .items-panel::-webkit-scrollbar {
            width: 3px;
        }

        .items-panel::-webkit-scrollbar-thumb {
            background: var(--space5);
            border-radius: 2px;
        }

        .panel-eyebrow {
            font-family: var(--font-mono);
            font-size: 9px;
            font-weight: 400;
            color: var(--aurora1);
            letter-spacing: 0.28em;
            text-transform: uppercase;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .panel-eyebrow::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, var(--wire), transparent);
        }

        /* Item card */
        .item-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 18px 0;
            border-bottom: 1px solid var(--wire2);
            animation: card-in 0.55s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .item-card:last-child {
            border-bottom: none;
        }

        @keyframes card-in {
            from {
                opacity: 0;
                transform: translateX(-16px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .item-num {
            width: 40px;
            height: 40px;
            border: 1px solid var(--wire);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-mono);
            font-size: 15px;
            color: var(--aurora1);
            flex-shrink: 0;
            background: rgba(0, 201, 177, 0.04);
            position: relative;
        }

        /* corner pip */
        .item-num::after {
            content: '';
            position: absolute;
            top: -3px;
            right: -3px;
            width: 6px;
            height: 6px;
            background: var(--aurora1);
            border-radius: 50%;
            opacity: 0.6;
        }

        .item-body {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-family: var(--font-serif);
            font-size: 22px;
            font-weight: 400;
            color: var(--ivory);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-unit {
            font-size: 11px;
            color: var(--ivory3);
            font-family: var(--font-disp);
            font-weight: 400;
            letter-spacing: 0.06em;
            margin-top: 3px;
        }

        .item-amount {
            font-family: var(--font-mono);
            font-size: 18px;
            color: var(--ivory);
            letter-spacing: 0.04em;
            flex-shrink: 0;
        }

        /* Totals panel */
        .totals-panel {
            background: var(--space2);
            border-left: 1px solid var(--wire);
            padding: 30px 28px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        /* Animated scan line on totals */
        .totals-panel::before {
            content: '';
            position: absolute;
            top: -100%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--aurora1), transparent);
            opacity: 0.3;
            animation: scan 4s linear infinite;
        }

        @keyframes scan {
            from {
                top: -2px;
            }

            to {
                top: 100%;
            }
        }

        .sum-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            font-weight: 400;
            color: var(--ivory3);
            padding: 10px 0;
            letter-spacing: 0.04em;
            border-bottom: 1px solid var(--wire2);
        }

        .sum-row:last-of-type {
            border-bottom: none;
        }

        .sum-row.disc {
            color: var(--aurora1);
        }

        .sum-row span:last-child {
            font-family: var(--font-mono);
        }

        .grand-zone {
            margin-top: auto;
        }

        /* Animated border grand total box */
        .grand-box {
            position: relative;
            padding: 22px 20px;
            margin-bottom: 18px;
            background: rgba(0, 201, 177, 0.03);
        }

        .grand-box::before,
        .grand-box::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border-color: var(--aurora1);
            border-style: solid;
            opacity: 0.5;
        }

        .grand-box::before {
            top: 0;
            left: 0;
            border-width: 1px 0 0 1px;
        }

        .grand-box::after {
            bottom: 0;
            right: 0;
            border-width: 0 1px 1px 0;
        }

        .grand-eyebrow {
            font-family: var(--font-mono);
            font-size: 9px;
            color: var(--aurora1);
            letter-spacing: 0.28em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .grand-amt {
            font-family: var(--font-serif);
            font-size: 64px;
            font-weight: 300;
            font-style: italic;
            color: var(--ivory);
            line-height: 1;
            letter-spacing: -0.01em;
        }

        .grand-amt .cur {
            font-size: 30px;
            vertical-align: super;
            font-style: normal;
            color: var(--amber2);
            margin-right: 2px;
        }

        .pay-method {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 10px;
            color: var(--ivory3);
            letter-spacing: 0.16em;
            text-transform: uppercase;
            font-weight: 500;
            font-family: var(--font-disp);
        }

        .pay-method::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--amber);
            flex-shrink: 0;
        }

        /* Currency flags strip */
        .currency-strip {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--wire2);
        }

        .cur-tag {
            font-family: var(--font-mono);
            font-size: 9px;
            color: var(--ivory4);
            border: 1px solid var(--wire2);
            border-radius: 2px;
            padding: 3px 7px;
            letter-spacing: 0.08em;
        }

        .cur-tag.active {
            color: var(--amber2);
            border-color: rgba(255, 200, 74, 0.3);
            background: rgba(255, 200, 74, 0.05);
        }

        /* ══════════════ THANK YOU SCREEN ══════════════ */
        #thankyou {
            flex: 1;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Expanding world rings */
        .ty-rings {
            position: absolute;
            width: 600px;
            height: 600px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .ty-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid var(--aurora1);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: ring-expand 3s ease-out infinite;
            opacity: 0;
        }

        .ty-ring:nth-child(1) {
            width: 100px;
            height: 100px;
            animation-delay: 0s;
        }

        .ty-ring:nth-child(2) {
            width: 100px;
            height: 100px;
            animation-delay: 0.8s;
            border-color: var(--aurora2);
        }

        .ty-ring:nth-child(3) {
            width: 100px;
            height: 100px;
            animation-delay: 1.6s;
            border-color: var(--aurora3);
        }

        @keyframes ring-expand {
            from {
                width: 60px;
                height: 60px;
                opacity: 0.6;
            }

            to {
                width: 500px;
                height: 500px;
                opacity: 0;
            }
        }

        .ty-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            position: relative;
        }

        .ty-icon {
            font-size: 42px;
            margin-bottom: 24px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .ty-word {
            font-family: var(--font-serif);
            font-size: 88px;
            font-weight: 300;
            font-style: italic;
            color: var(--ivory);
            letter-spacing: -0.01em;
            line-height: 1;
            margin-bottom: 6px;
        }

        .ty-lang {
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--aurora1);
            letter-spacing: 0.3em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .ty-sub {
            font-size: 11px;
            color: var(--ivory3);
            letter-spacing: 0.22em;
            text-transform: uppercase;
            font-weight: 500;
            font-family: var(--font-disp);
        }

        .ty-amt {
            font-family: var(--font-mono);
            font-size: 28px;
            color: var(--amber2);
            margin-top: 20px;
            letter-spacing: 0.08em;
        }

        /* Language dots */
        .ty-langs {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .ty-ld {
            font-family: var(--font-serif);
            font-size: 13px;
            font-style: italic;
            color: var(--ivory4);
            padding: 4px 12px;
            border: 1px solid var(--wire2);
            border-radius: 999px;
            letter-spacing: 0.04em;
            transition: color 0.3s, border-color 0.3s;
        }

        .ty-ld.hi {
            color: var(--ivory2);
            border-color: var(--wire);
        }
    </style>
</head>

<body>

    <!-- World map canvas -->
    <canvas id="world-canvas"></canvas>
    <div class="aurora"></div>
    <div class="scanlines"></div>

    <!-- ═══ HEADER ═══ -->
    <header>
        <div class="hd-left">
            <div class="globe-wrap">
                <div class="globe-meridian"></div>
                <div class="globe-dot"></div>
            </div>
            <div>
                <div class="shop-name" id="hd-shop">My Store</div>
                <div class="shop-tagline">Customer Display · Point of Sale</div>
            </div>
        </div>

        <!-- Live timezone clocks -->
        <div class="tz-strip" id="tz-strip"></div>

        <div class="hd-right">
            <div class="live-pill">
                <span class="live-dot"></span>
                <span class="live-text">Live</span>
            </div>
        </div>
    </header>

    <!-- ═══ IDLE SCREEN ═══ -->
    <div id="idle">
        <div class="idle-arcs">
            <div class="arc arc1"></div>
            <div class="arc arc2"></div>
            <div class="arc arc3"></div>
        </div>

        <div class="greeting-ring">
            <div class="greeting-word" id="g-word">Welcome</div>
            <div class="greeting-lang" id="g-lang">English · EN</div>
        </div>

        <div class="idle-sub">Your order will appear here</div>

        <div class="lang-ticker">
            <div class="ticker-track" id="ticker-track"></div>
        </div>
    </div>

    <!-- ═══ ORDER SCREEN ═══ -->
    <div id="order">
        <div class="order-grid">

            <div class="items-panel">
                <div class="panel-eyebrow">Your order</div>
                <div id="itemList"></div>
            </div>

            <div class="totals-panel">
                <div class="panel-eyebrow" style="margin-bottom:18px;">Summary</div>

                <div class="sum-row"><span>Subtotal</span><span id="dSub">$0.00</span></div>
                <div class="sum-row disc"><span>Discount</span><span id="dDisc">-$0.00</span></div>
                <div class="sum-row"><span>Tax</span><span id="dTax">$0.00</span></div>

                <div class="grand-zone">
                    <div class="grand-box">
                        <div class="grand-eyebrow">Total due</div>
                        <div class="grand-amt"><span class="cur" id="dCur">$</span><span id="dGrandNum">0.00</span>
                        </div>
                    </div>

                    <div class="pay-method" id="dPay">Cash</div>

                    <div class="currency-strip">
                        <div class="cur-tag active" id="cur-local">USD</div>
                        <div class="cur-tag">EUR</div>
                        <div class="cur-tag">GBP</div>
                        <div class="cur-tag">JPY</div>
                        <div class="cur-tag">CNY</div>
                        <div class="cur-tag">KHR</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ═══ THANK YOU SCREEN ═══ -->
    <div id="thankyou">
        <div class="ty-rings">
            <div class="ty-ring"></div>
            <div class="ty-ring"></div>
            <div class="ty-ring"></div>
        </div>
        <div class="ty-content">
            <div class="ty-icon">🌍</div>
            <div class="ty-word" id="ty-word">Thank You</div>
            <div class="ty-lang" id="ty-lang">English · EN</div>
            <div class="ty-sub">We appreciate your visit</div>
            <div class="ty-amt" id="thankAmt"></div>
            <div class="ty-langs" id="ty-langs"></div>
        </div>
    </div>

    <script>
        /* ═══════════════════════════════════════
           WORLD DOT MAP
        ═══════════════════════════════════════ */
        (function () {
            const canvas = document.getElementById('world-canvas');
            const ctx = canvas.getContext('2d');

            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                drawMap();
            }

            // Simplified world outline via latitude/longitude dot grid
            // Continents approximated as polygon regions
            const LAND = [
                // North America
                [[-170, 70], [-60, 70], [-60, 15], [-85, 15], [-95, 20], [-110, 22], [-117, 30], [-120, 40], [-125, 48], [-135, 58], [-140, 60], [-155, 60], [-165, 60], [-170, 70]],
                // South America
                [[-80, 12], [-60, 12], [-35, -5], [-35, -55], [-65, -55], [-75, -40], [-80, 0], [-80, 12]],
                // Europe
                [[-10, 70], [40, 70], [40, 35], [28, 35], [20, 40], [10, 38], [-5, 36], [-9, 38], [-10, 44], [-10, 70]],
                // Africa
                [[-18, 15], [50, 15], [50, -35], [-18, -35], [-18, 15]],
                // Asia
                [[25, 70], [145, 70], [145, 10], [100, 5], [80, 10], [60, 20], [40, 35], [25, 40], [25, 70]],
                // Australia
                [[115, -20], [153, -20], [153, -38], [115, -38], [115, -20]],
            ];

            function pointInPolygon(x, y, poly) {
                let inside = false;
                for (let i = 0, j = poly.length - 1; i < poly.length; j = i++) {
                    const xi = poly[i][0], yi = poly[i][1];
                    const xj = poly[j][0], yj = poly[j][1];
                    if (((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi)) {
                        inside = !inside;
                    }
                }
                return inside;
            }

            function lngLatToXY(lng, lat, w, h) {
                const x = (lng + 180) / 360 * w;
                const y = (90 - lat) / 180 * h;
                return [x, y];
            }

            function drawMap() {
                const w = canvas.width, h = canvas.height;
                ctx.clearRect(0, 0, w, h);

                const cols = Math.floor(w / 8);
                const rows = Math.floor(h / 6);

                for (let r = 0; r < rows; r++) {
                    for (let c = 0; c < cols; c++) {
                        const lng = (c / cols) * 360 - 180;
                        const lat = 90 - (r / rows) * 180;

                        let isLand = false;
                        for (const poly of LAND) {
                            if (pointInPolygon(lng, lat, poly)) { isLand = true; break; }
                        }

                        if (isLand) {
                            const px = c * 8 + 4;
                            const py = r * 6 + 3;
                            ctx.beginPath();
                            ctx.arc(px, py, 1.2, 0, Math.PI * 2);
                            ctx.fillStyle = '#00c9b1';
                            ctx.fill();
                        }
                    }
                }
            }

            resize();
            window.addEventListener('resize', resize);
        })();

        /* ═══════════════════════════════════════
           MULTILINGUAL GREETINGS
        ═══════════════════════════════════════ */
        const GREETINGS = [
            { word: 'សូមស្វាគមន៍', lang: 'ខ្មែរ · KM', ty: 'អរគុណ' },
            { word: 'Welcome', lang: 'English · EN', ty: 'Thank You' },
            //   { word: 'Bienvenue',      lang: 'Français · FR',  ty: 'Merci' },
            //   { word: 'Willkommen',     lang: 'Deutsch · DE',   ty: 'Danke' },
            //   { word: 'Bienvenido',     lang: 'Español · ES',   ty: 'Gracias' },
            //   { word: 'Benvenuto',      lang: 'Italiano · IT',  ty: 'Grazie' },
            //   { word: 'いらっしゃいませ', lang: '日本語 · JP',    ty: 'ありがとう' },
            //   { word: '欢迎光临',        lang: '中文 · ZH',     ty: '谢谢' },
        ];

        let greetIdx = 0;
        const gWord = document.getElementById('g-word');
        const gLang = document.getElementById('g-lang');

        function cycleGreeting() {
            greetIdx = (greetIdx + 1) % GREETINGS.length;
            const g = GREETINGS[greetIdx];

            gWord.classList.remove('visible');
            gLang.classList.remove('visible');

            setTimeout(() => {
                gWord.textContent = g.word;
                gLang.textContent = g.lang;
                gWord.classList.add('visible');
                gLang.classList.add('visible');
            }, 400);
        }

        // Init visible
        gWord.textContent = GREETINGS[0].word;
        gLang.textContent = GREETINGS[0].lang;
        gWord.classList.add('visible');
        gLang.classList.add('visible');

        setInterval(cycleGreeting, 3200);

        /* ═══════════════════════════════════════
           TICKER TAPE
        ═══════════════════════════════════════ */
        const TICKER_WORDS = [
            { t: 'Welcome', hi: true },
            //   { t: 'Bienvenue' }, { t: 'Willkommen' },
            { t: 'ស្វាគមន៍' },
            //   { t: 'Bem-vindo' }, { t: '환영합니다', hi: true },
            //   { t: 'Bienvenido' }, { t: 'Benvenuto' }, { t: '歡迎' }, { t: 'いらっしゃいませ', hi: true },
        ];

        const track = document.getElementById('ticker-track');
        const doubled = [...TICKER_WORDS, ...TICKER_WORDS];
        track.innerHTML = doubled.map(t =>
            `<span class="ticker-item${t.hi ? ' hi' : ''}">${t.t}</span>`
        ).join('');

        /* ═══════════════════════════════════════
           TIMEZONE CLOCKS
        ═══════════════════════════════════════ */
        const ZONES = [
            { city: 'LOCAL', tz: null },
            { city: 'NYC', tz: 'America/New_York' },
            { city: 'LONDON', tz: 'Europe/London' },
            { city: 'DUBAI', tz: 'Asia/Dubai' },
            { city: 'TOKYO', tz: 'Asia/Tokyo' },
        ];

        const tzStrip = document.getElementById('tz-strip');
        tzStrip.innerHTML = ZONES.map((z, i) =>
            `<div class="tz-cell">
    <div class="tz-city">${z.city}</div>
    <div class="tz-time ${i === 0 ? 'local' : ''}" id="tz-${i}">--:--</div>
  </div>`
        ).join('');

        function updateClocks() {
            ZONES.forEach((z, i) => {
                const now = new Date();
                const opts = { hour: '2-digit', minute: '2-digit', hour12: false };
                if (z.tz) opts.timeZone = z.tz;
                document.getElementById(`tz-${i}`).textContent =
                    now.toLocaleTimeString('en-GB', opts);
            });
        }
        updateClocks();
        setInterval(updateClocks, 1000);

        /* ═══════════════════════════════════════
           POS LOGIC
        ═══════════════════════════════════════ */
        function fmt(n) {
            return '$' + parseFloat(n || 0).toFixed(2);
        }

        function showIdle() {
            document.getElementById('idle').style.display = 'flex';
            document.getElementById('order').style.display = 'none';
            document.getElementById('thankyou').style.display = 'none';
        }

        function showThankYou(grand) {
            document.getElementById('idle').style.display = 'none';
            document.getElementById('order').style.display = 'none';
            document.getElementById('thankyou').style.display = 'flex';
            document.getElementById('thankAmt').textContent = fmt(grand);

            // cycle through "Thank You" translations
            const tyWords = GREETINGS.map(g => g.ty);
            let ti = 0;
            const tyW = document.getElementById('ty-word');
            const tyL = document.getElementById('ty-lang');
            const tyInterval = setInterval(() => {
                ti = (ti + 1) % GREETINGS.length;
                tyW.textContent = GREETINGS[ti].ty;
                tyL.textContent = GREETINGS[ti].lang;
            }, 900);

            // show mini language pills
            const pills = document.getElementById('ty-langs');
            pills.innerHTML = GREETINGS.slice(0, 6).map((g, i) =>
                `<div class="ty-ld ${i === 0 ? 'hi' : ''}">${g.ty}</div>`).join('');

            setTimeout(() => {
                clearInterval(tyInterval);
                showIdle();
            }, 5000);
        }

        function render(data) {
            if (!data) { showIdle(); return; }

            if (data.completed) { showThankYou(data.grand); return; }

            const hasItems = data.items && data.items.length > 0;
            if (!hasItems) { showIdle(); return; }

            document.getElementById('idle').style.display = 'none';
            document.getElementById('order').style.display = 'block';
            document.getElementById('thankyou').style.display = 'none';

            document.getElementById('itemList').innerHTML = data.items.map((item, i) => `
    <div class="item-card" style="animation-delay:${i * 0.06}s">
      <div class="item-num">${item.qty}</div>
      <div class="item-body">
        <div class="item-name">${item.name}</div>
        <div class="item-unit">${fmt(item.price)} each</div>
      </div>
      <div class="item-amount">${fmt(item.price * item.qty)}</div>
    </div>
  `).join('');

            const sub = parseFloat(data.subtotal || 0);
            const disc = parseFloat(data.discount || 0);
            const tax = parseFloat(data.tax || 0);
            const grand = parseFloat(data.grand || 0);

            document.getElementById('dSub').textContent = fmt(sub);
            document.getElementById('dDisc').textContent = '-' + fmt(disc);
            document.getElementById('dTax').textContent = fmt(tax);
            document.getElementById('dGrandNum').textContent = grand.toFixed(2);
            document.getElementById('dPay').textContent = data.pay_method || 'Cash';
        }

        // Bootstrap from localStorage
        const existing = localStorage.getItem('pos_cart');
        if (existing) try { render(JSON.parse(existing)); } catch (e) { showIdle(); }

        window.addEventListener('storage', function (e) {
            if (e.key === 'pos_cart' && e.newValue) {
                try { render(JSON.parse(e.newValue)); } catch (e) { showIdle(); }
            }
        });
    </script>
</body>

</html>
