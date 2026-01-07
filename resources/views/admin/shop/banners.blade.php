@extends('layouts.master')

@section('title', __('Banners Management'))

@section('content')

    <div class="container py-5">
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
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow-sm">
            {{-- <div class="card-header">
                <h5 class="mb-0">Banner / Slider Management</h5>
            </div> --}}


            <form method="POST" action="{{ route('banners.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="card-body">

                    <!-- Banner Item -->
                    <div id="bannerContainer">

                        @foreach ($banners as $index => $banner)
                            <!-- Banner Item -->
                            <div class="banner-card mb-4" data-index="{{ $index }}">

                                <input type="hidden" name="banners[{{ $index }}][id]" value="{{ $banner->id }}">
                                <input type="hidden" name="banners[{{ $index }}][_delete]" value="0"
                                    class="delete-flag">

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong></strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-banner">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="banners[{{ $index }}][title]"
                                            class="form-control" value="{{ $banner->title }}" placeholder="Banner title">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Link URL</label>
                                        <input type="url" name="banners[{{ $index }}][link]"
                                            class="form-control" value="{{ $banner->link }}"
                                            placeholder="https://example.com">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Image</label>
                                        <input type="file" name="banners[{{ $index }}][image]"
                                            class="form-control image-input" accept="image/*">

                                        @if ($banner->image)
                                            <img src="{{ asset('storage/banners/' . $banner->image) }}"
                                                class="banner-image mt-2" style="max-height:120px;">
                                        @else
                                            <img class="banner-image mt-2 d-none" style="max-height:120px;">
                                        @endif
                                    </div>

                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox"
                                                name="banners[{{ $index }}][status]" value="1"
                                                {{ $banner->status ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>


                    <!-- Add Banner Button -->
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-primary" id="addBanner">
                            <i class="bi bi-plus-circle"></i> Add Banner
                        </button>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview (delegated)
            document.addEventListener('change', function(e) {
                if (!e.target || !e.target.classList || !e.target.classList.contains('image-input')) return;

                const input = e.target;
                const bannerCard = input.closest('.banner-card');
                const img = bannerCard ? bannerCard.querySelector('.banner-image') : null;
                const file = input.files && input.files[0];

                if (file && img) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        img.src = evt.target.result;
                        img.classList.remove('d-none');
                        img.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Add new banner UI
            const addBtn = document.getElementById('addBanner');
            if (addBtn) {
                addBtn.addEventListener('click', function(e) {
                    // prevent accidental submit just in case
                    e.preventDefault();

                    const container = document.querySelector('#bannerContainer');
                    const newIndex = document.querySelectorAll('.banner-card').length;

                    const banner = document.createElement('div');
                    banner.className = 'banner-card mb-4';
                    banner.setAttribute('data-index', newIndex);

                    banner.innerHTML = `
                        <input type="hidden" name="banners[${newIndex}][id]" value="">
                        <input type="hidden" name="banners[${newIndex}][_delete]" value="0" class="delete-flag">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Banner</strong>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-banner">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" name="banners[${newIndex}][title]" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Link URL</label>
                                <input type="url" name="banners[${newIndex}][link]" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <input type="file" name="banners[${newIndex}][image]" class="form-control image-input" accept="image/*">
                                <img class="banner-image mt-2 d-none" style="max-height:120px;">
                            </div>

                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="banners[${newIndex}][status]"
                                           value="1"
                                           checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>
                    `;

                    if (container) container.appendChild(banner);
                });
            }


            // Remove banner (delegated click)
            document.addEventListener('click', function(e) {
                const removeBtn = e.target.closest ? e.target.closest('.remove-banner') : null;
                if (!removeBtn) return;

                const bannerCard = removeBtn.closest('.banner-card');
                if (!bannerCard) return;

                // If banner exists in DB → mark for delete
                const deleteInput = bannerCard.querySelector('.delete-flag');

                if (deleteInput) {
                    deleteInput.value = 1;
                    bannerCard.style.display = 'none';
                } else {
                    // New banner (not saved yet) → remove from DOM
                    bannerCard.remove();
                }
            });
        });
    </script>
@endpush
