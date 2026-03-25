@extends('admin.layouts.master')
@section('content')
    <div class="app">
        <!-- HEADER -->
        {{-- <header class="d-flex align-items-center px-3 gap-3 flex-shrink-0"
        style="height:52px;background:var(--ink2);border-bottom:1px solid var(--wire);position:relative;z-index:10;">
  <div class="d-flex align-items-center gap-2 brand-logo-text"><div class="bdot"></div>KASA</div>
  <div class="hsep"></div>
  <div class="hchip d-flex align-items-center gap-2 px-3 py-1">
    <i class="bi bi-shop live"></i><span class="live">Main Branch</span>
  </div>
  <div class="hclock" id="hTime"></div>
  <div class="ms-auto d-flex align-items-center gap-2">
    <div class="hchip d-flex align-items-center gap-2 px-3 py-1">
      <div class="avatar d-flex align-items-center justify-content-center">JD</div>
      <span style="font-size:11px;font-weight:600;color:var(--chalk);">John Doe</span>
    </div>
    <button class="ibtn d-flex align-items-center justify-content-center" onclick="toast('No held orders','err')"><i class="bi bi-archive"></i></button>
    <button class="ibtn d-flex align-items-center justify-content-center" onclick="toast('Settings coming soon','ok')"><i class="bi bi-sliders2"></i></button>
  </div>
</header> --}}

        <!-- BODY -->
        <div class="body">

            <!-- ══ BRAND SIDEBAR ══ -->
            <div class="brand-sidebar">
                <div class="bs-head px-3 py-3" style="border-bottom:1px solid var(--wire);">BRANDS</div>
                <div class="position-relative px-2 py-2" style="border-bottom:1px solid var(--wire);">
                    <i class="bi bi-search position-absolute"
                        style="left:18px;top:50%;transform:translateY(-50%);color:var(--dim);font-size:12px;pointer-events:none;"></i>
                    <input type="text" class="bs-search-input" id="brandSearch" placeholder="Search brand…"
                        oninput="filterBrandList()">
                </div>
                <div class="bs-list p-2" id="brandList"></div>
            </div>

            <!-- ══ CENTER ══ -->
            <div class="center d-flex flex-column overflow-hidden">

                <!-- Filter bar -->
                <div class="filter-bar">
                    <div class="d-flex align-items-center px-3 pt-2" style="border-bottom:1px solid var(--wire);">
                        <div class="cat-row-label pe-3 me-3" style="border-right:1px solid var(--wire2);">Category</div>
                        <div class="cat-scroller pb-2 flex-grow-1" id="catScroller"></div>
                    </div>
                    <div class="sub-row" id="subRow">
                        <div class="sub-row-label pe-3 me-3" style="border-right:1px solid var(--wire2);">
                            <i class="bi bi-diagram-2 me-1" style="font-size:10px;"></i>Sub
                        </div>
                        <div class="sub-scroller gap-1 flex-grow-1" id="subScroller"></div>
                    </div>
                </div>

                <!-- Search + controls -->
                <div class="d-flex align-items-center gap-2 px-3 py-2" style="border-bottom:1px solid var(--wire);">
                    <div class="position-relative flex-grow-1">
                        <i class="bi bi-search srch-icon"></i>
                        <input type="text" class="srch-input" id="srch" placeholder="Search products, SKU, barcode…"
                            oninput="filterProducts()">
                        <button class="srch-clear" onclick="clearSrch()"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="result-count"><span id="resCount">0</span> items</div>
                    <button class="sort-btn" onclick="cycleSort()">
                        <i class="bi bi-sort-down" id="sortIcon"></i><span id="sortLabel">Name</span>
                    </button>
                    <div class="view-toggle">
                        <button class="vt-btn on" id="vgrid" onclick="setView('grid')" title="Grid"><i
                                class="bi bi-grid-3x3-gap"></i></button>
                        <button class="vt-btn" id="vlist" onclick="setView('list')" title="List"><i
                                class="bi bi-list-ul"></i></button>
                    </div>
                </div>

                <!-- Products -->
                <div class="pgrid-wrap">
                    <div class="pgrid" id="pgrid"></div>
                    <p style="height: 30px"></p>
                </div>
            </div>

            <!-- ══ CART ══ -->
            <div class="cart-panel d-flex flex-column overflow-hidden"
                style="background:var(--ink2);border-left:1px solid var(--wire);">

                <div class="d-flex align-items-start justify-content-between px-3 pt-3 pb-2"
                    style="border-bottom:1px solid var(--wire);">
                    <div>
                        <div class="cart-heading">ORDER <span class="badge-cnt" id="cCount">0</span></div>
                        <div class="cart-sub" id="ordLabel">— NEW ORDER —</div>
                    </div>
                    <button class="btn-void" onclick="clearCart()"><i class="bi bi-trash3"></i> Void</button>
                </div>

                <div class="px-3 py-2" style="border-bottom:1px solid var(--wire);">
                    <div class="fl"><i class="bi bi-person-circle"></i> Customer</div>
                    <select class="sel">
                        <option>Walk-in Customer</option>
                        <option>Alice Johnson</option>
                        <option>Bob Smith</option>
                        <option>Carol Williams</option>
                    </select>
                </div>

                <div class="cart-list" id="cartList">
                    <div class="empty-st" id="emptySt">
                        <i class="bi bi-bag-x"></i>
                        <p>No items yet</p>
                        <small>Tap a product to add</small>
                    </div>
                </div>

                <div class="d-flex gap-2 px-3 py-2" style="border-top:1px solid var(--wire);">
                    <div class="flex-fill">
                        <div class="fl">Discount %</div>
                        <input type="number" class="extras-input" id="disc" min="0" max="100"
                            value="0" oninput="renderSum()">
                    </div>
                    <div style="flex:2;">
                        <div class="fl">Note</div>
                        <input type="text" class="extras-input" id="note" placeholder="Order note…">
                    </div>
                </div>

                <div class="px-3 py-2" style="background:var(--ink3);border-top:1px solid var(--wire);">
                    <div class="sr"><span>Subtotal</span><span class="v" id="sSub">$0.00</span></div>
                    <div class="sr disc"><span>Discount</span><span class="v" id="sDisc">−$0.00</span></div>
                    <div class="sr"><span>Tax (10%)</span><span class="v" id="sTax">$0.00</span></div>
                    <hr class="ssep">
                    <div class="d-flex justify-content-between align-items-baseline pt-1">
                        <span class="stl">Total Due</span><span class="stv" id="sTotal">$0.00</span>
                    </div>
                </div>

                <div class="d-grid gap-2 px-3 py-2"
                    style="grid-template-columns:repeat(3,1fr);border-top:1px solid var(--wire);">
                    <button class="pb on-cash" onclick="setPay('cash',this)"><i
                            class="bi bi-cash-stack"></i>Cash</button>
                    <button class="pb" onclick="setPay('card',this)"><i
                            class="bi bi-credit-card-2-front"></i>Card</button>
                    <button class="pb" onclick="setPay('qr',this)"><i class="bi bi-qr-code-scan"></i>QR Pay</button>
                </div>

                <div class="px-3 pb-3 pt-2">
                    <button class="btn-charge" id="btnCharge" disabled onclick="openNP()">
                        <span><i class="bi bi-bag-check-fill"></i>&nbsp; CHARGE &nbsp;</span>
                        <span id="btnAmt">$0.00</span>
                    </button>

                    {{-- <button class="btn-hold" onclick="toast('Order held','ok')"><i class="bi bi-archive"></i> Hold</button> --}}

                    <p style="height: 30px"></p>

                </div>
            </div>

        </div><!-- /.body -->
