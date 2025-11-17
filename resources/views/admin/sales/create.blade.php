@extends('layouts.master')

@section('title', __('messages.create_sale'))

@section('content')
<div class="container-fluid">
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
    


    
</div>
@endsection
@push('scripts')
    <script>
        const USER_ID = {{ auth()->id() }}; 
        const STORAGE_KEY = `items_user_${USER_ID}`;
        console.log(USER_ID,'_',STORAGE_KEY);
    </script>
@endpush
