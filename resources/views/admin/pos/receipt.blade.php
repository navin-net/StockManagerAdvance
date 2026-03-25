<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sale->reference }}</title>
    <!-- CSS Files -->
    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('backend/DataTables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style-custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700&display=swap');

        /* ══════════════════════════════════════════════
   PAGE WRAPPER
══════════════════════════════════════════════ */
        .rcpt-page {
            min-height: 100vh;
            background: #f7f7f7;
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(14, 165, 233, 0.08) 0%, transparent 60%);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 16px 80px;
            font-family: 'DM Sans', sans-serif;
        }

        /* ══════════════════════════════════════════════
   TOP ACTION BAR
══════════════════════════════════════════════ */
        .rcpt-topbar {
            width: 100%;
            max-width: 560px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            animation: fadeDown .4s ease both;
        }

        .rcpt-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            padding: 7px 14px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            transition: all .15s ease;
            background: rgba(255, 255, 255, 0.03);
        }

        .rcpt-back:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.06);
        }

        .rcpt-actions {
            display: flex;
            gap: 8px;
        }

        .rcpt-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all .15s ease;
            text-decoration: none;
        }

        .rcpt-action-btn--print {
            background: #0ea5e9;
            color: #fff;
        }

        .rcpt-action-btn--print:hover {
            background: #0284c7;
            color: #fff;
        }

        .rcpt-action-btn--new {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
        }

        .rcpt-action-btn--new:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .rcpt-action-btn--void {
            background: rgba(244, 63, 94, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.25);
            color: #f43f5e;
        }

        .rcpt-action-btn--void:hover {
            background: rgba(244, 63, 94, 0.2);
        }

        /* ══════════════════════════════════════════════
   STATUS BADGE
══════════════════════════════════════════════ */
        .rcpt-status-wrap {
            width: 100%;
            max-width: 560px;
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            animation: fadeDown .4s .05s ease both;
        }

        .rcpt-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .rcpt-status--paid {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
        }

        .rcpt-status--partial {
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #f59e0b;
        }

        .rcpt-status--unpaid {
            background: rgba(244, 63, 94, 0.12);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #f43f5e;
        }

        .rcpt-status--voided {
            background: rgba(107, 114, 128, 0.12);
            border: 1px solid rgba(107, 114, 128, 0.3);
            color: #9ca3af;
        }

        .rcpt-status__dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse 2s infinite;
        }

        .rcpt-status--voided .rcpt-status__dot {
            animation: none;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(.75);
            }
        }

        /* ══════════════════════════════════════════════
   RECEIPT CARD  — thermal paper aesthetic
══════════════════════════════════════════════ */
        .rcpt-card {
            width: 100%;
            max-width: 560px;
            background: #fafaf7;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
            box-shadow:
                0 0 0 1px rgba(0, 0, 0, 0.08),
                0 32px 80px rgba(0, 0, 0, 0.5),
                0 4px 16px rgba(0, 0, 0, 0.3);
            animation: riseUp .5s .1s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes riseUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Torn top edge */
        .rcpt-card::before {
            content: '';
            display: block;
            height: 12px;
            background:
                radial-gradient(circle at 10px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 30px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 50px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 70px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 90px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 110px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 130px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 150px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 170px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 190px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 210px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 230px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 250px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 270px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 290px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 310px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 330px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 350px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 370px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 390px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 410px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 430px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 450px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 470px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 490px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 510px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 530px 0, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 550px 0, #0f0f14 10px, transparent 11px);
            background-color: #fafaf7;
        }

        /* Torn bottom edge */
        .rcpt-card::after {
            content: '';
            display: block;
            height: 12px;
            background:
                radial-gradient(circle at 10px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 30px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 50px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 70px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 90px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 110px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 130px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 150px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 170px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 190px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 210px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 230px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 250px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 270px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 290px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 310px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 330px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 350px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 370px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 390px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 410px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 430px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 450px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 470px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 490px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 510px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 530px 100%, #0f0f14 10px, transparent 11px),
                radial-gradient(circle at 550px 100%, #0f0f14 10px, transparent 11px);
            background-color: #fafaf7;
        }

        /* Paper texture overlay */
        .rcpt-paper {
            padding: 32px 40px 28px;
            position: relative;
            color: #1a1a1a;
            font-family: 'Courier Prime', 'Courier New', monospace;
        }

        .rcpt-paper::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* ── Store Header ── */
        .rcpt-store {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 18px;
            border-bottom: 1px dashed #c8c8c0;
        }

        .rcpt-store__logo {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            margin: 0 auto 10px;
            overflow: hidden;
            border: 2px solid #e8e8e0;
        }

        .rcpt-store__logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rcpt-store__logo-placeholder {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            /* background: #1a1a1a; */
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            color: #fff;
            letter-spacing: 2px;
        }

        .rcpt-store__name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 26px;
            letter-spacing: 4px;
            color: #1a1a1a;
            line-height: 1;
            margin-bottom: 4px;
        }

        .rcpt-store__tagline {
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            color: #888;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ── Reference block ── */
        .rcpt-ref {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 18px;
            border-bottom: 1px dashed #c8c8c0;
        }

        .rcpt-ref__num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px;
            letter-spacing: 3px;
            color: #1a1a1a;
        }

        .rcpt-ref__meta {
            font-size: 11px;
            color: #888;
            margin-top: 4px;
            line-height: 1.7;
        }

        /* ── Customer / Cashier row ── */
        .rcpt-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 20px;
            margin-bottom: 20px;
            padding-bottom: 18px;
            border-bottom: 1px dashed #c8c8c0;
            font-size: 11px;
        }

        .rcpt-info-item__label {
            font-family: 'DM Sans', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #aaa;
            margin-bottom: 2px;
        }

        .rcpt-info-item__value {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a1a;
            font-family: 'DM Sans', sans-serif;
        }

        /* ── Items table ── */
        .rcpt-items-head {
            display: grid;
            grid-template-columns: 1fr 50px 80px;
            font-family: 'DM Sans', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #aaa;
            padding: 0 0 8px;
            border-bottom: 1px solid #1a1a1a;
            margin-bottom: 8px;
        }

        .rcpt-items-head span:nth-child(2) {
            text-align: center;
        }

        .rcpt-items-head span:last-child {
            text-align: right;
        }

        .rcpt-item {
            display: grid;
            grid-template-columns: 1fr 50px 80px;
            padding: 7px 0;
            border-bottom: 1px dotted #ddddd5;
            align-items: center;
            animation: fadeItem .3s ease both;
        }

        .rcpt-item:last-child {
            border-bottom: none;
        }

        @keyframes fadeItem {
            from {
                opacity: 0;
                transform: translateX(-6px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .rcpt-item__name {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a1a;
            font-family: 'Courier Prime', monospace;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 8px;
        }

        .rcpt-item__unit {
            font-size: 10px;
            color: #999;
            margin-top: 2px;
            font-family: 'DM Sans', sans-serif;
        }

        .rcpt-item__qty {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: #555;
            font-family: 'Courier Prime', monospace;
        }

        .rcpt-item__total {
            text-align: right;
            font-size: 13px;
            font-weight: 700;
            color: #1a1a1a;
            font-family: 'Courier Prime', monospace;
        }

        /* Items section */
        .rcpt-items-wrap {
            margin-bottom: 6px;
        }

        /* ── Totals ── */
        .rcpt-totals {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid #1a1a1a;
        }

        .rcpt-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            font-size: 12px;
            color: #555;
            font-family: 'DM Sans', sans-serif;
        }

        .rcpt-total-row span:last-child {
            font-family: 'Courier Prime', monospace;
            font-size: 12px;
            color: #1a1a1a;
            font-weight: 700;
        }

        .rcpt-total-row--disc span:last-child {
            color: #16a34a;
        }

        .rcpt-total-row--grand {
            margin-top: 8px;
            padding-top: 10px;
            border-top: 2px solid #1a1a1a;
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .rcpt-total-row--grand span:last-child {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            letter-spacing: 1px;
            color: #1a1a1a;
        }

        /* ── Payment info ── */
        .rcpt-payment {
            margin-top: 16px;
            padding: 14px 16px;
            background: #f0f0eb;
            border-radius: 6px;
            border: 1px dashed #c8c8c0;
        }

        .rcpt-payment__grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .rcpt-payment__item {
            text-align: center;
        }

        .rcpt-payment__label {
            font-family: 'DM Sans', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #aaa;
            margin-bottom: 3px;
        }

        .rcpt-payment__value {
            font-family: 'Courier Prime', monospace;
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .rcpt-payment__value--green {
            color: #16a34a;
        }

        .rcpt-payment__method {
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #c8c8c0;
        }

        .rcpt-payment__method-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            background: #1a1a1a;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── Note ── */
        .rcpt-note {
            margin-top: 14px;
            padding: 10px 14px;
            background: #f5f5f0;
            border-left: 3px solid #1a1a1a;
            font-size: 11px;
            color: #666;
            font-family: 'DM Sans', sans-serif;
            font-style: italic;
            border-radius: 0 4px 4px 0;
        }

        /* ── Footer ── */
        .rcpt-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px dashed #c8c8c0;
        }

        .rcpt-footer__thankyou {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 20px;
            letter-spacing: 4px;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .rcpt-footer__sub {
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            color: #aaa;
            line-height: 1.6;
            letter-spacing: .5px;
        }

        /* Barcode decorative lines */
        .rcpt-barcode {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 2px;
            height: 40px;
            margin: 16px 0 8px;
        }

        .rcpt-barcode span {
            display: inline-block;
            background: #1a1a1a;
            width: 2px;
            border-radius: 1px;
        }

        /* ── VOID overlay ── */
        .rcpt-void-stamp {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-18deg);
            font-family: 'Bebas Neue', sans-serif;
            font-size: 64px;
            letter-spacing: 6px;
            color: rgba(244, 63, 94, 0.18);
            border: 6px solid rgba(244, 63, 94, 0.18);
            border-radius: 8px;
            padding: 4px 20px;
            pointer-events: none;
            white-space: nowrap;
            z-index: 10;
        }

        /* ═══════════════════════════════
           TOP BAR
        ═══════════════════════════════ */
        .topbar {
            width: 100%;
            max-width: 480px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-mid);
            text-decoration: none;
            padding: 6px 12px;
            border: 1px solid  #e2e2ea;
            border-radius: 6px;
            background: #ffffff;
            transition: all .15s;
        }
        .btn-back:hover { color: var(--ink); border-color: #bbbbc8; }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 6px;
            border: none;
            background:#1a1a2e;;
            color: #fff;
            cursor: pointer;
            transition: opacity .15s;
            font-family: 'Outfit', sans-serif;
        }
        .btn-print:hover { opacity: .85; }

/* ── WiFi Info ── */
.rcpt-wifi {
    margin-top: 14px;
    padding: 12px 16px;
    background: #f0f0eb;
    border-radius: 6px;
    border: 1px dashed #c8c8c0;
    text-align: center;
}

.rcpt-wifi__label {
    font-family: 'DM Sans', sans-serif;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #aaa;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.rcpt-wifi__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}

.rcpt-wifi__item-label {
    font-family: 'DM Sans', sans-serif;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #aaa;
    margin-bottom: 2px;
}

.rcpt-wifi__item-value {
    font-family: 'Courier Prime', monospace;
    font-size: 13px;
    font-weight: 700;
    color: #1a1a1a;
    word-break: break-all;
}
        /* ══════════════════════════════════════════════
   PRINT STYLES
══════════════════════════════════════════════ */
/* ══════════════════════════════════════════════
   PRINT STYLES
══════════════════════════════════════════════ */

@media print {

    body {
        background: #fff !important;
        margin: 0;
        padding: 0;
    }

    .rcpt-page {
        background: #fff !important;
        padding: 0;
        min-height: auto;
    }

    /* Hide UI elements when printing */
    .rcpt-topbar,
    .modal,
    .btn,
    .rcpt-actions {
        display: none !important;
    }

    /* Receipt card print layout */
    .rcpt-card {
        box-shadow: none !important;
        border: none !important;
        max-width: 80mm;
        width: 100%;
        margin: auto;
    }

    .rcpt-paper {
        padding: 12px 14px !important;
        font-size: 12px;
    }

    /* Remove decorative torn edges */
    .rcpt-card::before,
    .rcpt-card::after {
        display: none !important;
    }

    /* Remove background textures */
    .rcpt-paper::before {
        display: none !important;
    }

    /* Better print readability */
    .rcpt-store__name {
        font-size: 20px;
        letter-spacing: 2px;
    }

    .rcpt-ref__num {
        font-size: 16px;
    }

    .rcpt-item {
        font-size: 11px;
    }

    .rcpt-total-row--grand span:last-child {
        font-size: 22px;
    }

    /* Force black text for printers */
    * {
        color: #000 !important;
    }

    /* Remove animations */
    * {
        animation: none !important;
        transition: none !important;
    }
    .no-print {
        display: none !important;
    }
    /* Page size for thermal printer */
    @page {
        size: 80mm auto;
        margin: 5mm;
    }
}
    </style>
</head>

<body>


    @php
        $payment = $sale->payments->first();
        $amountPaid = $sale->payments->sum('amount');
        $change = $payment?->pos_balance ?? 0;
        $subtotal = $sale->subtotal ?? $sale->total_amount;
        $discount = $sale->discount ?? 0;
        $tax = $sale->tax ?? 0;
        $grandTotal = $sale->total_amount;
        $method = $payment?->method ?? 'cash';
        $statusMap = [
            'paid' => ['label' => 'Paid', 'class' => 'rcpt-status--paid'],
            'partial' => ['label' => 'Partial', 'class' => 'rcpt-status--partial'],
            'unpaid' => ['label' => 'Unpaid', 'class' => 'rcpt-status--unpaid'],
            'voided' => ['label' => 'Voided', 'class' => 'rcpt-status--voided'],
        ];
        $statusInfo = $statusMap[$sale->payment_status] ?? $statusMap['unpaid'];
        $methodIcons = ['cash' => 'bi-cash-stack', 'card' => 'bi-credit-card-2-front', 'qr' => 'bi-qr-code-scan'];
        $methodIcon = $methodIcons[$method] ?? 'bi-cash-stack';

        /* Decorative barcode heights */
        $barHeights = [];
        srand(crc32($sale->reference));
        for ($i = 0; $i < 42; $i++) {
            $barHeights[] = rand(14, 38);
        }
        srand();
    @endphp

    <div class="rcpt-page">
        <div class="topbar">
<div style="display:flex; gap:8px;" class="no-print">
    <a href="{{ route('pos.index') }}" class="btn-print" style="background:#16a34a; text-decoration:none;">
        <i class="bi bi-plus-lg"></i> New POS
    </a>
    <button onclick="window.print()" class="btn-print">
        <i class="bi bi-printer"></i> Print
    </button>
</div>
        </div>
        <br>
        {{-- ════════════════════ RECEIPT CARD ════════════════════ --}}
        <div class="rcpt-card">
            <div class="rcpt-paper">
                {{-- Void stamp --}}
                @if($sale->status === 'voided')
                    <div class="rcpt-void-stamp">VOIDED</div>
                @endif

                {{-- Store header --}}
                <div class="rcpt-store">
                    <div class="rcpt-store__logo-placeholder">
                        <img src="{{ asset($biller->logo ?? '') }}"
                            style="width: 100%;"
                            onerror="this.outerHTML='<span class=\'fw-bold text-dark text-uppercase\'>StockManagment</span>'">
                    </div>
                    {{-- <div class="rcpt-store__name">{{ config('app.name', 'My Store') }}</div> --}}
                    {{-- <div class="rcpt-store__tagline">Point of Sale Receipt</div> --}}
                </div>

                {{-- Reference block --}}
                <div class="rcpt-ref">
                    <div class="rcpt-ref__num">{{ $sale->reference }}</div>
                    <div class="rcpt-ref__meta">
                        {{ \Carbon\Carbon::parse($sale->date)->format('l, F j, Y') }}
                        {{ $payment?->paid_at?->format('g:i A') ?? now()->format('g:i A') }}
                        &nbsp;·&nbsp;
                    </div>
                </div>

                {{-- Customer & Cashier --}}
                <div class="rcpt-info-grid">
                    <div class="rcpt-info-item">
                        <div class="rcpt-info-item__label">{{ __('messages.customer') }}</div>
                        <div class="rcpt-info-item__value">
                            {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                        </div>
                    </div>
                    <div class="rcpt-info-item" >
                        <div class="rcpt-info-item__label">Cashier</div>
                        <div class="rcpt-info-item__value">
                            {{ $sale->user?->name ?? auth()->user()->name }}
                        </div>
                    </div>
                    @if($sale->customer?->email)
                        <div class="rcpt-info-item">
                            <div class="rcpt-info-item__label">{{ __('messages.email') }}</div>
                            <div class="rcpt-info-item__value" style="font-size:11px;">
                                {{ $sale->customer->email }}
                            </div>
                        </div>
                    @endif
                    @if($sale->customer?->phone)
                        <div class="rcpt-info-item" >
                            <div class="rcpt-info-item__label">{{ __('messages.phone') }}</div>
                            <div class="rcpt-info-item__value">{{ $sale->customer->phone }}</div>
                        </div>
                    @endif
                </div>

                {{-- Items --}}
                <div class="rcpt-items-wrap">
                    <div class="rcpt-items-head">
                        <span>Item</span>
                        <span>Qty</span>
                        <span>Total</span>
                    </div>

                    @foreach($sale->items as $index => $item)
                        <div class="rcpt-item" style="animation-delay: {{ $index * 0.04 }}s">
                            <div>
                                <div class="rcpt-item__name">{{ $item->product_name ?? $item->product?->name }}</div>
                                <div class="rcpt-item__unit">${{ number_format($item->sale_price, 2) }} each</div>
                            </div>
                            <div class="rcpt-item__qty">× {{ $item->quantity }}</div>
                            <div class="rcpt-item__total">${{ number_format($item->sale_price * $item->quantity, 2) }}</div>
                        </div>
                    @endforeach
                </div>

                {{-- Totals --}}
                <div class="rcpt-totals">
                    <div class="rcpt-total-row">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($discount > 0)
                        <div class="rcpt-total-row rcpt-total-row--disc">
                            <span>
                                Discount
                                @if(($sale->discount_type ?? 'fixed') === 'percentage')
                                    ({{ $sale->discount_value }}%)
                                @endif
                            </span>
                            <span>−${{ number_format($discount, 2) }}</span>
                        </div>
                    @endif

                    @if($tax > 0)
                        <div class="rcpt-total-row">
                            <span>Tax (8%)</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                    @endif

                    <div class="rcpt-total-row rcpt-total-row--grand">
                        <span>Total</span>
                        <span>${{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>

                {{-- Payment details --}}
                {{-- <div class="rcpt-payment">
                    <div class="rcpt-payment__grid">
                        <div class="rcpt-payment__item">
                            <div class="rcpt-payment__label">Paid</div>
                            <div class="rcpt-payment__value">${{ number_format($amountPaid, 2) }}</div>
                        </div>
                        <div class="rcpt-payment__item">
                            <div class="rcpt-payment__label">Balance Due</div>
                            <div class="rcpt-payment__value">
                                ${{ number_format(max(0, $grandTotal - $amountPaid), 2) }}
                            </div>
                        </div>
                        <div class="rcpt-payment__item">
                            <div class="rcpt-payment__label">Change</div>
                            <div class="rcpt-payment__value rcpt-payment__value--green">
                                ${{ number_format(max(0, $change), 2) }}
                            </div>
                        </div>
                    </div>
                    <div class="rcpt-payment__method">
                        <span class="rcpt-payment__method-badge">
                            <i class="bi {{ $methodIcon }}"></i>
                            {{ ucfirst($method) }}
                        </span>
                    </div>
                </div> --}}

                {{-- Note --}}
                @if($sale->note)
                    <div class="rcpt-note">
                        <i class="bi bi-chat-left-text me-1"></i> {{ $sale->note }}
                    </div>
                @endif

                {{-- WiFi Info --}}
                <div class="rcpt-wifi">
                    <div class="rcpt-wifi__label">
                        <i class="bi bi-wifi"></i> Free WiFi
                    </div>
                    <div class="rcpt-wifi__grid">
                        <div>
                            <div class="rcpt-wifi__item-label">Network</div>
                            <div class="rcpt-wifi__item-value">MyStore_Guest</div>
                        </div>
                        <div>
                            <div class="rcpt-wifi__item-label">Password</div>
                            <div class="rcpt-wifi__item-value">welcome2025</div>
                        </div>
                    </div>
                </div>


                {{-- Decorative barcode --}}
                {{-- <div class="rcpt-barcode" aria-hidden="true">
                    @foreach($barHeights as $h)
                        <span style="height:{{ $h }}px;"></span>
                    @endforeach
                </div> --}}

                {{-- Footer --}}
                <div class="rcpt-footer">
                    <div class="rcpt-footer__thankyou">Thank You!</div>
                    <div class="rcpt-footer__sub">
                        {{ now()->format('Y') }} · {{ config('app.name', 'My Store') }}<br>
                        Keep this receipt for your records
                    </div>
                </div>

            </div>{{-- /.rcpt-paper --}}
        </div>{{-- /.rcpt-card --}}

    </div>{{-- /.rcpt-page --}}

    {{-- Void confirm modal --}}
    <div class="modal fade" id="voidModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content"
                style="background:#13131a;border:1px solid rgba(255,255,255,0.1);border-radius:14px;color:#eeeef2;">
                <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.06);padding:14px 20px;">
                    <h5 class="modal-title" style="font-size:14px;font-weight:700;">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>Void Sale
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-3" style="font-size:13px;color:rgba(255,255,255,0.6);">
                    Void <strong id="voidRef" style="color:#fff;"></strong>?<br>
                    <small style="font-size:11px;opacity:.6;">Stock will be restored. This cannot be undone.</small>
                </div>
                <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.06);padding:12px 20px;">
                    <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="voidForm" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Yes, Void</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>

    </script>
</body>

</html>
