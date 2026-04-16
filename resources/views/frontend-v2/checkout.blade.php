@extends('frontend-v2.app')

@section('title', 'Checkout')

@section('content')
    @push('style')
        <style>
            /* ════════════════════════════════════════
                               PROGRESS BAR — THE SPINE
                            ════════════════════════════════════════ */
            #progressBar {
                background: var(--white);
                border-bottom: 1px solid var(--border);
                padding: 0;
                position: sticky;
                top: 61px;
                z-index: 99;
            }

            .progress-inner {
                display: flex;
                align-items: stretch;
                position: relative;
            }

            /* horizontal line behind steps */
            .progress-line {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                left: 0;
                right: 0;
                height: 2px;
                background: var(--border);
                z-index: 0;
            }

            .progress-line-fill {
                height: 100%;
                background: var(--accent);
                transition: width .6s cubic-bezier(.4, 0, .2, 1);
                width: 0%;
            }

            .progress-step {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 18px 10px;
                cursor: pointer;
                position: relative;
                z-index: 1;
                transition: background .25s;
                background: transparent;
                border: none;
            }

            .progress-step:hover {
                background: rgba(200, 147, 90, .04);
            }

            .step-bubble {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: .82rem;
                font-weight: 700;
                border: 2px solid var(--step-todo);
                background: var(--white);
                color: var(--muted);
                transition: all .35s cubic-bezier(.4, 0, .2, 1);
                position: relative;
                z-index: 2;
            }

            .progress-step.done .step-bubble {
                background: var(--step-done);
                border-color: var(--step-done);
                color: var(--accent);
            }

            .progress-step.active .step-bubble {
                background: var(--step-active);
                border-color: var(--step-active);
                color: var(--accent);
                box-shadow: 0 0 0 5px rgba(26, 20, 16, .08);
            }

            .step-label {
                font-size: .62rem;
                letter-spacing: .1em;
                text-transform: uppercase;
                font-weight: 600;
                color: var(--muted);
                margin-top: 6px;
                transition: color .3s;
                white-space: nowrap;
            }

            .progress-step.active .step-label {
                color: var(--dark);
                font-weight: 700;
            }

            .progress-step.done .step-label {
                color: var(--accent);
            }

            /* divider chevrons between steps */
            .step-sep {
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--border);
                font-size: .7rem;
                padding: 0 4px;
                position: relative;
                z-index: 1;
            }

            /* ════════════════════════════════════════
                       MAIN LAYOUT
                    ════════════════════════════════════════ */
            #checkoutMain {
                padding: 44px 0 90px;
            }

            /* ════════════════════════════════════════
                       STEP PANELS — slide transition
                    ════════════════════════════════════════ */
            .checkout-step {
                display: none;
                animation: stepIn .4s cubic-bezier(.4, 0, .2, 1) both;
            }

            .checkout-step.active {
                display: block;
            }

            @keyframes stepIn {
                from {
                    opacity: 0;
                    transform: translateX(24px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes stepOut {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }

                to {
                    opacity: 0;
                    transform: translateX(-24px);
                }
            }

            /* ════════════════════════════════════════
                       SECTION CARD
                    ════════════════════════════════════════ */
            .sec-card {
                background: var(--white);
                border-top: 3px solid var(--accent);
                padding: 2rem 2rem 1.8rem;
                margin-bottom: 1.2rem;
            }

            .sec-card-title {
                display: flex;
                align-items: center;
                gap: 12px;
                font-family: 'Cormorant Garamond', serif;
                font-size: 1.35rem;
                font-weight: 700;
                color: var(--dark);
                margin-bottom: 1.6rem;
                padding-bottom: .9rem;
                border-bottom: 1px solid var(--border);
            }

            .title-num {
                width: 30px;
                height: 30px;
                background: var(--accent);
                color: #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: .78rem;
                font-weight: 700;
                font-family: 'DM Sans', sans-serif;
                flex-shrink: 0;
            }

            /* ════════════════════════════════════════
                       FORM CONTROLS
                    ════════════════════════════════════════ */
            .f-label {
                font-size: .68rem;
                font-weight: 700;
                letter-spacing: .12em;
                text-transform: uppercase;
                color: var(--muted);
                display: block;
                margin-bottom: .35rem;
            }

            .f-control {
                width: 100%;
                border: 1px solid var(--border);
                padding: .72rem 1rem;
                font-size: .88rem;
                font-family: 'DM Sans', sans-serif;
                color: var(--dark);
                background: var(--cream);
                outline: none;
                transition: border-color .25s, background .25s;
                border-radius: 0;
            }

            .f-control:focus {
                border-color: var(--accent);
                background: #fff;
            }

            .f-control::placeholder {
                color: #c0b8b0;
            }

            .f-control.error {
                border-color: var(--red);
            }

            .f-select {
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237A6E65' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
            }

            .error-msg {
                font-size: .7rem;
                color: var(--red);
                margin-top: 4px;
                display: none;
            }

            .f-control.error~.error-msg {
                display: block;
            }

            /* Phone flag */
            .phone-wrap {
                display: flex;
                gap: 0;
            }

            .phone-flag {
                border: 1px solid var(--border);
                border-right: none;
                background: var(--cream);
                padding: 0 12px;
                display: flex;
                align-items: center;
                font-size: .82rem;
                color: var(--dark);
                white-space: nowrap;
            }

            /* ════════════════════════════════════════
                       SHIPPING OPTIONS
                    ════════════════════════════════════════ */
            .ship-option {
                border: 1.5px solid var(--border);
                padding: 1.1rem 1.3rem;
                margin-bottom: 8px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 14px;
                transition: border-color .25s, background .25s;
                position: relative;
            }

            .ship-option:hover {
                border-color: var(--accent);
            }

            .ship-option.selected {
                border-color: var(--accent);
                background: rgba(200, 147, 90, .04);
            }

            .ship-option input[type="radio"] {
                accent-color: var(--accent);
                width: 16px;
                height: 16px;
                flex-shrink: 0;
                cursor: pointer;
            }

            .ship-name {
                font-size: .88rem;
                font-weight: 600;
                color: var(--dark);
            }

            .ship-sub {
                font-size: .74rem;
                color: var(--muted);
                margin-top: 2px;
            }

            .ship-price {
                margin-left: auto;
                font-size: .92rem;
                font-weight: 700;
                color: var(--dark);
                white-space: nowrap;
            }

            .ship-price.free {
                color: var(--green);
            }

            .ship-badge {
                position: absolute;
                top: -10px;
                left: 14px;
                background: var(--accent);
                color: #fff;
                font-size: .6rem;
                letter-spacing: .1em;
                text-transform: uppercase;
                font-weight: 700;
                padding: 2px 8px;
            }

            /* ════════════════════════════════════════
                       PAYMENT TABS
                    ════════════════════════════════════════ */
            .pay-tabs {
                display: flex;
                gap: 0;
                border: 1px solid var(--border);
                margin-bottom: 1.4rem;
            }

            .pay-tab {
                flex: 1;
                padding: 11px;
                background: transparent;
                border: none;
                border-right: 1px solid var(--border);
                font-size: .74rem;
                letter-spacing: .08em;
                text-transform: uppercase;
                font-weight: 600;
                cursor: pointer;
                transition: all .25s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                color: var(--muted);
            }

            .pay-tab:last-child {
                border-right: none;
            }

            .pay-tab.active {
                background: var(--dark);
                color: #fff;
            }

            .pay-tab i {
                font-size: 1rem;
            }

            /* Card number formatting */
            .card-wrap {
                position: relative;
            }

            .card-brand-icon {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 1.3rem;
                color: var(--muted);
                transition: color .25s;
                pointer-events: none;
            }

            .card-logos {
                display: flex;
                gap: 6px;
                margin-top: 8px;
            }

            .clogo {
                background: #f4f4f4;
                border: 1px solid #e0e0e0;
                padding: 4px 10px;
                font-size: .62rem;
                font-weight: 800;
                color: #555;
                letter-spacing: .04em;
            }

            /* ════════════════════════════════════════
                       REVIEW STEP — items list
                    ════════════════════════════════════════ */
            .review-item {
                display: grid;
                grid-template-columns: 72px 1fr auto;
                gap: 1rem;
                align-items: center;
                padding: 14px 0;
                border-bottom: 1px solid var(--border);
            }

            .review-item:last-of-type {
                border: none;
            }

            .review-thumb {
                width: 72px;
                height: 90px;
                object-fit: cover;
            }

            .review-name {
                font-weight: 600;
                font-size: .9rem;
                color: var(--dark);
            }

            .review-meta {
                font-size: .74rem;
                color: var(--muted);
                margin-top: 3px;
            }

            .review-price {
                font-family: 'Cormorant Garamond', serif;
                font-size: 1.2rem;
                font-weight: 700;
                color: var(--dark);
                white-space: nowrap;
            }

            /* Summary rows */
            .sum-row {
                display: flex;
                justify-content: space-between;
                font-size: .84rem;
                padding: 7px 0;
                border-bottom: 1px solid var(--border);
            }

            .sum-row:last-of-type {
                border: none;
            }

            .sum-label {
                color: var(--muted);
            }

            .sum-value {
                font-weight: 600;
            }

            .sum-total-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 14px 0 0;
                border-top: 2px solid var(--dark);
                margin-top: 4px;
            }

            .sum-total-label {
                font-size: .72rem;
                letter-spacing: .12em;
                text-transform: uppercase;
                font-weight: 700;
            }

            .sum-total-amount {
                font-family: 'Cormorant Garamond', serif;
                font-size: 2rem;
                font-weight: 700;
            }

            /* ════════════════════════════════════════
                       STICKY ORDER SUMMARY SIDEBAR
                    ════════════════════════════════════════ */
            .order-summary {
                background: var(--white);
                border-top: 3px solid var(--accent);
                padding: 1.8rem;
                position: sticky;
                top: 130px;
            }

            .os-title {
                font-family: 'Cormorant Garamond', serif;
                font-size: 1.25rem;
                font-weight: 700;
                margin-bottom: 1.2rem;
                padding-bottom: .8rem;
                border-bottom: 1px solid var(--border);
            }

            .os-item {
                display: flex;
                gap: 10px;
                margin-bottom: 12px;
                padding-bottom: 12px;
                border-bottom: 1px solid var(--border);
            }

            .os-item:last-of-type {
                border: none;
                margin-bottom: 6px;
            }

            .os-thumb {
                width: 54px;
                height: 68px;
                object-fit: cover;
                flex-shrink: 0;
            }

            .os-name {
                font-size: .82rem;
                font-weight: 600;
                color: var(--dark);
                line-height: 1.3;
            }

            .os-meta {
                font-size: .7rem;
                color: var(--muted);
                margin-top: 2px;
            }

            .os-price {
                font-size: .88rem;
                font-weight: 700;
                color: var(--dark);
                margin-left: auto;
                white-space: nowrap;
            }

            .os-divider {
                border: none;
                border-top: 1px solid var(--border);
                margin: 10px 0;
            }

            .os-row {
                display: flex;
                justify-content: space-between;
                font-size: .82rem;
                padding: 5px 0;
            }

            .os-lbl {
                color: var(--muted);
            }

            .os-val {
                font-weight: 600;
            }

            .os-total {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0 0;
                border-top: 2px solid var(--dark);
                margin-top: 6px;
            }

            .os-total-lbl {
                font-size: .7rem;
                letter-spacing: .12em;
                text-transform: uppercase;
                font-weight: 700;
            }

            .os-total-amt {
                font-family: 'Cormorant Garamond', serif;
                font-size: 1.8rem;
                font-weight: 700;
            }

            /* Promo */
            .promo-row {
                display: flex;
                margin-top: 12px;
                margin-bottom: 6px;
            }

            .promo-input {
                flex: 1;
                border: 1px solid var(--border);
                border-right: none;
                padding: 9px 12px;
                font-size: .82rem;
                font-family: 'DM Sans', sans-serif;
                background: var(--cream);
                outline: none;
                color: var(--dark);
                transition: border-color .25s;
            }

            .promo-input:focus {
                border-color: var(--accent);
            }

            .promo-btn {
                background: var(--dark);
                color: #fff;
                border: none;
                padding: 9px 16px;
                font-size: .72rem;
                letter-spacing: .08em;
                text-transform: uppercase;
                font-weight: 700;
                cursor: pointer;
                transition: background .3s;
                white-space: nowrap;
            }

            .promo-btn:hover {
                background: var(--accent);
            }

            .promo-applied {
                display: none;
                align-items: center;
                gap: 8px;
                font-size: .74rem;
                color: var(--accent);
                font-weight: 600;
                background: rgba(200, 147, 90, .08);
                border: 1px solid rgba(200, 147, 90, .2);
                padding: 6px 12px;
                margin-top: 4px;
            }

            .promo-applied i {
                font-size: .9rem;
            }

            /* Pickup notice */
            .pickup-notice {
                background: linear-gradient(90deg, rgba(200, 147, 90, .12), rgba(200, 147, 90, .04));
                border-left: 3px solid var(--accent);
                padding: 10px 14px;
                font-size: .78rem;
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 14px;
            }

            .pickup-notice i {
                color: var(--accent);
                flex-shrink: 0;
            }

            /* Security strip */
            .sec-strip {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
                margin-top: 14px;
                padding-top: 12px;
                border-top: 1px solid var(--border);
            }

            .sec-item {
                display: flex;
                align-items: center;
                gap: 5px;
                font-size: .66rem;
                color: var(--muted);
            }

            .sec-item i {
                color: var(--accent);
                font-size: .82rem;
            }

            /* ════════════════════════════════════════
                       NAV BUTTONS
                    ════════════════════════════════════════ */
            .step-nav {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 1.2rem;
                flex-wrap: wrap;
                gap: 10px;
            }

            .btn-back {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: transparent;
                border: 1.5px solid var(--border);
                color: var(--muted);
                padding: 12px 22px;
                font-size: .76rem;
                letter-spacing: .1em;
                text-transform: uppercase;
                font-weight: 600;
                cursor: pointer;
                transition: all .25s;
            }

            .btn-back:hover {
                border-color: var(--dark);
                color: var(--dark);
            }

            .btn-next {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: var(--dark);
                color: var(--white);
                border: none;
                padding: 14px 32px;
                font-size: .78rem;
                letter-spacing: .1em;
                text-transform: uppercase;
                font-weight: 700;
                cursor: pointer;
                transition: background .3s, gap .3s;
            }

            .btn-next:hover {
                background: var(--accent);
                gap: 14px;
            }

            .btn-place-order {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                background: var(--dark);
                color: #fff;
                border: none;
                padding: 16px;
                font-size: .8rem;
                letter-spacing: .12em;
                text-transform: uppercase;
                font-weight: 700;
                cursor: pointer;
                transition: background .3s;
                margin-top: 1rem;
            }

            .btn-place-order:hover {
                background: var(--accent);
            }

            /* ════════════════════════════════════════
                       GIFT & EXTRAS
                    ════════════════════════════════════════ */
            .gift-toggle-row {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: .9rem;
                background: var(--cream);
                border: 1px solid var(--border);
                cursor: pointer;
                font-size: .84rem;
                font-weight: 500;
                transition: border-color .25s;
            }

            .gift-toggle-row:hover {
                border-color: var(--accent);
            }

            .gift-toggle-row input {
                accent-color: var(--accent);
                width: 16px;
                height: 16px;
            }

            .gift-box {
                display: none;
                padding: 1rem;
                background: #fff;
                border: 1px solid var(--border);
                border-top: none;
            }

            textarea.f-control {
                resize: vertical;
                min-height: 80px;
            }

            /* Express checkout */
            .express-row {
                display: flex;
                gap: 8px;
                margin-bottom: 1rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid var(--border);
            }

            .express-btn {
                flex: 1;
                padding: 10px;
                background: #fff;
                border: 1.5px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                font-size: .74rem;
                font-weight: 700;
                cursor: pointer;
                transition: border-color .25s, box-shadow .25s;
            }

            .express-btn:hover {
                border-color: var(--dark);
                box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
            }

            .express-btn.paypal {
                color: #003087;
            }

            .express-btn.apple {
                background: #000;
                color: #fff;
                border-color: #000;
            }

            .express-btn.google {
                color: #4285F4;
            }

            .or-divider {
                text-align: center;
                position: relative;
                font-size: .7rem;
                letter-spacing: .1em;
                text-transform: uppercase;
                color: var(--muted);
                margin-bottom: 1rem;
            }

            .or-divider::before,
            .or-divider::after {
                content: '';
                position: absolute;
                top: 50%;
                width: 44%;
                height: 1px;
                background: var(--border);
            }

            .or-divider::before {
                left: 0;
            }

            .or-divider::after {
                right: 0;
            }

            /* ════════════════════════════════════════
                       ADDRESS SAVED CARD
                    ════════════════════════════════════════ */
            .saved-address {
                border: 1.5px solid var(--border);
                padding: 1rem 1.2rem;
                margin-bottom: 8px;
                cursor: pointer;
                display: flex;
                align-items: flex-start;
                gap: 12px;
                transition: border-color .25s;
            }

            .saved-address:hover {
                border-color: var(--accent);
            }

            .saved-address.selected {
                border-color: var(--accent);
                background: rgba(200, 147, 90, .04);
            }

            .saved-address input {
                accent-color: var(--accent);
                margin-top: 3px;
                width: 16px;
                height: 16px;
                flex-shrink: 0;
            }

            .addr-name {
                font-weight: 700;
                font-size: .86rem;
            }

            .addr-line {
                font-size: .78rem;
                color: var(--muted);
                line-height: 1.6;
            }

            .addr-default {
                font-size: .62rem;
                letter-spacing: .08em;
                text-transform: uppercase;
                font-weight: 700;
                background: rgba(200, 147, 90, .12);
                color: var(--accent);
                padding: 2px 8px;
                margin-left: 8px;
            }

            .or-new-address {
                font-size: .75rem;
                letter-spacing: .1em;
                text-transform: uppercase;
                font-weight: 600;
                color: var(--accent);
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                margin-top: 8px;
                cursor: pointer;
                background: none;
                border: none;
            }

            /* ════════════════════════════════════════
                       SUCCESS MODAL
                    ════════════════════════════════════════ */
            #successOverlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .65);
                z-index: 9999;
                display: none;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(6px);
            }

            #successOverlay.show {
                display: flex;
                animation: fadeIn .4s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            .success-box {
                background: var(--white);
                max-width: 520px;
                width: 92%;
                border-top: 4px solid var(--accent);
                padding: 3rem 2.5rem;
                text-align: center;
                animation: slideUp .5s cubic-bezier(.4, 0, .2, 1);
            }

            @keyframes slideUp {
                from {
                    transform: translateY(40px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .success-check {
                width: 72px;
                height: 72px;
                background: var(--accent);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                color: #fff;
                margin: 0 auto 1.6rem;
                animation: checkPop .5s .3s cubic-bezier(.4, 0, .2, 1) both;
            }

            @keyframes checkPop {
                from {
                    transform: scale(.4);
                    opacity: 0;
                }

                to {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .success-title {
                font-family: 'Cormorant Garamond', serif;
                font-size: 2.4rem;
                font-weight: 700;
                margin-bottom: .5rem;
            }

            .success-sub {
                font-size: .88rem;
                color: var(--muted);
                line-height: 1.75;
                margin-bottom: 1.5rem;
            }

            .success-order-badge {
                display: inline-block;
                background: var(--cream);
                font-size: .78rem;
                letter-spacing: .14em;
                text-transform: uppercase;
                font-weight: 700;
                padding: 10px 22px;
                color: var(--dark);
                margin-bottom: 1.5rem;
                border: 1px solid var(--border);
            }

            .success-items {
                display: flex;
                justify-content: center;
                gap: 8px;
                margin: 1rem 0 1.5rem;
                flex-wrap: wrap;
            }

            .success-thumb {
                width: 56px;
                height: 70px;
                object-fit: cover;
                border: 2px solid var(--border);
            }

            .success-actions {
                display: flex;
                gap: 10px;
                justify-content: center;
                flex-wrap: wrap;
            }

            .btn-success-primary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: var(--dark);
                color: #fff;
                padding: 13px 26px;
                font-size: .78rem;
                letter-spacing: .1em;
                text-transform: uppercase;
                font-weight: 700;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: background .3s;
            }

            .btn-success-primary:hover {
                background: var(--accent);
                color: #fff;
            }

            .btn-success-outline {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: transparent;
                color: var(--dark);
                border: 1.5px solid var(--border);
                padding: 12px 22px;
                font-size: .78rem;
                letter-spacing: .1em;
                text-transform: uppercase;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
                transition: all .3s;
            }

            .btn-success-outline:hover {
                border-color: var(--dark);
            }

            /* ════════════════════════════════════════
               STEP 4 — Review summary panel
            ════════════════════════════════════════ */
            .review-section {
                background: var(--cream);
                border: 1px solid var(--border);
                padding: 1.2rem 1.4rem;
                margin-bottom: 1rem;
            }

            .review-section-title {
                font-size: .68rem;
                letter-spacing: .14em;
                text-transform: uppercase;
                font-weight: 700;
                color: var(--accent);
                margin-bottom: .8rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .edit-link {
                font-size: .68rem;
                letter-spacing: .06em;
                text-transform: uppercase;
                font-weight: 700;
                color: var(--muted);
                text-decoration: none;
                transition: color .25s;
                cursor: pointer;
                background: none;
                border: none;
            }

            .edit-link:hover {
                color: var(--accent);
            }

            .review-info {
                font-size: .84rem;
                color: var(--dark);
                line-height: 1.7;
            }


            /* ════════════════════════════════════════
               RESPONSIVE
            ════════════════════════════════════════ */
            @media(max-width:992px) {
                .order-summary {
                    position: static;
                    margin-top: 1.5rem;
                }
            }

            @media(max-width:768px) {
                .progress-step .step-label {
                    display: none;
                }

                .sec-card {
                    padding: 1.2rem;
                }

                .express-row {
                    flex-wrap: wrap;
                }

                .review-item {
                    grid-template-columns: 56px 1fr;
                }

                .review-price {
                    grid-column: 2;
                }
            }

            @media(max-width:480px) {
                .pay-tabs {
                    flex-wrap: wrap;
                }

                .pay-tab {
                    flex: 1 0 48%;
                }
            }
        </style>
    @endpush


    <!-- ── PAGE HERO ── -->
    <div class="page-hero">
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    {{-- <li class="breadcrumb-item"><a href="#">Women</a></li> --}}
                    <li class="breadcrumb-item active">Contact Information</li>
                </ol>
            </nav>
            <div class="page-hero-eyebrow">Curated Collection</div>
            <h1 class="page-hero-title">Contact <em>Information</em></h1>
        </div>
    </div>
    <div id="progressBar">
        <div class="container-fluid px-4">
            <div class="progress-inner">
                <div class="progress-line">
                    <div class="progress-line-fill" id="progressFill"></div>
                </div>

                <button class="progress-step active" id="pStep1" onclick="goToStep(1)">
                    <div class="step-bubble" id="bubble1">1</div>
                    <div class="step-label">Contact</div>
                </button>
                <div class="step-sep"><i class="bi bi-chevron-right"></i></div>
                <button class="progress-step" id="pStep2" onclick="goToStep(2)">
                    <div class="step-bubble" id="bubble2">2</div>
                    <div class="step-label">Shipping</div>
                </button>
                <div class="step-sep"><i class="bi bi-chevron-right"></i></div>
                <button class="progress-step" id="pStep3" onclick="goToStep(3)">
                    <div class="step-bubble" id="bubble3">3</div>
                    <div class="step-label">Delivery</div>
                </button>
                <div class="step-sep"><i class="bi bi-chevron-right"></i></div>
                <button class="progress-step" id="pStep4" onclick="goToStep(4)">
                    <div class="step-bubble" id="bubble4">4</div>
                    <div class="step-label">Payment</div>
                </button>
                <div class="step-sep"><i class="bi bi-chevron-right"></i></div>
                <button class="progress-step" id="pStep5" onclick="goToStep(5)">
                    <div class="step-bubble" id="bubble5">5</div>
                    <div class="step-label">Review</div>
                </button>
            </div>
        </div>
    </div>
    <section id="checkoutMain">
        <div class="container-fluid px-4">
            <div class="row g-4">

                <!-- ── LEFT COLUMN ── -->
                <div class="col-lg-12 col-xl-12">

                    <!-- ===================================
                     STEP 1: CONTACT INFORMATION
                =================================== -->
                    <div class="checkout-step active" id="step1">
                        <div class="sec-card">
                            <div class="sec-card-title">
                                <div class="title-num">1</div> Contact Information
                            </div>

                            <!-- Express checkout -->
                            <div class="or-divider" style="margin-bottom:.8rem;margin-top:.2rem;">Express Checkout</div>
                            <div class="express-row">
                                <button class="express-btn paypal"
                                    onclick="showToast('Redirecting to PayPal…','bi-paypal')"><i class="bi bi-paypal"></i>
                                    PayPal</button>
                                <button class="express-btn apple" onclick="showToast('Apple Pay selected','bi-apple')"><i
                                        class="bi bi-apple"></i> Pay</button>
                                <button class="express-btn google" onclick="showToast('Google Pay selected','bi-google')"><i
                                        class="bi bi-google"></i> Pay</button>
                            </div>
                            <div class="or-divider">Or fill in below</div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="f-label">First Name *</label>
                                    <input type="text" class="f-control" id="fName" placeholder="Sophie" />
                                    <div class="error-msg">Please enter your first name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="f-label">Last Name *</label>
                                    <input type="text" class="f-control" id="lName" placeholder="Laurent" />
                                    <div class="error-msg">Please enter your last name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="f-label">Email Address *</label>
                                    <input type="email" class="f-control" id="email" placeholder="hello@example.com" />
                                    <div class="error-msg">Please enter a valid email.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="f-label">Phone Number</label>
                                    <div class="phone-wrap">
                                        <div class="phone-flag">🇫🇷 +33</div>
                                        <input type="tel" class="f-control" placeholder="06 12 34 56 78" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-2"
                                        style="font-size:.82rem;color:var(--muted);cursor:pointer;">
                                        <input type="checkbox" id="guestChk"
                                            style="accent-color:var(--accent);width:15px;height:15px;" />
                                        <label for="guestChk" style="cursor:pointer;">Continue as guest &nbsp;|&nbsp; <a
                                                href="#" style="color:var(--accent);">Sign in</a> for faster future
                                            checkouts</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-nav">
                            <a href="{{ url('shop/cart') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Back to
                                Cart</a>
                            <button class="btn-next" onclick="nextStep(1)">Continue to Shipping <i
                                    class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- ===================================
                     STEP 2: SHIPPING ADDRESS
                =================================== -->
                    <div class="checkout-step" id="step2">
                        <div class="sec-card">
                            <div class="sec-card-title">
                                <div class="title-num">2</div> Shipping Address
                            </div>

                            <!-- Saved addresses -->
                            <div style="margin-bottom:1.2rem;">
                                <div
                                    style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--muted);margin-bottom:.6rem;">
                                    Saved Addresses</div>
                                <div class="saved-address selected" onclick="selectAddress(this)">
                                    <input type="radio" name="addr" checked />
                                    <div>
                                        <div class="addr-name">Sophie Laurent <span class="addr-default">Default</span>
                                        </div>
                                        <div class="addr-line">14 Rue de Rivoli, Apt 5B<br>Paris, 75001, France</div>
                                    </div>
                                </div>
                                <div class="saved-address" onclick="selectAddress(this)">
                                    <input type="radio" name="addr" />
                                    <div>
                                        <div class="addr-name">Work — Studio Office</div>
                                        <div class="addr-line">28 Avenue Montaigne<br>Paris, 75008, France</div>
                                    </div>
                                </div>
                                <button class="or-new-address" onclick="toggleNewAddress()"><i
                                        class="bi bi-plus-circle"></i> Add a new address</button>
                            </div>

                            <!-- New address form (hidden by default) -->
                            <div id="newAddressForm"
                                style="display:none;padding-top:1rem;border-top:1px solid var(--border);">
                                <div class="row g-3">
                                    <div class="col-12"><label class="f-label">Address Line 1 *</label><input type="text"
                                            class="f-control" id="addr1" placeholder="Street address, building, apt…" />
                                        <div class="error-msg">Please enter your address.</div>
                                    </div>
                                    <div class="col-12"><label class="f-label">Address Line 2</label><input type="text"
                                            class="f-control" placeholder="Suite, floor, landmark (optional)" /></div>
                                    <div class="col-md-5"><label class="f-label">City *</label><input type="text"
                                            class="f-control" placeholder="Paris" /></div>
                                    <div class="col-md-4"><label class="f-label">State / Region</label><input type="text"
                                            class="f-control" placeholder="Île-de-France" /></div>
                                    <div class="col-md-3"><label class="f-label">ZIP *</label><input type="text"
                                            class="f-control" placeholder="75001" /></div>
                                    <div class="col-12">
                                        <label class="f-label">Country *</label>
                                        <select class="f-control f-select">
                                            <option>France</option>
                                            <option>United States</option>
                                            <option>United Kingdom</option>
                                            <option>Germany</option>
                                            <option>Japan</option>
                                            <option>Australia</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center gap-2"
                                            style="font-size:.82rem;color:var(--muted);cursor:pointer;">
                                            <input type="checkbox" id="saveAddr"
                                                style="accent-color:var(--accent);width:15px;height:15px;" checked />
                                            <label for="saveAddr" style="cursor:pointer;">Save this address for future
                                                orders</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-nav">
                            <button class="btn-back" onclick="prevStep(2)"><i class="bi bi-arrow-left"></i> Back</button>
                            <button class="btn-next" onclick="nextStep(2)">Continue to Delivery <i
                                    class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- ===================================
                     STEP 3: DELIVERY METHOD
                =================================== -->
                    <div class="checkout-step" id="step3">
                        <div class="sec-card">
                            <div class="sec-card-title">
                                <div class="title-num">3</div> Delivery Method
                            </div>

                            <div class="ship-option selected" onclick="selectShip(this)" id="shipFree">
                                <input type="radio" name="ship" checked />
                                <div style="flex:1">
                                    <div class="ship-name">Standard Delivery</div>
                                    <div class="ship-sub"><i class="bi bi-calendar3 me-1"></i>Estimated: Apr 3–5, 2026
                                        &nbsp;·&nbsp; via DHL Standard</div>
                                </div>
                                <div class="ship-price free">Free</div>
                            </div>

                            <div class="ship-option" onclick="selectShip(this);updateShipping(18)" id="shipExpress">
                                <input type="radio" name="ship" />
                                <div style="flex:1">
                                    <div class="ship-name">Express Delivery</div>
                                    <div class="ship-sub"><i class="bi bi-lightning me-1"
                                            style="color:var(--accent)"></i>Estimated: Apr 1–2, 2026 &nbsp;·&nbsp; via DHL
                                        Express</div>
                                </div>
                                <div class="ship-price">$18.00</div>
                            </div>

                            <div class="ship-option" onclick="selectShip(this);updateShipping(35)" id="shipOvernight">
                                <input type="radio" name="ship" />
                                <div style="flex:1">
                                    <div class="ship-name">Overnight — Next Day</div>
                                    <div class="ship-sub"><i class="bi bi-lightning-fill me-1"
                                            style="color:var(--accent)"></i>Estimated: Apr 1, 2026 &nbsp;·&nbsp; via UPS
                                        Next Day Air</div>
                                </div>
                                <div class="ship-price">$35.00</div>
                            </div>

                            <div class="ship-option" onclick="selectShip(this);updateShipping(0)" id="shipPickup"
                                style="position:relative;">
                                <div class="ship-badge">Save 15%</div>
                                <input type="radio" name="ship" />
                                <div style="flex:1">
                                    <div class="ship-name"><i class="bi bi-bag-check me-1"
                                            style="color:var(--accent)"></i>In-Store Pickup &nbsp;·&nbsp; <span
                                            style="color:var(--green);font-weight:700;">Automatic 15% Discount</span></div>
                                    <div class="ship-sub">Ready within 2 hours &nbsp;·&nbsp; 14 Blvd Saint-Germain, Paris
                                    </div>
                                </div>
                                <div class="ship-price free">Free</div>
                            </div>

                            <div class="ship-option" onclick="selectShip(this);updateShipping(28)" id="shipIntl">
                                <input type="radio" name="ship" />
                                <div style="flex:1">
                                    <div class="ship-name">International Shipping</div>
                                    <div class="ship-sub"><i class="bi bi-globe me-1"></i>Estimated: Apr 8–14, 2026
                                        &nbsp;·&nbsp; via DHL Worldwide</div>
                                </div>
                                <div class="ship-price">$28.00</div>
                            </div>

                        </div>

                        <div class="sec-card" style="padding:1.4rem 2rem;">
                            <div
                                style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;color:var(--muted);margin-bottom:.8rem;">
                                Order Notes (Optional)</div>
                            <textarea class="f-control" rows="3"
                                placeholder="Special instructions for delivery or packaging…"></textarea>
                        </div>

                        <div class="step-nav">
                            <button class="btn-back" onclick="prevStep(3)"><i class="bi bi-arrow-left"></i> Back</button>
                            <button class="btn-next" onclick="nextStep(3)">Continue to Payment <i
                                    class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- ===================================
                     STEP 4: PAYMENT
                =================================== -->
                    <div class="checkout-step" id="step4">
                        <div class="sec-card">
                            <div class="sec-card-title">
                                <div class="title-num">4</div> Payment Details
                            </div>

                            <div class="pay-tabs" id="payTabs">
                                <button class="pay-tab active" onclick="selectPayTab(this,'card')"><i
                                        class="bi bi-credit-card-2-front"></i> Card</button>
                                <button class="pay-tab" onclick="selectPayTab(this,'paypal')"><i class="bi bi-paypal"></i>
                                    PayPal</button>
                                <button class="pay-tab" onclick="selectPayTab(this,'apple')"><i class="bi bi-apple"></i>
                                    Apple Pay</button>
                                <button class="pay-tab" onclick="selectPayTab(this,'google')"><i class="bi bi-google"></i>
                                    Google Pay</button>
                            </div>

                            <!-- Card form -->
                            <div id="payCard">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="f-label">Card Number *</label>
                                        <div class="card-wrap">
                                            <input type="text" class="f-control" id="cardNum"
                                                placeholder="1234  5678  9012  3456" maxlength="19"
                                                oninput="formatCard(this)" style="padding-right:46px;" />
                                            <i class="bi bi-credit-card-2-front card-brand-icon" id="cardBrandIcon"></i>
                                        </div>
                                        <div class="card-logos mt-1">
                                            <div class="clogo">VISA</div>
                                            <div class="clogo">MC</div>
                                            <div class="clogo">AMEX</div>
                                            <div class="clogo">DISCOVER</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="f-label">Cardholder Name *</label>
                                        <input type="text" class="f-control" id="cardName" placeholder="SOPHIE LAURENT" />
                                    </div>
                                    <div class="col-6">
                                        <label class="f-label">Expiry Date *</label>
                                        <input type="text" class="f-control" id="cardExpiry" placeholder="MM / YY"
                                            maxlength="7" oninput="formatExpiry(this)" />
                                    </div>
                                    <div class="col-6">
                                        <label class="f-label">CVV / CVC *</label>
                                        <div class="card-wrap">
                                            <input type="text" class="f-control" id="cardCvv" placeholder="•••"
                                                maxlength="4" style="padding-right:42px;" />
                                            <i class="bi bi-question-circle card-brand-icon" style="font-size:1rem;"
                                                title="3 digits on back, 4 on front for Amex"></i>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    style="display:flex;align-items:center;gap:8px;background:rgba(39,174,96,.06);border:1px solid rgba(39,174,96,.2);padding:10px 14px;margin-top:1rem;font-size:.78rem;color:var(--muted);">
                                    <i class="bi bi-shield-lock-fill"
                                        style="color:var(--green);font-size:1rem;flex-shrink:0;"></i>
                                    Your card details are encrypted with 256-bit SSL and never stored on our servers.
                                </div>
                            </div>

                            <!-- PayPal / Apple / Google placeholder -->
                            <div id="payOther"
                                style="display:none;text-align:center;padding:2.5rem 1rem;background:var(--cream);border:1px solid var(--border);">
                                <i class="bi bi-box-arrow-up-right"
                                    style="font-size:2.5rem;color:var(--accent);display:block;margin-bottom:1rem;"></i>
                                <div style="font-size:.9rem;color:var(--muted);">You'll be securely redirected to complete
                                    payment.</div>
                            </div>
                        </div>

                        <!-- Gift option -->
                        <div class="sec-card" style="padding:0;">
                            <div class="gift-toggle-row" onclick="toggleGift()">
                                <input type="checkbox" id="giftChk" style="accent-color:var(--accent)" />
                                <label for="giftChk" style="cursor:pointer;flex:1;"><i class="bi bi-gift me-2"
                                        style="color:var(--accent);"></i>This is a gift — add message &amp; premium gift
                                    wrap <span style="color:var(--muted);font-size:.8rem;">(+$8.00)</span></label>
                                <i class="bi bi-chevron-down" style="color:var(--muted);font-size:.8rem;"></i>
                            </div>
                            <div class="gift-box" id="giftBox">
                                <label class="f-label">Gift Message</label>
                                <textarea class="f-control" rows="3"
                                    placeholder="Write your personal message here…"></textarea>
                                <div style="font-size:.74rem;color:var(--muted);margin-top:6px;"><i
                                        class="bi bi-box-seam me-1" style="color:var(--accent)"></i>Luxé signature box ·
                                    Hand-folded tissue · Gold wax seal · Authenticity card</div>
                            </div>
                        </div>

                        <div class="step-nav">
                            <button class="btn-back" onclick="prevStep(4)"><i class="bi bi-arrow-left"></i> Back</button>
                            <button class="btn-next" onclick="nextStep(4)">Review Order <i
                                    class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- ===================================
                     STEP 5: REVIEW & PLACE ORDER
                =================================== -->
                    <div class="checkout-step" id="step5">
                        <div class="sec-card">
                            <div class="sec-card-title">
                                <div class="title-num">5</div> Review Your Order
                            </div>
                            <div
                                style="font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;color:var(--accent);margin-bottom:1rem;">
                                Items in Your Order</div>
                            <!-- Items -->
                            <div id="product-review"></div>


                            <!-- Confirmed details -->
                            <div class="row g-3" style="margin-bottom:1rem;">
                                <div class="col-md-4">
                                    <div class="review-section">
                                        <div class="review-section-title">Contact <button class="edit-link"
                                                onclick="goToStep(1)">Edit</button></div>
                                        <div class="review-info">Sophie Laurent<br>sophie@example.com<br>+33 6 12 34 56 78
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="review-section">
                                        <div class="review-section-title">Ship To <button class="edit-link"
                                                onclick="goToStep(2)">Edit</button></div>
                                        <div class="review-info">14 Rue de Rivoli, Apt 5B<br>Paris, 75001, France</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="review-section">
                                        <div class="review-section-title">Delivery <button class="edit-link"
                                                onclick="goToStep(3)">Edit</button></div>
                                        <div class="review-info" id="reviewDelivery">Standard — Free<br>Est. Apr 3–5, 2026
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3" style="margin-bottom:1rem;">
                                <div class="col-md-8">
                                    <div class="review-section">
                                        <div class="review-section-title">Payment Method <button class="edit-link"
                                                onclick="goToStep(4)">Edit</button></div>
                                        <div class="review-info" id="reviewPayment">Credit / Debit Card<br>•••• •••• ••••
                                            3456 ·
                                            Visa</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="review-section">
                                        <div class="review-section-title">Gift Wrap</div>
                                        <div class="review-info" id="reviewGift">Not selected</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Totals -->
                            <div class="sec-card" style="padding:1.4rem 2rem;">
                                <div class="sum-row"><span class="sum-label">Subtotal (3 items)</span><span
                                        class="sum-value">$1,021.00</span></div>
                                <div class="sum-row"><span class="sum-label">Discount (LUXE15)</span><span class="sum-value"
                                        style="color:var(--red);">−$153.15</span></div>
                                <div class="sum-row"><span class="sum-label">Shipping</span><span class="sum-value"
                                        id="reviewShipLine" style="color:var(--green);">Free</span></div>
                                <div class="sum-row"><span class="sum-label">Estimated Tax (8%)</span><span
                                        class="sum-value" id="reviewTaxLine">$70.07</span></div>
                                <div class="sum-total-row">
                                    <div class="sum-total-label">Estimated Total</div>
                                    <div class="sum-total-amount" id="reviewTotalLine">$937.92</div>
                                </div>
                            </div>

                            <div
                                style="background:rgba(200,147,90,.08);border:1px solid rgba(200,147,90,.25);padding:12px 16px;font-size:.8rem;color:var(--dark);margin-bottom:1.2rem;display:flex;align-items:flex-start;gap:10px;">
                                <i class="bi bi-info-circle-fill"
                                    style="color:var(--accent);flex-shrink:0;margin-top:2px;"></i>
                                <span>By placing your order you agree to Luxé's <a href="#"
                                        style="color:var(--accent);">Terms
                                        of Service</a> and <a href="#" style="color:var(--accent);">Privacy Policy</a>. Your
                                    payment is processed securely by our PCI-DSS Level 1 certified payment provider.</span>
                            </div>

                            <div class="step-nav">
                                <button class="btn-back" onclick="prevStep(5)"><i class="bi bi-arrow-left"></i>
                                    Back</button>
                                <button class="btn-next"
                                    style="background:var(--accent);padding:14px 40px;font-size:.84rem;"
                                    onclick="placeOrder()"><i class="bi bi-lock-fill"></i> Place Order — $937.92</button>
                            </div>
                        </div>

                    </div><!-- /col-lg-7 -->
                </div>
            </div>
    </section>


@endsection
@push('scripts')
    <script>
        /* ══════════════════════════════════════════
           STATE
        ══════════════════════════════════════════ */
        let currentStep = 1;
        const totalSteps = 5;
        let shippingCost = 0;
        let discountPct = 0;
        const subtotal = 1021.00;

        /* ══════════════════════════════════════════
           STEP NAVIGATION
        ══════════════════════════════════════════ */
        function goToStep(n) {
            // Only allow going to completed or current+1 steps
            if (n > currentStep + 1) { showToast('Please complete the current step first', 'bi-exclamation-circle'); return; }
            if (n === currentStep) return;
            animateOut(currentStep, n > currentStep);
            setTimeout(() => {
                setStep(n);
            }, 220);
        }

        function nextStep(from) {
            if (!validateStep(from)) return;
            goToStep(from + 1);
        }

        function prevStep(from) {
            goToStep(from - 1);
        }

        function animateOut(step, forward) {
            const el = document.getElementById('step' + step);
            el.style.animation = forward ? 'none' : 'none';
            el.style.opacity = '0';
            el.style.transform = forward ? 'translateX(-24px)' : 'translateX(24px)';
            el.style.transition = 'opacity .22s ease, transform .22s ease';
        }

        function setStep(n) {
            // Hide all
            for (let i = 1; i <= totalSteps; i++) {
                const el = document.getElementById('step' + i);
                el.classList.remove('active');
                el.style.opacity = '';
                el.style.transform = '';
                el.style.transition = '';
            }
            // Show target
            const target = document.getElementById('step' + n);
            target.classList.add('active');
            target.style.animation = 'stepIn .4s cubic-bezier(.4,0,.2,1) both';

            currentStep = n;
            updateProgressBar();
            updateSummaryForStep(n);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        /* ══════════════════════════════════════════
           PROGRESS BAR UPDATE
        ══════════════════════════════════════════ */
        function updateProgressBar() {
            for (let i = 1; i <= totalSteps; i++) {
                const btn = document.getElementById('pStep' + i);
                const bubble = document.getElementById('bubble' + i);
                btn.classList.remove('active', 'done');

                if (i < currentStep) {
                    btn.classList.add('done');
                    bubble.innerHTML = '<i class="bi bi-check2" style="font-size:.75rem;"></i>';
                } else if (i === currentStep) {
                    btn.classList.add('active');
                    bubble.textContent = i;
                } else {
                    bubble.textContent = i;
                }
            }
            // Fill line
            const pct = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progressFill').style.width = pct + '%';
        }

        /* ══════════════════════════════════════════
           VALIDATION
        ══════════════════════════════════════════ */
        function validateStep(step) {
            let ok = true;
            if (step === 1) {
                ok = validateField('fName') & validateField('lName') & validateEmail();
            }
            if (step === 2) {
                const newForm = document.getElementById('newAddressForm');
                if (newForm.style.display !== 'none') {
                    ok = validateField('addr1');
                }
            }
            if (step === 4) {
                const activeTab = document.querySelector('.pay-tab.active');
                if (activeTab && activeTab.textContent.trim().startsWith('Card')) {
                    ok = validateField('cardNum') & validateField('cardName') & validateField('cardExpiry') & validateField('cardCvv');
                }
            }
            if (!ok) showToast('Please fill in all required fields', 'bi-exclamation-circle');
            return ok;
        }

        function validateField(id) {
            const el = document.getElementById(id);
            if (!el) return true;
            const valid = el.value.trim().length > 0;
            el.classList.toggle('error', !valid);
            return valid;
        }

        function validateEmail() {
            const el = document.getElementById('email');
            const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value);
            el.classList.toggle('error', !valid);
            return valid;
        }

        // Clear error on input
        document.querySelectorAll('.f-control').forEach(el => {
            el.addEventListener('input', () => el.classList.remove('error'));
        });

        /* ══════════════════════════════════════════
           SHIPPING SELECTION
        ══════════════════════════════════════════ */
        function selectShip(el) {
            document.querySelectorAll('.ship-option').forEach(o => o.classList.remove('selected'));
            el.classList.add('selected');
            el.querySelector('input[type="radio"]').checked = true;
        }

        function updateShipping(cost) {
            shippingCost = cost;
            recalc();
            const el = document.getElementById('osShipping');
            if (cost === 0) { el.textContent = 'Free'; el.style.color = 'var(--green)'; }
            else { el.textContent = '$' + cost.toFixed(2); el.style.color = ''; }

            // Update review delivery text
            const opts = { 18: 'Express · $18.00\nApr 1–2, 2026', 35: 'Overnight · $35.00\nApr 1, 2026', 0: 'Standard · Free\nApr 3–5, 2026', 28: 'International · $28.00\nApr 8–14, 2026' };
            document.getElementById('reviewDelivery').textContent = opts[cost] || 'Standard · Free\nApr 3–5, 2026';
        }

        /* ══════════════════════════════════════════
           PAYMENT TAB
        ══════════════════════════════════════════ */
        function selectPayTab(btn, type) {
            document.querySelectorAll('.pay-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const isCard = type === 'card';
            document.getElementById('payCard').style.display = isCard ? 'block' : 'none';
            document.getElementById('payOther').style.display = isCard ? 'none' : 'block';
            const payMethods = { card: 'Credit / Debit Card\n•••• •••• •••• 3456 · Visa', paypal: 'PayPal', apple: 'Apple Pay', google: 'Google Pay' };
            document.getElementById('reviewPayment').textContent = payMethods[type] || 'Card';
        }

        /* ══════════════════════════════════════════
           CARD FORMATTING
        ══════════════════════════════════════════ */
        function formatCard(el) {
            let v = el.value.replace(/\D/g, '').substring(0, 16);
            el.value = v.replace(/(.{4})/g, '$1  ').trim();
            // detect brand
            const icon = document.getElementById('cardBrandIcon');
            if (v.startsWith('4')) icon.className = 'bi bi-credit-card card-brand-icon';
            else if (/^5[1-5]/.test(v)) icon.className = 'bi bi-credit-card-2-front card-brand-icon';
            else if (v.startsWith('3')) icon.className = 'bi bi-credit-card-fill card-brand-icon';
            else icon.className = 'bi bi-credit-card-2-front card-brand-icon';
        }

        function formatExpiry(el) {
            let v = el.value.replace(/\D/g, '');
            if (v.length >= 2) v = v.substring(0, 2) + ' / ' + v.substring(2, 4);
            el.value = v;
        }


        /* ══════════════════════════════════════════
           RECALCULATE
        ══════════════════════════════════════════ */
        function recalc() {
            const discount = subtotal * discountPct;
            const discounted = subtotal - discount;
            const total = discounted + shippingCost;
            const tax = total * 0.08;
            const grand = total + tax;

            //   document.getElementById('osDiscount').textContent = discount > 0 ? '−$' + discount.toFixed(2) : '—';
            //   document.getElementById('osDiscount').style.color = discount > 0 ? 'var(--red)' : '';
            //   document.getElementById('osTax').textContent = '$' + tax.toFixed(2);
            //   document.getElementById('osTotal').textContent = '$' + grand.toFixed(2);

            document.getElementById('reviewTaxLine').textContent = '$' + tax.toFixed(2);
            document.getElementById('reviewTotalLine').textContent = '$' + grand.toFixed(2);
            document.getElementById('reviewShipLine').textContent = shippingCost === 0 ? 'Free' : '$' + shippingCost.toFixed(2);
            document.getElementById('reviewShipLine').style.color = shippingCost === 0 ? 'var(--green)' : '';

            // update place order button text
            document.querySelectorAll('.btn-place-order').forEach(b => {
                b.querySelector('i') ? null : null;
            });
            const placeBtn = document.querySelector('#step5 .btn-next');
            if (placeBtn) placeBtn.textContent = '';
            if (placeBtn) placeBtn.innerHTML = '<i class="bi bi-lock-fill"></i> Place Order — $' + grand.toFixed(2);
        }


        /* ══════════════════════════════════════════
           ADDRESS HELPERS
        ══════════════════════════════════════════ */
        function selectAddress(el) {
            document.querySelectorAll('.saved-address').forEach(a => a.classList.remove('selected'));
            el.classList.add('selected');
            el.querySelector('input[type="radio"]').checked = true;
        }

        let newAddrOpen = false;
        function toggleNewAddress() {
            newAddrOpen = !newAddrOpen;
            document.getElementById('newAddressForm').style.display = newAddrOpen ? 'block' : 'none';
        }

        /* ══════════════════════════════════════════
           SUMMARY SIDEBAR UPDATES
        ══════════════════════════════════════════ */
        function updateSummaryForStep(n) {
            // Nothing dynamic needed — sidebar is always visible
        }

        /* ══════════════════════════════════════════
           PLACE ORDER
        ══════════════════════════════════════════ */
        function placeOrder() {
            const btn = document.querySelector('#step5 .btn-next') || document.querySelector('.btn-place-order');
            if (btn) { btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing…'; btn.disabled = true; }

            setTimeout(() => {
                if (btn) { btn.innerHTML = '<i class="bi bi-lock-fill"></i> Place Order'; btn.disabled = false; }
                document.getElementById('orderNumSuffix').textContent = Math.floor(800 + Math.random() * 200);
                document.getElementById('successOverlay').classList.add('show');
            }, 2000);
        }



        /* ══════════════════════════════════════════
           NAVBAR SCROLL
        ══════════════════════════════════════════ */
        window.addEventListener('scroll', () => {
            document.getElementById('mainNav').style.boxShadow = window.scrollY > 30
                ? '0 4px 30px rgba(26,20,16,.12)'
                : '0 2px 20px rgba(26,20,16,.06)';
        });

        /* ══════════════════════════════════════════
           INIT
        ══════════════════════════════════════════ */
        updateProgressBar();
        recalc();
    </script>
@endpush
