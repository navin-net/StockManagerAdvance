@extends('admin.layouts.master')

@section('title', __('Portfolio Management'))

@section('content')
@push('style')
<style>
        .upload-area {
            border: 2px dashed #dee2e6;
            padding: 2rem;
            text-align: center;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .upload-area:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
</style>
@endpush
    <div class="container">
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
        <div class="card">
            <div class="card-body">
                <form action="{{ route('portfolio.update', $portfolio->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Project Title</label>
                            <input type="text" class="form-control form-control-lg fs-6" name="full_name" id="full_name" value="{{ $portfolio->full_name}}" placeholder="e.g. AI Dashboard">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold ">Category</label>
                            <select class="form-select form-control-lg fs-6">
                                <option selected>Web Development</option>
                                <option value="1">Mobile App</option>
                                <option value="2">UI/UX Design</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold ">Detailed Description</label>
                            <textarea class="form-control" rows="4" placeholder="Describe the challenges and solutions..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Project Thumbnail</label>
                            <div class="upload-area">
                                <p class="text-muted mb-0">Drag and drop your image here, or <span class="text-primary fw-medium">browse</span></p>
                                <input type="file" class="d-none" id="fileInput">
                            </div>
                        </div>


                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-light text-secondary px-4">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Save Project</button>
                    </div>

                </form>


            </div>
        </div>


    </div>



@endsection
