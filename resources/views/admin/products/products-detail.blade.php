@extends('admin.layouts.master')
@section('title', $product->name)
@section('content')
    <div class="container my-5">
        <div class="pagetitle mb-4">
            <h1 class="display-6 fw-bold">{{ $pageTitle }}</h1>
            <nav>
                <ol class="breadcrumb rounded-3 p-2">
                    @foreach ($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active text-muted' : '' }}">
                            @if (!$breadcrumb['active'])
                                <a href="{{ $breadcrumb['url'] }}"
                                    class="text-primary text-decoration-none">{{ $breadcrumb['label'] }}</a>
                            @else
                                {{ $breadcrumb['label'] }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="main-image-container mb-3 text-center">
                            <img id="mainImage"
                                src="{{ $product->image ? Storage::url($product->image) : asset('no-image.png') }}"
                                class="img-fluid main-image" alt="Main Image">
                        </div>
                        <div class="row thumbnail-row g-2">
                            @foreach ($images as $img)
                                <div class="col-6 col-md-3 image-item" id="image-{{ $img->id }}">
                                    <div class="position-relative thumbnail-container">
                                        <img src="{{ Storage::url($img->image_review) }}"
                                            class="img-fluid thumbnail rounded" alt="Thumbnail">

                                        <!-- Delete Button -->
                                        <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-image"
                                            data-id="{{ $img->id }}" title="Delete">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="product-details">
                            <div class="row mb-3">
                                <div class="col-md-4 mb-4 text-md-end">{{ __('messages.barcode') }} &amp;
                                    {{ __('messages.qr_code') }}</div>
                                <div class="col-md-8 mb-4">
                                    <div class="d-flex align-items-center">
                                        <svg id="barcode" class="barcode me-3"></svg>
                                        <div id="qrcode" class="qrcode"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">{{ __('messages.code') }}</div>
                                <div class="col-md-8">
                                    <div>{{ $product->code ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">{{ __('messages.name') }}</div>
                                <div class="col-md-8">
                                    <div>{{ $product->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">{{ __('messages.second_name') }}</div>
                                <div class="col-md-8">
                                    <div>{{ $product->second_name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">Brand</div>
                                <div class="col-md-8">
                                    <div>{{ $product->brand_name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">{{ __('messages.categories') }}</div>
                                <div class="col-md-8">
                                    <div>{{ $product->category_name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">{{ __('messages.sub_categories') }}</div>
                                <div class="col-md-8">
                                    <div>{{ $product->subcategory_name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">{{ __('messages.unit') }}</div>
                                <div class="col-md-8">
                                    <div>{{ $product->unit_name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">{{ __('messages.cost_price') }}</div>
                                <div class="col-md-8">
                                    <div>{{ number_format($product->cost_price ?? 0, 2) }}</div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">{{ __('messages.selling_price') }}</div>
                                <div class="col-md-8">
                                    <div>{{ number_format($product->selling_price ?? 0, 2) }}</div>
                                </div>
                            </div>



                            <div class="row mb-2">
                                <div class="col-md-4 text-md-end ">Tax Method</div>
                                <div class="col-md-8">
                                    <div>{{ $product->tax_method ?? 'Exclusive' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex action-buttons">
                    {{-- <button class="btn btn-primary flex-grow-1">
                        <i class="bi bi-printer"></i> Print Barcode/
                    </button> --}}
                    <button class="btn btn-info flex-grow-1">
                        <i class="bi bi-file-earmark-pdf"></i> {{ __('messages.pdf') }}
                    </button>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning flex-grow-1">
                        <i class="bi bi-pencil-square"></i> {{ __('messages.edit') }}
                    </a>
                    <button type="button" class="btn btn-danger  flex-grow-1" data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{ $product->id }}">
                        <i class="bi bi-trash"></i> {{ __('messages.delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        {{ __('messages.confirm_delete') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{ __('messages.delete_confirm') }}
                    <br>
                    {{-- <small class="text-muted">This action cannot be undone.</small> --}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('messages.cancel') }}
                    </button>

                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            {{ __('messages.yes') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <div class="modal fade" id="deleteImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete this image?</p>
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <script>
        let imageIdToDelete = null;
        const barcodeValue = String(@json($product->code ?? ''));
        const id = String(@json($product->id ?? ''));
        const baseUrl = "{{ url('/') }}";
        const qrValue = `${baseUrl}/admin/products/show/${encodeURIComponent(id)}`;


        JsBarcode("#barcode", barcodeValue, {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 60,
            displayValue: true,
            margin: 10,
            fontSize: 14,
            background: "#fff"
        });
        const qrContainer = document.getElementById("qrcode");
        qrContainer.innerHTML = "";
        new QRCode(qrContainer, {
            text: qrValue,
            width: 128,
            height: 128,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.M
        });



        document.querySelectorAll('.thumbnail').forEach(img => {
            img.addEventListener('click', function() {
                document.getElementById('mainImage').src = this.src;
            });
        });





// Delete Products
        $(document).on('click', '.delete-product', function() {
            const productId = $(this).data('id');
            const deleteUrl = "{{ url('admin/products') }}/" + productId;
            $('#deleteProductForm').attr('action', deleteUrl);
            $('#deleteProductModal').modal('show');
        });
        $('#deleteProductForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const action = form.attr('action');

            $.ajax({
                url: action,
                type: 'DELETE',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#deleteProductModal').modal('hide');
                    table.ajax.reload();
                    const successAlert = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                    $('#alertsContainer').html(successAlert);
                    setTimeout(function() {
                        $('#alertsContainer .alert').alert('close');
                    }, 5000);
                },
                error: function(xhr) {
                    $('#deleteProductModal').modal('hide');

                    const errorMessage = xhr.responseJSON?.error || xhr.responseJSON
                        ?.message ||
                        'Failed to delete the product. Please try again.';

                    const errorAlert = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${errorMessage}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;

                    $('#alertsContainer').html(errorAlert);

                    setTimeout(function() {
                        $('#alertsContainer .alert').alert('close');
                    }, 5000);
                }

            });
        });


// Delete Image
        document.querySelectorAll('.delete-image').forEach(btn => {
            btn.addEventListener('click', function() {
                imageIdToDelete = this.dataset.id;
                const modal = new bootstrap.Modal(document.getElementById('deleteImageModal'));
                modal.show();
            });
        });
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (!imageIdToDelete) return;
            fetch(`/product/image/${imageIdToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        const el = document.getElementById(`image-${imageIdToDelete}`);
                        el && el.remove();
                    }
                    imageIdToDelete = null;
                    const modalEl = document.getElementById('deleteImageModal');
                    bootstrap.Modal.getInstance(modalEl).hide();
                });
        });


 </script>
@endpush
