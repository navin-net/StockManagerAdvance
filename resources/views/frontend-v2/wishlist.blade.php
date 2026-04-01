@extends('frontend-v2.app')

@section('title', 'Wishlist')

@section('content')
    <!-- ── PAGE HERO ── -->
    <div class="page-hero">
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    {{-- <li class="breadcrumb-item"><a href="#">Women</a></li> --}}
                    <li class="breadcrumb-item active">Wishlist</li>
                </ol>
            </nav>
            <div class="page-hero-eyebrow">Your Saved Pieces</div>
            <h1 class="page-hero-title">My <em>Wishlist</em></h1>
        </div>
    </div>
@endsection
