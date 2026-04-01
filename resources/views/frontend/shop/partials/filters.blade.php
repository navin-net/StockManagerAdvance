{{-- resources/views/frontend/shop/partials/filters.blade.php --}}

{{-- ── CATEGORIES ── --}}
<div class="filter-card">
    <h3>Categories</h3>
    <div class="filter-pills">
        <button class="filter-pill pill-category" data-value="">All</button>
        @foreach($categories ?? [] as $cat)
            <button class="filter-pill pill-category" data-value="{{ $cat->slug ?? $cat->name }}">{{ $cat->name }}</button>
        @endforeach
        {{-- Fallback demo pills if no categories passed --}}
        @if(empty($categories))
            <button class="filter-pill pill-category" data-value="electronics">Electronics</button>
            <button class="filter-pill pill-category" data-value="clothing">Clothing</button>
            <button class="filter-pill pill-category" data-value="home">Home & Living</button>
            <button class="filter-pill pill-category" data-value="beauty">Beauty</button>
            <button class="filter-pill pill-category" data-value="sports">Sports</button>
        @endif
    </div>
</div>

{{-- ── BRANDS ── --}}
<div class="filter-card">
    <h3>Brands</h3>
    <div class="filter-pills">
        <button class="filter-pill pill-brand accent" data-value="">All Brands</button>
        @foreach($brands ?? [] as $brand)
            <button class="filter-pill pill-brand accent" data-value="{{ $brand->slug ?? $brand->name }}">{{ $brand->name }}</button>
        @endforeach
        {{-- Fallback demo pills --}}
        @if(empty($brands))
            <button class="filter-pill pill-brand accent" data-value="apple">Apple</button>
            <button class="filter-pill pill-brand accent" data-value="samsung">Samsung</button>
            <button class="filter-pill pill-brand accent" data-value="nike">Nike</button>
            <button class="filter-pill pill-brand accent" data-value="sony">Sony</button>
            <button class="filter-pill pill-brand accent" data-value="adidas">Adidas</button>
        @endif
    </div>
</div>

{{-- ── PRICE RANGE ── --}}
<div class="filter-card">
    <h3>Price Range</h3>
    <div class="price-range-wrap">
        <input
            type="range"
            id="maxPrice"
            min="0"
            max="{{ $maxProductPrice ?? 9999 }}"
            step="1"
            value="{{ request('max_price', $maxProductPrice ?? 9999) }}">
        <div class="price-labels">
            <span>$0</span>
            <span id="maxPriceLabel">${{ number_format(request('max_price', $maxProductPrice ?? 9999)) }}</span>
        </div>
    </div>
    <div style="display:flex;gap:0.5rem;margin-top:1rem;">
        <div style="flex:1;">
            <label style="font-size:0.75rem;color:var(--muted);display:block;margin-bottom:0.3rem;">Min Price</label>
            <input type="number"
                id="minPrice"
                placeholder="$0"
                value="{{ request('min_price', '') }}"
                min="0"
                style="width:100%;padding:0.55rem 0.75rem;border:1.5px solid var(--border);border-radius:0.75rem;font-family:'Satoshi',sans-serif;font-size:0.85rem;outline:none;color:var(--ink);">
        </div>
        <div style="flex:1;">
            <label style="font-size:0.75rem;color:var(--muted);display:block;margin-bottom:0.3rem;">Max Price</label>
            <input type="number"
                id="maxPriceInput"
                placeholder="$9999"
                value="{{ request('max_price', '') }}"
                min="0"
                style="width:100%;padding:0.55rem 0.75rem;border:1.5px solid var(--border);border-radius:0.75rem;font-family:'Satoshi',sans-serif;font-size:0.85rem;outline:none;color:var(--ink);"
                oninput="document.getElementById('maxPrice').value=this.value;document.getElementById('maxPriceLabel').textContent='$'+parseInt(this.value||0).toLocaleString()">
        </div>
    </div>
</div>

{{-- ── RATING ── --}}
<div class="filter-card">
    <h3>Minimum Rating</h3>
    @foreach([5,4,3,2,1] as $r)
    <label class="rating-row">
        <input type="radio" name="rating" value="{{ $r }}">
        <div class="stars">
            @for($i = 1; $i <= 5; $i++)
                <span class="star {{ $i <= $r ? '' : 'empty' }}">★</span>
            @endfor
        </div>
        <span class="rating-label">& up</span>
    </label>
    @endforeach
</div>

{{-- ── ACTIONS ── --}}
<div style="padding: 0 0 0.5rem;">
    <button class="btn-apply" onclick="applyFilters()">Apply Filters</button>
    <button class="btn-reset" onclick="clearAllFilters()">Reset All</button>
</div>
