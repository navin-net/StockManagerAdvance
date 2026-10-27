@extends('admin.layouts.master')
@section('title', $product->name)
@section('content')
<x-admin.page :title="$product->name" :subtitle="__('Product details, pricing, and inventory at a glance.')" eyebrow="Products" :back="route('products.index')">
    <x-slot:actions><a class="btn btn-primary" href="{{ route('products.edit', $product->id) }}"><i class="bi bi-pencil-square" aria-hidden="true"></i>{{ __('Edit product') }}</a></x-slot:actions>
    <div class="record-metrics">
        <div class="record-metric"><span class="record-metric-label"><i class="bi bi-box" aria-hidden="true"></i>{{ __('Available stock') }}</span><strong class="record-metric-value">{{ number_format($product->stock_quantity) }}</strong><small>{{ __('Units on hand') }}</small></div>
        <div class="record-metric"><span class="record-metric-label"><i class="bi bi-tag" aria-hidden="true"></i>{{ __('Selling price') }}</span><strong class="record-metric-value">{{ number_format($product->selling_price, 2) }}</strong><small>{{ __('Per unit') }}</small></div>
        <div class="record-metric"><span class="record-metric-label"><i class="bi bi-receipt" aria-hidden="true"></i>{{ __('Cost price') }}</span><strong class="record-metric-value">{{ number_format($product->cost_price, 2) }}</strong><small>{{ __('Per unit') }}</small></div>
    </div>
    <div class="record-layout record-layout-wide-aside">
        <div class="record-stack">
            <x-admin.panel :title="__('Product information')" :subtitle="__('Identification and classification')" icon="bi-box-seam"><dl class="record-data">
                @foreach([__('Product code') => $product->code, __('Second name') => $product->second_name, __('Brand') => $product->brand_name, __('Category') => $product->category_name, __('Subcategory') => $product->subcategory_name, __('Unit') => $product->unit_name, __('Quality') => $product->quality_name, __('Expiry date') => $product->expiry_date] as $label => $value)<div><dt>{{ $label }}</dt><dd>{{ filled($value) ? $value : '—' }}</dd></div>@endforeach
            </dl></x-admin.panel>
            <x-admin.panel :title="__('Description')" icon="bi-text-left"><div class="record-description">{{ filled($product->description) ? strip_tags($product->description) : __('No description has been added for this product.') }}</div></x-admin.panel>
            <x-admin.panel :title="__('Product identification')" :subtitle="__('Use the product code when finding items at the register.')" icon="bi-upc-scan"><div class="record-identity"><span class="record-summary-icon"><i class="bi bi-upc-scan" aria-hidden="true"></i></span><div><strong>{{ $product->code }}</strong><small>{{ $product->name }}</small></div></div></x-admin.panel>
        </div>
        <aside class="record-sidebar">
            <x-admin.panel :title="__('Product gallery')" icon="bi-images">
                <img id="record-main-image" class="record-product-image" src="{{ $product->image ? Storage::url($product->image) : asset('placeholder.svg') }}" alt="{{ $product->name }}">
                @if($images->isNotEmpty())<div class="record-thumbnails">@if($product->image)<button type="button" class="record-thumbnail is-active" data-gallery-image="{{ Storage::url($product->image) }}" aria-label="{{ __('Show main image') }}" aria-pressed="true"><img src="{{ Storage::url($product->image) }}" alt=""></button>@endif @foreach($images as $image)<button type="button" class="record-thumbnail" data-gallery-image="{{ Storage::url($image->image_review) }}" aria-label="{{ __('Show gallery image :number', ['number' => $loop->iteration]) }}" aria-pressed="false"><img src="{{ Storage::url($image->image_review) }}" alt="" loading="lazy"></button>@endforeach</div>@endif
                <div class="mt-3"><x-admin.status :value="$product->stock_quantity > 0 ? 'in stock' : 'out of stock'" /></div>
            </x-admin.panel>
            <div class="record-note"><strong>{{ __('Need to make a change?') }}</strong>{{ __('Update the product information, replace images, or adjust pricing from the edit page.') }}<a class="d-block mt-2 text-brand" href="{{ route('products.edit', $product->id) }}">{{ __('Edit product') }} <i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>
        </aside>
    </div>
</x-admin.page>
@endsection
