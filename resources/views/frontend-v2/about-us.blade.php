@extends('frontend-v2.app')

@section('title', 'About us')

@section('content')
    <!-- ── PAGE HERO ── -->
    <div class="page-hero">
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    {{-- <li class="breadcrumb-item"><a href="#">Women</a></li> --}}
                    <li class="breadcrumb-item active">About us</li>
                </ol>
            </nav>
            <div class="page-hero-eyebrow">Curated Collection</div>
            <h1 class="page-hero-title">About <em>us</em></h1>
        </div>
    </div>
@endsection
