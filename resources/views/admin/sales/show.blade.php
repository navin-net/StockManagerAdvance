@extends('admin.layouts.master')

@section('title', __('messages.view_sale'))

@section('content')
    <div class="container-fluid sv">
        <div class="row g-4">
            <div class="col-xl-12">
                <article class="inv" id="invoice">

                    <!-- Top: brand + invoice numbers -->
                    <header class="inv-top">
                        <div>
                            <div class="inv-brand">
                                <span class="inv-brand__mark">&#128717;</span>
                                <span class="inv-brand__name">COMPANY NAME</span>
                            </div>
                            <div class="dp-muted mt-2">Company Address, Phnom Penh, Cambodia</div>
                        </div>
                        <div class="inv-top__right">
                            <div class="inv-no">Ref <span>#INV-0001</span></div>
                            <div class="inv-dates">
                                <div><span class="dp-muted">Date :</span> Oct 15, 2026</div>
                                <div><span class="dp-muted">Due Date :</span> Oct 30, 2026</div>
                            </div>
                        </div>
                    </header>

                    <!-- From / To / Payment status -->
                    <section class="inv-parties">
                        <div>
                            <div class="inv-tag">From</div>
                            <div class="inv-name">BILLER NAME</div>
                            <div class="dp-muted">Biller Address</div>
                            <div><span class="dp-muted">Email :</span> biller@example.com</div>
                            <div><span class="dp-muted">Phone :</span> 012 345 678</div>
                        </div>

                        <div>
                            <div class="inv-tag">To</div>
                            <div class="inv-name">CUSTOMER NAME</div>
                            <div class="dp-muted">Customer Address</div>
                            <div><span class="dp-muted">Email :</span> customer@example.com</div>
                            <div><span class="dp-muted">Phone :</span> 098 765 432</div>
                        </div>

                        <div>
                            <div class="inv-tag">Payment Status</div>
                            <div class="inv-pills">
                                <span class="dp-pill dp-pill--success">Paid</span>
                                <span class="inv-ghost inv-ghost--success">Completed</span>
                            </div>
                            <div class="inv-qr">
                                <svg viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="10" height="10" fill="#fff"/>
                                    <path d="M0 0h4v4H0zM6 0h4v4H6zM0 6h4v4H0zM6 6h2v2H6zM8 8h2v2H8z" fill="#000"/>
                                </svg>
                            </div>
                        </div>
                    </section>

                    <section class="inv-for">
                        <span class="dp-muted">Invoice For :</span> Sale note
                    </section>

                    <!-- Items -->
                    <section class="inv-items">
                        <div class="table-responsive">
                            <table class="dp-table inv-table">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Sale Price</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="fw-bold">Product Name 1</td>
                                    <td class="text-end dp-num">2</td>
                                    <td class="text-end dp-num">$10.00</td>
                                    <td class="text-end dp-num">$0.00</td>
                                    <td class="text-end dp-num fw-bold">$20.00</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Product Name 2</td>
                                    <td class="text-end dp-num">1</td>
                                    <td class="text-end dp-num">$30.00</td>
                                    <td class="text-end dp-num">$5.00</td>
                                    <td class="text-end dp-num fw-bold">$25.00</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="inv-totals">
                            <div><span>Sub Total</span><span class="dp-num">$50.00</span></div>
                            <div><span>Item Discount</span><span class="dp-num">&minus;$5.00</span></div>
                            <div><span>Discount</span><span class="dp-num">&minus;$2.00</span></div>
                            <div><span>VAT</span><span class="dp-num">$4.30</span></div>
                            <div class="inv-grand">
                                <span>Total Amount</span><strong class="dp-num">$47.30</strong>
                            </div>
                            <div class="inv-words">Amount in Words : Forty-seven dollars and thirty cents</div>
                            <div class="inv-paidrow"><span>Paid</span><span class="dp-num">$47.30</span></div>
                            <div class="inv-paidrow">
                                <span>Balance</span><span class="dp-num fw-bold">$0.00</span>
                            </div>
                        </div>
                    </section>

                    <!-- Terms / notes / signature -->
                    <section class="inv-foot">
                        <div>
                            <div class="inv-tag inv-tag--dark">Terms and Conditions</div>
                            <p class="dp-muted">Payment is due within 15 days of the invoice date.</p>
                            <div class="inv-tag inv-tag--dark">Notes</div>
                            <p class="dp-muted mb-0">Please quote invoice number INV-0001 when remitting funds.</p>
                        </div>
                        <div class="inv-sign">
                            <div class="inv-sign__line"></div>
                            <div>BILLER NAME</div>
                            <div class="dp-muted">Biller</div>
                        </div>
                    </section>

                    <footer class="inv-end">
                        <div class="inv-brand inv-brand--center">
                            <span class="inv-brand__mark">&#128717;</span>
                            <span class="inv-brand__name">COMPANY NAME</span>
                        </div>
                        <div class="dp-muted">Thank you for your business.</div>
                    </footer>
                </article>
            </div>
        </div>

        <style>
            .sv {
                --paper: var(--card-bg);
                --ink: var(--text-color);
                --soft: var(--text-muted);
                --rule: var(--border-color);
                --head: var(--ink3);
                --ok: #198754;
                --ok-text: #198754;
                --due-text: #dc3545;
            }
            [data-bs-theme="dark"] .sv {
                --ok-text: #75b798;
                --due-text: #ea868f;
            }

            /* helpers (replace Bootstrap classes) */
            .dp-muted { color: var(--soft); }
            .dp-num { font-variant-numeric: tabular-nums; }
            .text-end { text-align: right; }
            .text-center { text-align: center; }
            .fw-bold { font-weight: 700; }
            .mt-2 { margin-top: .5rem; }
            .mb-0 { margin-bottom: 0; }
            .table-responsive { overflow-x: auto; }

            .dp-table { width: 100%; border-collapse: collapse; }
            .dp-table th { background: var(--head); text-align: left; padding: .7rem .8rem; font-weight: 600; }
            .dp-table th.text-end { text-align: right; }
            .dp-table td { padding: .8rem; border-bottom: 1px solid var(--rule); }

            .dp-pill { display: inline-block; padding: .15rem .6rem; border-radius: 5px;
                font-size: .75rem; font-weight: 600; line-height: 1.6; }
            .dp-pill--success { background: rgba(25,135,84,.15); color: var(--ok-text); }

            /* ===== Invoice paper ===== */
            .inv { background: var(--paper); color: var(--ink); border: 1px solid var(--rule); border-radius: var(--radius);
                overflow: hidden; font-size: .95rem; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .inv > section, .inv > header, .inv > footer { padding: 1.5rem 1.75rem; border-bottom: 1px solid var(--rule); }
            .inv > footer { border-bottom: 0; }

            .inv-top { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 1rem; }
            .inv-brand { display: inline-flex; align-items: center; gap: .6rem; }
            .inv-brand__mark { display: grid; place-items: center; width: 40px; height: 40px; border-radius: 10px;
                background: var(--brand); color: #fff; font-size: 1.25rem; }
            .inv-brand__name { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; }
            .inv-brand--center { margin-bottom: .6rem; }
            .inv-top__right { text-align: right; }
            .inv-no { font-size: 1.15rem; font-weight: 700; color: var(--soft); }
            .inv-no span { color: var(--brand-text); }
            .inv-dates { margin-top: .35rem; line-height: 1.8; }

            .inv-parties { display: grid; grid-template-columns: 1fr 1fr 190px; gap: 1.5rem; line-height: 1.8; }
            .inv-tag { font-weight: 500; color: var(--soft); margin-bottom: .2rem; }
            .inv-tag--dark { color: var(--ink); font-weight: 700; }
            .inv-name { font-size: 1.25rem; font-weight: 700; line-height: 1.4; }
            .inv-pills { display: flex; flex-wrap: wrap; gap: .35rem; }
            .inv-ghost { display: inline-flex; align-items: center; padding: .05rem .5rem; border-radius: 5px;
                border: 1px solid currentColor; font-size: .75rem; font-weight: 600; line-height: 1.6; }
            .inv-ghost--success { color: var(--ok-text); }

            .inv-qr { margin-top: .9rem; width: 100px; height: 100px; padding: 4px; background: #fff;
                border: 1px solid var(--rule); border-radius: 6px; }
            .inv-qr svg { width: 100%; height: 100%; display: block; }

            .inv-for { padding-top: 1.1rem !important; padding-bottom: 0 !important; border-bottom: 0 !important; }
            .inv-items { padding-top: 1.25rem !important; }

            .inv-totals { width: 100%; max-width: 370px; margin: 1.25rem 0 0 auto; }
            .inv-totals > div { display: flex; justify-content: space-between; gap: 1rem; padding: .65rem 0;
                color: var(--soft); border-bottom: 1px solid var(--rule); }
            .inv-totals > .inv-grand { align-items: baseline; color: var(--ink); border-bottom: 0; padding-bottom: .15rem; }
            .inv-grand span { font-size: 1.15rem; font-weight: 700; }
            .inv-grand strong { font-size: 1.35rem; }
            .inv-totals > .inv-words { display: block; border-bottom: 0; padding-top: .2rem; font-size: .85rem; }
            .inv-totals > .inv-paidrow { color: var(--ink); }
            .inv-totals > .inv-paidrow.is-due { color: var(--due-text); }
            .inv-totals > .inv-paidrow:last-child { border-bottom: 0; }

            .inv-foot { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 1.5rem; }
            .inv-foot p { margin: 0 0 1.1rem; }
            .inv-sign { min-width: 200px; text-align: center; }
            .inv-sign__line { height: 46px; border-bottom: 1px solid var(--ink); margin-bottom: .5rem; }
            .inv-end { text-align: center; }

            @media (max-width: 767.98px) {
                .inv-parties { grid-template-columns: 1fr; }
                .inv-top__right { text-align: left; }
            }
            @media print {
                @page { margin: 10mm; }
                body * { visibility: hidden; }
                #invoice, #invoice * { visibility: visible; }
                #invoice { position: absolute; left: 0; top: 0; width: 100%; border: 0; border-radius: 0; background: #fff; color: #000; --paper: #fff; --ink: #000; --soft: #555; --rule: #ddd; --head: #f3f3f3; --brand-text: #4343B8; --ok-text: #198754; --due-text: #dc3545; }
                .inv { border: 0; border-radius: 0; }
            }
        </style>
    </div>
@endsection
