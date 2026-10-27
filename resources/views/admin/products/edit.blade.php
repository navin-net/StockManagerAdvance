@extends('admin.layouts.master')
@section('title', __('Edit product'))
@section('content')
<x-admin.page :title="__('Edit product')" :subtitle="__('Keep product information, pricing, and images up to date.')" eyebrow="Products" :back="route('products.index')">
    <x-slot:actions><a class="btn record-button" href="{{ route('products.show', $product->id) }}"><i class="bi bi-eye" aria-hidden="true"></i>{{ __('View product') }}</a></x-slot:actions>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" data-record-form data-success-url="{{ route('products.show', $product->id) }}">
        @csrf @method('PUT')
        <div class="record-feedback record-feedback-error" data-record-feedback tabindex="-1" role="alert" hidden></div>
        <div class="record-layout">
            <div class="record-stack">
                <x-admin.panel :title="__('Product information')" :subtitle="__('The essentials used to identify this product.')" icon="bi-box-seam">
                    <div class="record-fields">
                        <x-admin.field name="name" :label="__('Product name')" :value="$product->name" required maxlength="191" />
                        <x-admin.field name="code" :label="__('Product code')" :value="$product->code" required maxlength="191" :hint="__('Use a unique code for stock and sales.')" />
                        <x-admin.field name="second_name" :label="__('Second name')" :value="$product->second_name" required maxlength="191" />
                        <x-admin.field name="brand_id" :label="__('Brand')" required><select id="brand_id" name="brand_id" class="form-select" required><option value="">{{ __('Select a brand') }}</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>@endforeach</select></x-admin.field>
                        <x-admin.field name="category_id" :label="__('Category')" required><select id="category_id" name="category_id" class="form-select" required data-category-picker data-subcategories-url="{{ route('products.subcategories', '__ID__') }}"><option value="">{{ __('Select a category') }}</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></x-admin.field>
                        <x-admin.field name="subcategory_id" :label="__('Subcategory')"><select id="subcategory_id" name="subcategory_id" class="form-select"><option value="">{{ __('Select a subcategory') }}</option>@foreach($subcategories as $subcategory)<option value="{{ $subcategory->id }}" @selected(old('subcategory_id', $product->subcategory_id) == $subcategory->id)>{{ $subcategory->name }}</option>@endforeach</select></x-admin.field>
                        <x-admin.field name="unit_id" :label="__('Unit')" required><select id="unit_id" name="unit_id" class="form-select" required>@foreach($units as $unit)<option value="{{ $unit->id }}" @selected(old('unit_id', $product->unit_id) == $unit->id)>{{ $unit->name }}</option>@endforeach</select></x-admin.field>
                        <x-admin.field name="quality_id" :label="__('Quality')" required><select id="quality_id" name="quality_id" class="form-select" required>@foreach($qualities as $quality)<option value="{{ $quality->id }}" @selected(old('quality_id', $product->quality_id) == $quality->id)>{{ $quality->name }}</option>@endforeach</select></x-admin.field>
                        <x-admin.field name="description" :label="__('Description')" wide><textarea class="form-control" id="description" name="description" rows="5" placeholder="{{ __('Add useful details about this product…') }}">{{ old('description', $product->description) }}</textarea></x-admin.field>
                    </div>
                </x-admin.panel>
                <x-admin.panel :title="__('Pricing & inventory')" :subtitle="__('Set prices and review current stock availability.')" icon="bi-tag">
                    <div class="record-fields">
                        <x-admin.field name="cost_price" :label="__('Cost price')" type="number" :value="$product->cost_price" required min="0" max="999999.99" step="0.01" />
                        <x-admin.field name="selling_price" :label="__('Selling price')" type="number" :value="$product->selling_price" required min="0" max="999999.99" step="0.01" />
                        <x-admin.field name="expiry_date" :label="__('Expiry date')" type="date" :value="$product->expiry_date ? \Carbon\Carbon::parse($product->expiry_date)->toDateString() : ''" />
                        <div class="record-field"><label for="stock_display">{{ __('Available stock') }}</label><input class="form-control" id="stock_display" value="{{ $product->stock_quantity }}" readonly><small class="record-hint">{{ __('Stock is updated through purchases and sales.') }}</small></div>
                    </div>
                </x-admin.panel>
                <x-admin.panel :title="__('Product images')" :subtitle="__('JPG or PNG, up to 2 MB per image.')" icon="bi-images">
                    <div class="record-fields">
                        <x-admin.field name="image" :label="__('Main image')"><div class="record-upload"><img class="record-upload-preview" id="product-preview" src="{{ $product->image ? Storage::url($product->image) : asset('placeholder.svg') }}" alt="{{ $product->name }}"><input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png" data-image-preview="product-preview"></div></x-admin.field>
                        <x-admin.field name="image_review" :label="__('Gallery images')" :hint="__('Selecting new gallery images replaces the current gallery.')"><div class="record-upload"><i class="bi bi-images fs-2 text-brand" aria-hidden="true"></i><p class="record-hint mt-2">{{ trans_choice(':count current image|:count current images', $product->images->count()) }}</p><input type="file" name="image_review[]" id="image_review" class="form-control" multiple accept="image/jpeg,image/png">@if($product->images->isNotEmpty())<div class="record-thumbnails">@foreach($product->images as $image)<div class="record-thumbnail"><img src="{{ Storage::url($image->image_review) }}" alt="{{ __('Current gallery image :number', ['number' => $loop->iteration]) }}" loading="lazy"></div>@endforeach</div>@endif</div></x-admin.field>
                    </div>
                </x-admin.panel>
            </div>
            <aside class="record-sidebar">
                <section class="record-panel record-summary"><div class="record-summary-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></div><h2>{{ $product->name }}</h2><p>{{ $product->code }}</p><x-admin.status :value="$product->stock_quantity > 0 ? 'in stock' : 'out of stock'" /><dl class="record-meta"><div><dt>{{ __('Stock on hand') }}</dt><dd>{{ $product->stock_quantity }}</dd></div><div><dt>{{ __('Selling price') }}</dt><dd>{{ number_format($product->selling_price, 2) }}</dd></div><div><dt>{{ __('Gallery images') }}</dt><dd>{{ $product->images->count() }}</dd></div></dl></section>
                <div class="record-note"><strong><i class="bi bi-info-circle" aria-hidden="true"></i> {{ __('Keep your catalog consistent') }}</strong>{{ __('Product codes must be unique. Changes to prices apply to future sales; existing sale items keep their recorded prices.') }}</div>
            </aside>
        </div>
        <x-admin.form-actions :back="route('products.index')" label="Save product" />
    </form>
</x-admin.page>
@endsection
