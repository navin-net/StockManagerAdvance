@extends('layouts.master')
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
                    <div class="main-image-container mb-3">
                        <img src="{{ $product->image ? asset($product->image) : 'no-image.png' }}" alt="No Image Available" class="img-fluid main-image">
                    </div>
                    <div class="row thumbnail-row">
                        @foreach ($images as $image)
                        <div class="col-6">
                            <div class="thumbnail-container">
                                <img src="{{ $image ? asset($image) : 'no-image.png' }}" alt="No Image Available" class="img-fluid thumbnail">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="product-details">
                        <div class="row mb-3">
                            <div class="col-md-4 text-md-end ">Barcode & QRcode</div>
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <img src="barcode.png" alt="Barcode" class="barcode me-2">
                                    <img src="qrcode.png" alt="QR Code" class="qrcode">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Type</div>
                            <div class="col-md-8">
                                <div >{{ $product->type ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Name</div>
                            <div class="col-md-8">
                                <div >{{ $product->name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Code</div>
                            <div class="col-md-8">
                                <div >{{ $product->code ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Brand</div>
                            <div class="col-md-8">
                                <div >{{ $product->brand_name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Category</div>
                            <div class="col-md-8">
                                <div >{{ $product->category ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Unit</div>
                            <div class="col-md-8">
                                <div >{{ $product->unit ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Cost</div>
                            <div class="col-md-8">
                                <div >{{ number_format($product->cost ?? 0, 2) }}</div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Price</div>
                            <div class="col-md-8">
                                <div >{{ number_format($product->price ?? 0, 2) }}</div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Tax Rate</div>
                            <div class="col-md-8">
                                <div >{{ $product->tax_rate ?? 'No Tax' }}</div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 text-md-end ">Tax Method</div>
                            <div class="col-md-8">
                                <div >{{ $product->tax_method ?? 'Exclusive' }}</div>
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
                <button class="btn btn-primary flex-grow-1">
                    <i class="bi bi-printer"></i> Print Barcode/
                </button>
                <button class="btn btn-info flex-grow-1">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </button>
                <button class="btn btn-warning flex-grow-1">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <button class="btn btn-danger flex-grow-1">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
<script>
      // Generate placeholder images for demo
document.addEventListener('DOMContentLoaded', function() {
    // Create no-image placeholder
    createNoImagePlaceholder();

    // Create barcode and QR code
    createBarcode();
    createQRCode();

    // Create logo
    createLogo();
});

function createNoImagePlaceholder() {
    const canvas = document.createElement('canvas');
    canvas.width = 300;
    canvas.height = 300;
    const ctx = canvas.getContext('2d');

    // Draw circle
    ctx.beginPath();
    ctx.arc(150, 150, 120, 0, Math.PI * 2);
    ctx.strokeStyle = '#aaa';
    ctx.lineWidth = 10;
    ctx.stroke();

    // Draw camera icon
    ctx.beginPath();
    ctx.rect(100, 120, 100, 70);
    ctx.fillStyle = '#555';
    ctx.fill();

    // Draw lens
    ctx.beginPath();
    ctx.arc(150, 155, 25, 0, Math.PI * 2);
    ctx.fillStyle = '#777';
    ctx.fill();
    ctx.beginPath();
    ctx.arc(150, 155, 15, 0, Math.PI * 2);
    ctx.fillStyle = '#555';
    ctx.fill();

    // Draw flash
    ctx.beginPath();
    ctx.arc(180, 130, 5, 0, Math.PI * 2);
    ctx.fillStyle = '#fff';
    ctx.fill();

    // Draw diagonal line
    ctx.beginPath();
    ctx.moveTo(80, 80);
    ctx.lineTo(220, 220);
    ctx.strokeStyle = '#aaa';
    ctx.lineWidth = 10;
    ctx.stroke();

    const dataUrl = canvas.toDataURL();
    document.querySelectorAll('img[src="no-image.png"]').forEach(img => {
        img.src = dataUrl;
    });
}

function createBarcode() {
    const canvas = document.createElement('canvas');
    canvas.width = 200;
    canvas.height = 80;
    const ctx = canvas.getContext('2d');

    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Draw barcode lines
    ctx.fillStyle = '#000';
    for (let i = 0; i < 30; i++) {
        const x = 10 + i * 6;
        const height = 20 + Math.random() * 40;
        const width = 2 + Math.random() * 2;
        ctx.fillRect(x, 10, width, height);
    }

    const dataUrl = canvas.toDataURL();
    document.querySelectorAll('img[src="barcode.png"]').forEach(img => {
        img.src = dataUrl;
    });
}

function createQRCode() {
    const canvas = document.createElement('canvas');
    canvas.width = 80;
    canvas.height = 80;
    const ctx = canvas.getContext('2d');

    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Draw QR code pattern
    ctx.fillStyle = '#000';
    const blockSize = 8;

    // Corner squares
    ctx.fillRect(10, 10, 20, 20);
    ctx.fillRect(50, 10, 20, 20);
    ctx.fillRect(10, 50, 20, 20);

    // Inner white squares for corners
    ctx.fillStyle = '#fff';
    ctx.fillRect(15, 15, 10, 10);
    ctx.fillRect(55, 15, 10, 10);
    ctx.fillRect(15, 55, 10, 10);

    // Random QR code pattern
    ctx.fillStyle = '#000';
    for (let i = 0; i < 6; i++) {
        for (let j = 0; j < 6; j++) {
            if (Math.random() > 0.5) {
                ctx.fillRect(10 + i * blockSize, 10 + j * blockSize, blockSize, blockSize);
            }
        }
    }

    const dataUrl = canvas.toDataURL();
    document.querySelectorAll('img[src="qrcode.png"]').forEach(img => {
        img.src = dataUrl;
    });
}

function createLogo() {
    const canvas = document.createElement('canvas');
    canvas.width = 100;
    canvas.height = 100;
    const ctx = canvas.getContext('2d');

    // Draw logo on red background
    ctx.fillStyle = '#ff3b30';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Draw geometric shape (similar to Laravel logo)
    ctx.strokeStyle = '#fff';
    ctx.lineWidth = 3;

    // Draw cube-like shape
    ctx.beginPath();
    ctx.moveTo(30, 60);
    ctx.lineTo(50, 70);
    ctx.lineTo(70, 60);
    ctx.lineTo(50, 50);
    ctx.closePath();
    ctx.stroke();

    // Draw left extension
    ctx.beginPath();
    ctx.moveTo(30, 60);
    ctx.lineTo(30, 40);
    ctx.lineTo(50, 30);
    ctx.lineTo(50, 50);
    ctx.stroke();

    // Draw right extension
    ctx.beginPath();
    ctx.moveTo(50, 50);
    ctx.lineTo(70, 40);
    ctx.lineTo(70, 60);
    ctx.stroke();

    const dataUrl = canvas.toDataURL();
    document.querySelectorAll('img[src="logo.png"]').forEach(img => {
        img.src = dataUrl;
    });
}
</script>
@endpush