<div class="cart-backdrop" id="cartBackdrop" onclick="toggleCart()"></div>
    </div>
    <!-- NUMPAD -->
    <div class="overlay" id="npOv">
        <div class="np-card">
            <div class="np-head">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-calculator"></i>Cash Received</span>
            </div>
            <div class="np-screen" id="npScr">$0.00</div>
            <div class="np-chg"><span>Change due</span><span class="chg" id="npChg">$0.00</span></div>
            <div class="np-grid" id="npGrid"></div>
            <div class="np-shorts">
                <button class="ns" onclick="npExact()">Exact</button>
                <button class="ns" onclick="npRnd(5)">$5</button>
                <button class="ns" onclick="npRnd(10)">$10</button>
                <button class="ns" onclick="npRnd(20)">$20</button>
                <button class="ns" onclick="npRnd(50)">$50</button>
            </div>
            <button class="np-cancel" onclick="closeNP()">Cancel</button>
        </div>
    </div>

    <!-- RECEIPT -->
    <div class="overlay" id="rcOv">
        <div class="rc-card">
            <div class="rcb text-center mb-2">
                <h2>KASA</h2>
                <p>Official Receipt</p>
                <div class="dt" id="rcDate"></div>
            </div>
            <hr class="rcl">
            <div id="rcItems"></div>
            <hr class="rcl">
            <div class="rcs"><span>Subtotal</span><span class="v" id="rcSub"></span></div>
            <div class="rcs disc"><span>Discount</span><span class="v" id="rcDisc"></span></div>
            <div class="rcs"><span>Tax (10%)</span><span class="v" id="rcTax"></span></div>
            <hr class="rcl">
            <div class="rct"><span class="rct-l">TOTAL</span><span class="rct-v" id="rcTotal"></span></div>
            <hr class="rcl">
            <div class="rcp"><span>Payment</span><span class="v" id="rcMeth"></span></div>
            <div class="rcp" id="rcPaidRow" style="display:none"><span>Cash Paid</span><span class="v"
                    id="rcPaid"></span></div>
            <div class="rcp chg" id="rcChgRow" style="display:none"><span>Change</span><span class="v"
                    id="rcChg"></span></div>
            <hr class="rcl">
            <div class="rcf">Thank you!<br><strong id="rcOrd"></strong> · KASA POS</div>
            <button class="btn-pr" onclick="toast('Printing…','ok');setTimeout(()=>window.print(),300)"><i
                    class="bi bi-printer-fill"></i> PRINT</button>
            <button class="btn-cr" onclick="closeRC()">Close</button>
        </div>
    </div>

    <div id="toasts"></div>
