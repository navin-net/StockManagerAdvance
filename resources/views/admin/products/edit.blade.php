@extends('admin.layouts.master')
@section('title', $pageTitle)
@section('content')
    <div class="container-fluid">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <div class="pagetitle">
                    <h1 class="h3 fw-bold mb-2">{{ $pageTitle }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            @foreach ($breadcrumbs as $breadcrumb)
                                @if (!$breadcrumb['active'])
                                    <li class="breadcrumb-item">
                                        <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                                            {{ $breadcrumb['label'] }}
                                        </a>
                                    </li>
                                @else
                                    <li class="breadcrumb-item active text-muted" aria-current="page">
                                        {{ $breadcrumb['label'] }}
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <small class="text-muted fw-semibold">
                    {{ __('messages.ip_address') }}:
                </small>
                <span class="fw-semibold text-primary">
                    {{ auth()->user()->ip_address }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="formError" class="alert alert-danger d-none" role="alert"></div>
                        <form action="{{ route('products.update', $product) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">{{ __('messages.name') }}</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ $product->name ?? 'N/A' }}" class="form-control" required>
                                    <div class="invalid-feedback" id="name_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="code" class="form-label">{{ __('messages.code') }}</label>
                                    <div class="input-group">
                                        <input type="text" name="code" id="code"
                                            value="{{ $product->code ?? 'N/A' }}" class="form-control">
                                    </div>
                                    <div class="invalid-feedback" id="code_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="second_name" class="form-label">{{ __('messages.second_name') }}</label>
                                    <input type="text" name="second_name" id="second_name"
                                        value="{{ $product->second_name }}" class="form-control" required>
                                    <div class="invalid-feedback" id="second_name_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="brand_id" class="form-label">{{ __('messages.brand') }}</label>
                                    <select name="brand_id" id="brand_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_brand') }}</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id ?? null) == $brand->id)>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    <div class="invalid-feedback" id="brand_id_error"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">{{ __('messages.categories') }}</label>

                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subcategory_id"
                                        class="form-label">{{ __('messages.sub_categories') }}</label>

                                    <select name="subcategory_id" id="subcategory_id" class="form-select"
                                        {{ empty($subcategories) ? 'disabled' : '' }}>
                                        <option value="">{{ __('messages.select_subcategory') }}</option>

                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}"
                                                {{ old('subcategory_id', $product->subcategory_id ?? '') == $subcategory->id ? 'selected' : '' }}>
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="quality_id" class="form-label">{{ __('messages.quality') }}</label>
                                    <select name="quality_id" id="quality_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_quality') }}</option>
                                        @foreach ($qualities as $quality)
                                            <option value="{{ $quality->id }}" @selected(old('brand_id', $product->quality_id ?? null) == $quality->id)>
                                                {{ $quality->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="quality_id_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="unit_id" class="form-label">{{ __('messages.unit') }}</label>
                                    <select name="unit_id" id="unit_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_unit') }}</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" @selected(old('brand_id', $product->unit_id ?? null) == $unit->id)>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="unit_id_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cost_price" class="form-label">{{ __('messages.cost_price') }}</label>
                                    <input type="number" name="cost_price" id="cost_price"
                                        value="{{ $product->cost_price ?? 'N/A' }}" class="form-control" step="0.01"
                                        min="0" required>
                                    <div class="invalid-feedback" id="cost_price_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="selling_price"
                                        class="form-label">{{ __('messages.selling_price') }}</label>
                                    <input type="number" name="selling_price" id="selling_price"
                                        value="{{ $product->selling_price ?? 'N/A' }}" class="form-control"
                                        step="0.01" min="0" required>
                                    <div class="invalid-feedback" id="selling_price_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="image" class="form-label">{{ __('messages.image') }}</label>
                                    <input type="file" name="image" id="image" class="form-control"
                                        accept="image/jpeg,image/png,image/jpg">
                                    <div class="invalid-feedback" id="image_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="image" class="form-label">{{ __('messages.image_review') }}</label>
                                    {{-- <label for="image_review" class="form-label">{{ __('messages.image_review') }}</label> --}}
                                    <input type="file" name="image_review[]" multiple class="form-control"
                                        accept="image/jpeg,image/png,image/jpg">
                                    <div class="invalid-feedback" id="image_review.0_error"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>

                                <textarea name="description" id="description" class="form-control" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
                            </div>

                            <div class="mb-3 text-md-end">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-save"></i> {{ __('messages.submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
ClassicEditor.create(document.querySelector('#description'), {
    toolbar: [
        'heading', '|',
        'bold', 'italic', '|',
        'link', 'bulletedList', 'numberedList', '|',
        'blockQuote', 'insertTable', '|',
        'undo', 'redo'
    ],
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
    }
}).catch(error => {
    console.error(error);
});

        $(document).ready(function() {

            $('#category_id').on('change', function() {
                let categoryId = $(this).val();
                let subcategorySelect = $('#subcategory_id');

                subcategorySelect.prop('disabled', true);
                subcategorySelect.html('<option value="">{{ __('messages.Loading') }}</option>');

                if (!categoryId) {
                    subcategorySelect.html(
                        '<option value="">{{ __('messages.select_subcategory') }}</option>'
                    );
                    return;
                }

                $.ajax({
                    url: "{{ route('products.subcategories', ':id') }}".replace(':id', categoryId),
                    type: 'GET',
                    success: function(data) {
                        subcategorySelect.prop('disabled', false);
                        subcategorySelect.html(
                            '<option value="">{{ __('messages.select_sub_categories') }}</option>'
                        );

                        $.each(data, function(key, subcategory) {
                            subcategorySelect.append(
                                `<option value="${subcategory.id}">${subcategory.name}</option>`
                            );
                        });
                    }
                });
            });
        });
    </script>
@endpush