@endsection
@push('scripts')
    <script>
        const BRANDS = [{
                id: 'all',
                name: 'All Brands',
                e: '🏪',
                color: 'lime',
                cats: []
            },
            {
                id: 'kasa',
                name: 'KASA Select',
                e: '⭐',
                color: 'lime',
                cats: ['Beverages', 'Food', 'Snacks']
            },
            {
                id: 'brew',
                name: 'BrewCraft',
                e: '☕',
                color: 'gold',
                cats: ['Coffee', 'Tea', 'Cold Brew']
            },
            {
                id: 'vita',
                name: 'VitaFresh',
                e: '🥗',
                color: 'green',
                cats: ['Salads', 'Wraps', 'Bowls']
            },
            {
                id: 'bake',
                name: 'BakeLab',
                e: '🥐',
                color: 'sky',
                cats: ['Pastries', 'Bread', 'Cakes']
            },
            {
                id: 'zen',
                name: 'ZenDrinks',
                e: '🧃',
                color: 'violet',
                cats: ['Juices', 'Smoothies', 'Water']
            },
            {
                id: 'prot',
                name: 'ProFuel',
                e: '💪',
                color: 'pink',
                cats: ['Protein', 'Supplements', 'Bars']
            },
            {
                id: 'kasa',
                name: 'KASA Select',
                e: '⭐',
                color: 'lime',
                cats: ['Beverages', 'Food', 'Snacks']
            },
            {
                id: 'brew',
                name: 'BrewCraft',
                e: '☕',
                color: 'gold',
                cats: ['Coffee', 'Tea', 'Cold Brew']
            },
            {
                id: 'vita',
                name: 'VitaFresh',
                e: '🥗',
                color: 'green',
                cats: ['Salads', 'Wraps', 'Bowls']
            },
            {
                id: 'bake',
                name: 'BakeLab',
                e: '🥐',
                color: 'sky',
                cats: ['Pastries', 'Bread', 'Cakes']
            },
            {
                id: 'zen',
                name: 'ZenDrinks',
                e: '🧃',
                color: 'violet',
                cats: ['Juices', 'Smoothies', 'Water']
            },
            {
                id: 'prot',
                name: 'ProFuel',
                e: '💪',
                color: 'pink',
                cats: ['Protein', 'Supplements', 'Bars']
            },
            {
                id: 'kasa',
                name: 'KASA Select',
                e: '⭐',
                color: 'lime',
                cats: ['Beverages', 'Food', 'Snacks']
            },
            {
                id: 'brew',
                name: 'BrewCraft',
                e: '☕',
                color: 'gold',
                cats: ['Coffee', 'Tea', 'Cold Brew']
            },
            {
                id: 'vita',
                name: 'VitaFresh',
                e: '🥗',
                color: 'green',
                cats: ['Salads', 'Wraps', 'Bowls']
            },
            {
                id: 'bake',
                name: 'BakeLab',
                e: '🥐',
                color: 'sky',
                cats: ['Pastries', 'Bread', 'Cakes']
            },
            {
                id: 'zen',
                name: 'ZenDrinks',
                e: '🧃',
                color: 'violet',
                cats: ['Juices', 'Smoothies', 'Water']
            },
            {
                id: 'prot',
                name: 'ProFuel',
                e: '💪',
                color: 'pink',
                cats: ['Protein', 'Supplements', 'Bars']
            },

        ];
        const SUBCATS = {
            'Coffee': ['All', 'Iced', 'Hot', 'Blended', 'Decaf'],
            'Tea': ['All', 'Iced', 'Hot', 'Herbal', 'Matcha'],
            'Cold Brew': ['All', 'Original', 'Nitro', 'Flavored'],
            'Beverages': ['All', 'Hot', 'Cold', 'Sparkling'],
            'Food': ['All', 'Hot', 'Cold', 'Vegan'],
            'Snacks': ['All', 'Sweet', 'Savory', 'Healthy'],
            'Salads': ['All', 'Green', 'Caesar', 'Asian'],
            'Wraps': ['All', 'Chicken', 'Veggie', 'Beef'],
            'Bowls': ['All', 'Grain', 'Noodle', 'Soup'],
            'Pastries': ['All', 'Croissant', 'Muffin', 'Danish'],
            'Bread': ['All', 'Sourdough', 'Whole Wheat', 'Rye'],
            'Cakes': ['All', 'Layer', 'Cheesecake', 'Tart'],
            'Juices': ['All', 'Cold Press', 'Orange', 'Green'],
            'Smoothies': ['All', 'Berry', 'Tropical', 'Green'],
            'Water': ['All', 'Still', 'Sparkling', 'Infused'],
            'Protein': ['All', 'Whey', 'Plant', 'Casein'],
            'Supplements': ['All', 'Vitamins', 'Minerals', 'Blends'],
            'Bars': ['All', 'Protein', 'Energy', 'Granola'],
        };
        const PRODUCTS = [{
                id: 1,
                brand: 'brew',
                name: 'Iced Americano',
                price: 3.50,
                cat: 'Coffee',
                sub: 'Iced',
                e: '☕',
                stock: 99,
                sku: 'BC-001'
            },
            {
                id: 2,
                brand: 'brew',
                name: 'Matcha Latte',
                price: 4.75,
                cat: 'Tea',
                sub: 'Matcha',
                e: '🍵',
                stock: 24,
                sku: 'BC-002'
            },
            {
                id: 3,
                brand: 'brew',
                name: 'Caramel Frappé',
                price: 5.25,
                cat: 'Coffee',
                sub: 'Blended',
                e: '🥤',
                stock: 18,
                sku: 'BC-003'
            },
            {
                id: 4,
                brand: 'brew',
                name: 'Vanilla Latte',
                price: 4.50,
                cat: 'Coffee',
                sub: 'Hot',
                e: '☕',
                stock: 33,
                sku: 'BC-004'
            },
            {
                id: 5,
                brand: 'brew',
                name: 'Earl Grey Hot',
                price: 3.00,
                cat: 'Tea',
                sub: 'Hot',
                e: '🫖',
                stock: 50,
                sku: 'BC-005'
            },
            {
                id: 6,
                brand: 'brew',
                name: 'Mint Iced Tea',
                price: 3.25,
                cat: 'Tea',
                sub: 'Iced',
                e: '🧊',
                stock: 30,
                sku: 'BC-006'
            },
            {
                id: 7,
                brand: 'brew',
                name: 'Nitro Cold Brew',
                price: 5.50,
                cat: 'Cold Brew',
                sub: 'Nitro',
                e: '🍺',
                stock: 15,
                sku: 'BC-007'
            },
            {
                id: 8,
                brand: 'brew',
                name: 'Original Cold Brew',
                price: 4.25,
                cat: 'Cold Brew',
                sub: 'Original',
                e: '🥤',
                stock: 20,
                sku: 'BC-008'
            },
            {
                id: 9,
                brand: 'kasa',
                name: 'House Blend Coffee',
                price: 3.00,
                cat: 'Beverages',
                sub: 'Hot',
                e: '☕',
                stock: 99,
                sku: 'KS-001'
            },
            {
                id: 10,
                brand: 'kasa',
                name: 'Sparkling Water',
                price: 2.00,
                cat: 'Beverages',
                sub: 'Sparkling',
                e: '💧',
                stock: 0,
                sku: 'KS-002'
            },
            {
                id: 11,
                brand: 'kasa',
                name: 'KASA Granola Bar',
                price: 2.50,
                cat: 'Snacks',
                sub: 'Healthy',
                e: '🌾',
                stock: 40,
                sku: 'KS-003'
            },
            {
                id: 12,
                brand: 'kasa',
                name: 'Mixed Nuts',
                price: 3.25,
                cat: 'Snacks',
                sub: 'Savory',
                e: '🥜',
                stock: 35,
                sku: 'KS-004'
            },
            {
                id: 13,
                brand: 'vita',
                name: 'Caesar Salad',
                price: 7.50,
                cat: 'Salads',
                sub: 'Caesar',
                e: '🥗',
                stock: 6,
                sku: 'VF-001'
            },
            {
                id: 14,
                brand: 'vita',
                name: 'Asian Slaw',
                price: 8.00,
                cat: 'Salads',
                sub: 'Asian',
                e: '🥗',
                stock: 10,
                sku: 'VF-002'
            },
            {
                id: 15,
                brand: 'vita',
                name: 'Chicken Wrap',
                price: 6.50,
                cat: 'Wraps',
                sub: 'Chicken',
                e: '🌯',
                stock: 8,
                sku: 'VF-003'
            },
            {
                id: 16,
                brand: 'vita',
                name: 'Veggie Wrap',
                price: 5.75,
                cat: 'Wraps',
                sub: 'Veggie',
                e: '🌯',
                stock: 12,
                sku: 'VF-004'
            },
            {
                id: 17,
                brand: 'vita',
                name: 'Grain Bowl',
                price: 9.00,
                cat: 'Bowls',
                sub: 'Grain',
                e: '🍲',
                stock: 7,
                sku: 'VF-005'
            },
            {
                id: 18,
                brand: 'bake',
                name: 'Butter Croissant',
                price: 2.50,
                cat: 'Pastries',
                sub: 'Croissant',
                e: '🥐',
                stock: 22,
                sku: 'BL-001'
            },
            {
                id: 19,
                brand: 'bake',
                name: 'Blueberry Muffin',
                price: 2.75,
                cat: 'Pastries',
                sub: 'Muffin',
                e: '🫐',
                stock: 18,
                sku: 'BL-002'
            },
            {
                id: 20,
                brand: 'bake',
                name: 'Choc Brownie',
                price: 3.25,
                cat: 'Pastries',
                sub: 'Danish',
                e: '🍫',
                stock: 15,
                sku: 'BL-003'
            },
            {
                id: 21,
                brand: 'bake',
                name: 'Sourdough Loaf',
                price: 6.00,
                cat: 'Bread',
                sub: 'Sourdough',
                e: '🍞',
                stock: 5,
                sku: 'BL-004'
            },
            {
                id: 22,
                brand: 'bake',
                name: 'NY Cheesecake',
                price: 5.50,
                cat: 'Cakes',
                sub: 'Cheesecake',
                e: '🍰',
                stock: 8,
                sku: 'BL-005'
            },
            {
                id: 23,
                brand: 'zen',
                name: 'Cold Press OJ',
                price: 4.50,
                cat: 'Juices',
                sub: 'Cold Press',
                e: '🍊',
                stock: 14,
                sku: 'ZD-001'
            },
            {
                id: 24,
                brand: 'zen',
                name: 'Green Detox',
                price: 5.25,
                cat: 'Juices',
                sub: 'Green',
                e: '🥬',
                stock: 10,
                sku: 'ZD-002'
            },
            {
                id: 25,
                brand: 'zen',
                name: 'Berry Smoothie',
                price: 5.75,
                cat: 'Smoothies',
                sub: 'Berry',
                e: '🍓',
                stock: 12,
                sku: 'ZD-003'
            },
            {
                id: 26,
                brand: 'zen',
                name: 'Tropical Blend',
                price: 5.25,
                cat: 'Smoothies',
                sub: 'Tropical',
                e: '🥭',
                stock: 9,
                sku: 'ZD-004'
            },
            {
                id: 27,
                brand: 'zen',
                name: 'Infused Water',
                price: 2.75,
                cat: 'Water',
                sub: 'Infused',
                e: '💧',
                stock: 30,
                sku: 'ZD-005'
            },
            {
                id: 28,
                brand: 'prot',
                name: 'Whey Shake',
                price: 6.50,
                cat: 'Protein',
                sub: 'Whey',
                e: '🥛',
                stock: 20,
                sku: 'PF-001'
            },
            {
                id: 29,
                brand: 'prot',
                name: 'Plant Protein',
                price: 6.75,
                cat: 'Protein',
                sub: 'Plant',
                e: '🌱',
                stock: 15,
                sku: 'PF-002'
            },
            {
                id: 30,
                brand: 'prot',
                name: 'Energy Bar',
                price: 3.50,
                cat: 'Bars',
                sub: 'Energy',
                e: '⚡',
                stock: 25,
                sku: 'PF-003'
            },
            {
                id: 31,
                brand: 'prot',
                name: 'Protein Bar',
                price: 3.75,
                cat: 'Bars',
                sub: 'Protein',
                e: '💪',
                stock: 22,
                sku: 'PF-004'
            },
            {
                id: 32,
                brand: 'prot',
                name: 'Vitamin Pack',
                price: 4.25,
                cat: 'Supplements',
                sub: 'Vitamins',
                e: '💊',
                stock: 0,
                sku: 'PF-005'
            },
        ];
        const BRAND_COLORS = {
            kasa: 'lime',
            brew: 'gold',
            vita: 'green',
            bake: 'sky',
            zen: 'violet',
            prot: 'pink'
        };
        const BRAND_CSS_COLORS = {
            lime: '#c8ff00',
            gold: '#ffb547',
            green: '#4ade80',
            sky: '#00c2ff',
            violet: '#a78bfa',
            pink: '#f472b6'
        };
        let activeBrand = 'all',
            activeCat = 'All',
            activeSub = 'All',
            cart = [],
            payMethod = 'cash',
            npRaw = '';
        let ordSeq = 1001 + Math.floor(Math.random() * 99),
            sortMode = 0,
            viewMode = 'grid';
        const SORTS = [{
                label: 'Name',
                icon: 'bi-sort-alpha-down',
                fn: (a, b) => a.name.localeCompare(b.name)
            },
            {
                label: 'Price↑',
                icon: 'bi-sort-numeric-down',
                fn: (a, b) => a.price - b.price
            },
            {
                label: 'Price↓',
                icon: 'bi-sort-numeric-up-alt',
                fn: (a, b) => b.price - a.price
            },
        ];
        (function() {
            buildBrandList();
            buildCategoryTabs();
            filterProducts();
            clock();
            setInterval(clock, 1000);
            document.getElementById('ordLabel').textContent = `#ORD-${ordSeq}`;
            buildNpGrid();
        })();

        function clock() {
            document.getElementById('hTime').textContent = new Date().toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function buildBrandList(filter = '') {
            const el = document.getElementById('brandList'),
                f = filter.toLowerCase();
            el.innerHTML = BRANDS.filter(b => !f || b.name.toLowerCase().includes(f)).map(b => {
                const count = b.id === 'all' ? PRODUCTS.length : PRODUCTS.filter(p => p.brand === b.id).length;
                return `<div class="brand-item${activeBrand===b.id?' on':''} ${b.id==='all'?'all-brands':''}" onclick="setBrand('${b.id}')">
      <div class="brand-logo">${b.e}</div>
      <div class="brand-text flex-fill" style="min-width:0;">
        <div class="brand-name">${b.name}</div>
        <div class="brand-count">${count} products</div>
      </div>
      <div class="brand-indicator"></div>
    </div>`;
            }).join('');
        }

        function filterBrandList() {
            buildBrandList(document.getElementById('brandSearch').value);
        }

        function setBrand(id) {
            activeBrand = id;
            activeCat = 'All';
            activeSub = 'All';
            buildBrandList(document.getElementById('brandSearch').value);
            buildCategoryTabs();
            buildSubTabs();
            filterProducts();
        }

        function getCatsForBrand() {
            if (activeBrand === 'all') return [...new Set(PRODUCTS.map(p => p.cat))].sort();
            const b = BRANDS.find(b => b.id === activeBrand);
            return b ? b.cats : [];
        }

        function buildCategoryTabs() {
            const cats = getCatsForBrand(),
                el = document.getElementById('catScroller');
            el.innerHTML = ['All', ...cats].map(c => {
                const count = c === 'All' ? (activeBrand === 'all' ? PRODUCTS.length : PRODUCTS.filter(p => p
                    .brand === activeBrand).length) : PRODUCTS.filter(p => (activeBrand === 'all' || p.brand ===
                    activeBrand) && p.cat === c).length;
                return `<button class="ctab${activeCat===c?' on':''}" onclick="setCat('${c}')">${c} <span class="ctab-count">${count}</span></button>`;
            }).join('');
        }

        function setCat(c) {
            activeCat = c;
            activeSub = 'All';
            buildCategoryTabs();
            buildSubTabs();
            filterProducts();
        }

        function buildSubTabs() {
            const subRow = document.getElementById('subRow'),
                el = document.getElementById('subScroller');
            if (activeCat === 'All' || !SUBCATS[activeCat]) {
                subRow.classList.add('hidden');
                return;
            }
            subRow.classList.remove('hidden');
            const color = activeBrand !== 'all' ? BRAND_COLORS[activeBrand] || 'lime' : 'lime';
            el.innerHTML = (SUBCATS[activeCat] || ['All']).map(s =>
                `<button class="stab${activeSub===s?' on':''}" data-color="${color}" onclick="setSub('${s}')">${s}</button>`
                ).join('');
        }

        function setSub(s) {
            activeSub = s;
            buildSubTabs();
            filterProducts();
        }

        function filterProducts() {
            const q = document.getElementById('srch').value.toLowerCase();
            let list = PRODUCTS;
            if (activeBrand !== 'all') list = list.filter(p => p.brand === activeBrand);
            if (activeCat !== 'All') list = list.filter(p => p.cat === activeCat);
            if (activeSub !== 'All') list = list.filter(p => p.sub === activeSub);
            if (q) list = list.filter(p => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q));
            list = [...list].sort(SORTS[sortMode].fn);
            document.getElementById('resCount').textContent = list.length;
            renderGrid(list);
        }

        function clearSrch() {
            document.getElementById('srch').value = '';
            filterProducts();
        }

        function cycleSort() {
            sortMode = (sortMode + 1) % SORTS.length;
            document.getElementById('sortLabel').textContent = SORTS[sortMode].label;
            document.getElementById('sortIcon').className = 'bi ' + SORTS[sortMode].icon;
            filterProducts();
        }

        function setView(v) {
            viewMode = v;
            document.getElementById('vgrid').classList.toggle('on', v === 'grid');
            document.getElementById('vlist').classList.toggle('on', v === 'list');
            document.getElementById('pgrid').classList.toggle('list-view', v === 'list');
        }

        function renderGrid(list) {
            const el = document.getElementById('pgrid');
            if (!list.length) {
                el.innerHTML =
                    `<div class="no-res"><i class="bi bi-search"></i><p>No products found</p><small>Try a different brand, category or search term</small></div>`;
                return;
            }
            const isGrid = viewMode === 'grid';
            el.innerHTML = list.map(p => {
                const brand = BRANDS.find(b => b.id === p.brand),
                    color = BRAND_CSS_COLORS[brand?.color] || 'var(--lime)';
                if (isGrid) return `<div class="pcard${p.stock===0?' out':''}" onclick="addItem(${p.id})">
      <div class="pcard-accent" style="background:${color}"></div>
      ${p.stock===0?'<span class="pcard-status out-s">Out</span>':p.stock<=8?'<span class="pcard-status low-s">Low</span>':''}
      <div class="pcard-img"><span class="pcard-brand-badge" style="border-color:${color}22;color:${color};">${brand?.e||''} ${brand?.name||''}</span>${p.e}</div>
      <div class="pcard-body">
        <div class="pcard-cat"><span class="pcard-cat-badge" style="border-color:${color}33;color:${color};background:${color}11;">${p.cat}</span><span class="pcard-subcat">/ ${p.sub}</span></div>
        <div class="pcard-name">${p.name}</div>
        <div class="pcard-price">$${p.price.toFixed(2)}</div>
        <div class="pcard-sku">${p.sku} · ${p.stock===0?'Out of stock':p.stock+' in stock'}</div>
      </div>
      <div class="pcard-plus">+</div></div>`;
                return `<div class="pcard${p.stock===0?' out':''}" onclick="addItem(${p.id})" style="position:relative;">
      <div class="pcard-accent" style="background:${color};position:absolute;left:0;top:0;bottom:0;width:3px;border-radius:10px 0 0 10px;height:100%;"></div>
      <div class="pcard-img" style="height:52px;width:52px;min-width:52px;border-radius:8px;margin:8px 0 8px 14px;font-size:1.6rem;">${p.e}</div>
      <div class="pcard-body" style="flex:1;display:flex;align-items:center;gap:12px;padding:8px 12px;">
        <div style="flex:1;min-width:0;">
          <div style="display:flex;align-items:center;gap:5px;margin-bottom:3px;">
            <span class="pcard-brand-badge" style="position:static;border-color:${color}22;color:${color};font-size:8px;">${brand?.e} ${brand?.name}</span>
            <span class="pcard-cat-badge" style="border-color:${color}33;color:${color};background:${color}11;">${p.cat} / ${p.sub}</span>
            ${p.stock===0?'<span class="pcard-status out-s" style="position:static;font-size:8px;">Out</span>':p.stock<=8?'<span class="pcard-status low-s" style="position:static;font-size:8px;">Low</span>':''}
          </div>
          <div class="pcard-name" style="font-size:12px;">${p.name}</div>
          <div class="pcard-sku">${p.sku}</div>
        </div>
        <div class="pcard-price" style="font-size:14px;">$${p.price.toFixed(2)}</div>
        <div class="pcard-plus" style="position:static;opacity:1;margin-left:4px;">+</div>
      </div></div>`;
            }).join('');
        }

        function addItem(id) {
            const p = PRODUCTS.find(x => x.id === id);
            if (!p || p.stock === 0) return;
            const ex = cart.find(c => c.id === id);
            if (ex) ex.qty++;
            else cart.push({
                ...p,
                qty: 1
            });
            renderCart();
            toast(`${p.e} ${p.name} added`, 'ok');
        }

        function changeQty(id, d) {
            const item = cart.find(c => c.id === id);
            if (!item) return;
            item.qty += d;
            if (item.qty <= 0) cart = cart.filter(c => c.id !== id);
            renderCart();
        }

        function removeItem(id) {
            cart = cart.filter(c => c.id !== id);
            renderCart();
        }

        function clearCart() {
            if (!cart.length) return;
            cart = [];
            renderCart();
            toast('Cart cleared', 'err');
        }

        function renderCart() {
            const el = document.getElementById('cartList'),
                em = document.getElementById('emptySt');
            document.getElementById('cCount').textContent = cart.reduce((s, c) => s + c.qty, 0);
            el.querySelectorAll('.ci').forEach(x => x.remove());
            if (!cart.length) {
                em.style.display = 'flex';
            } else {
                em.style.display = 'none';
                cart.forEach(item => {
                    const brand = BRANDS.find(b => b.id === item.brand),
                        color = BRAND_CSS_COLORS[brand?.color] || 'var(--lime)';
                    const d = document.createElement('div');
                    d.className = 'ci';
                    d.innerHTML = `<span class="ci-em">${item.e}</span>
        <div class="ci-info"><div class="ci-name">${item.name}</div>
          <div class="ci-meta"><div class="ci-brand-dot" style="background:${color}"></div>${brand?.name} · ${item.cat}/${item.sub}</div>
        </div>
        <div class="ci-qty">
          <button class="qb" onclick="changeQty(${item.id},-1)">−</button>
          <span class="qv">${item.qty}</span>
          <button class="qb" onclick="changeQty(${item.id},1)">+</button>
        </div>
        <span class="ci-tot">$${(item.price*item.qty).toFixed(2)}</span>
        <button class="btn-del" onclick="removeItem(${item.id})"><i class="bi bi-x-lg"></i></button>`;
                    el.appendChild(d);
                });
            }
            renderSum();
        }

        function renderSum() {
            const d = Math.min(100, Math.max(0, parseFloat(document.getElementById('disc').value) || 0));
            const sub = cart.reduce((s, c) => s + c.price * c.qty, 0);
            const da = sub * d / 100,
                tax = (sub - da) * .10,
                tot = sub - da + tax;
            document.getElementById('sSub').textContent = '$' + sub.toFixed(2);
            document.getElementById('sDisc').textContent = '−$' + da.toFixed(2);
            document.getElementById('sTax').textContent = '$' + tax.toFixed(2);
            document.getElementById('sTotal').textContent = '$' + tot.toFixed(2);
            document.getElementById('btnAmt').textContent = '$' + tot.toFixed(2);
            document.getElementById('btnCharge').disabled = cart.length === 0;
            if (document.getElementById('npOv').classList.contains('show')) updateNpChg();
        }

        function getTotal() {
            const d = Math.min(100, Math.max(0, parseFloat(document.getElementById('disc').value) || 0));
            const sub = cart.reduce((s, c) => s + c.price * c.qty, 0);
            return (sub * (1 - d / 100)) * 1.10;
        }

        function setPay(m, btn) {
            payMethod = m;
            document.querySelectorAll('.pb').forEach(b => b.className = 'pb');
            btn.classList.add('on-' + m);
        }

        function buildNpGrid() {
            document.getElementById('npGrid').innerHTML = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '⌫', '0', 'OK'].map(
                    k => `<button class="nk${k==='⌫'?' del':k==='OK'?' ok':''}" onclick="npKey('${k}')">${k}</button>`)
                .join('');
        }

        function openNP() {
            if (!cart.length) return;
            if (payMethod !== 'cash') {
                processOrder(0);
                return;
            }
            npRaw = '';
            updateNpScr();
            document.getElementById('npOv').classList.add('show');
        }

        function closeNP() {
            document.getElementById('npOv').classList.remove('show');
        }

        function npKey(k) {
            if (k === '⌫') npRaw = npRaw.slice(0, -1);
            else if (k === 'OK') {
                npConfirm();
                return;
            } else if (npRaw.length < 8) npRaw += k;
            updateNpScr();
            updateNpChg();
        }

        function npExact() {
            npRaw = Math.round(getTotal() * 100).toString();
            updateNpScr();
            updateNpChg();
        }

        function npRnd(v) {
            npRaw = (v * 100).toString();
            updateNpScr();
            updateNpChg();
        }

        function updateNpScr() {
            document.getElementById('npScr').textContent = '$' + (parseFloat(npRaw || '0') / 100).toFixed(2);
        }

        function updateNpChg() {
            const paid = parseFloat(npRaw || '0') / 100,
                chg = paid - getTotal();
            const el = document.getElementById('npChg');
            el.textContent = '$' + Math.abs(chg).toFixed(2);
            el.className = 'chg' + (chg < 0 ? ' neg' : '');
        }

        function npConfirm() {
            const paid = parseFloat(npRaw || '0') / 100;
            if (paid < getTotal()) {
                toast('Amount is less than total', 'err');
                return;
            }
            closeNP();
            processOrder(paid);
        }

        function processOrder(paidCash) {
            const d = Math.min(100, Math.max(0, parseFloat(document.getElementById('disc').value) || 0));
            const sub = cart.reduce((s, c) => s + c.price * c.qty, 0);
            const da = sub * d / 100,
                tax = (sub - da) * .10,
                tot = sub - da + tax,
                chg = paidCash - tot;
            document.getElementById('rcDate').textContent = new Date().toLocaleString();
            document.getElementById('rcOrd').textContent = '#ORD-' + ordSeq;
            document.getElementById('rcItems').innerHTML = cart.map(i =>
                `<div class="rci"><span class="l2"><span>${i.e}</span>${i.name} ×${i.qty}</span><span class="r2">$${(i.price*i.qty).toFixed(2)}</span></div>`
                ).join('');
            document.getElementById('rcSub').textContent = '$' + sub.toFixed(2);
            document.getElementById('rcDisc').textContent = '−$' + da.toFixed(2);
            document.getElementById('rcTax').textContent = '$' + tax.toFixed(2);
            document.getElementById('rcTotal').textContent = '$' + tot.toFixed(2);
            document.getElementById('rcMeth').textContent = payMethod.toUpperCase();
            if (payMethod === 'cash') {
                document.getElementById('rcPaidRow').style.display = 'flex';
                document.getElementById('rcPaid').textContent = '$' + paidCash.toFixed(2);
                document.getElementById('rcChgRow').style.display = 'flex';
                document.getElementById('rcChg').textContent = '$' + chg.toFixed(2);
            } else {
                document.getElementById('rcPaidRow').style.display = 'none';
                document.getElementById('rcChgRow').style.display = 'none';
            }
            document.getElementById('rcOv').classList.add('show');
            cart = [];
            document.getElementById('disc').value = 0;
            ordSeq++;
            document.getElementById('ordLabel').textContent = `#ORD-${ordSeq}`;
            renderCart();
        }

        function closeRC() {
            document.getElementById('rcOv').classList.remove('show');
        }

        function toast(msg, type = 'ok') {
            const el = document.getElementById('toasts'),
                t = document.createElement('div');
            t.className = `tn ${type}`;
            t.innerHTML = `<i class="bi ${type==='ok'?'bi-check-circle-fill':'bi-x-circle-fill'}"></i>${msg}`;
            el.appendChild(t);
            setTimeout(() => t.remove(), 2300);
        }


function toggleCart() {
  const panel = document.getElementById('cartPanel');
  const backdrop = document.getElementById('cartBackdrop');
  const isOpen = panel.classList.contains('open');
  panel.classList.toggle('open', !isOpen);
  backdrop.classList.toggle('show', !isOpen);
}

    </script>
@endpush
