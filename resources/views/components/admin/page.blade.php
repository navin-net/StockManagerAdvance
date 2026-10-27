@props(['title', 'subtitle' => '', 'eyebrow' => 'Workspace', 'back' => null, 'backLabel' => 'Back to list'])
<div {{ $attributes->class(['record-page']) }}>
    <nav class="record-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid" aria-hidden="true"></i> {{ __('Dashboard') }}</a>
        <i class="bi bi-chevron-right" aria-hidden="true"></i>
        @if ($back)<a href="{{ $back }}">{{ __($eyebrow) }}</a><i class="bi bi-chevron-right" aria-hidden="true"></i>@endif
        <span aria-current="page">{{ $title }}</span>
    </nav>
    <header class="record-header">
        <div><div class="record-eyebrow">{{ __($eyebrow) }}</div><h1>{{ $title }}</h1>@if($subtitle)<p>{{ $subtitle }}</p>@endif</div>
        <div class="record-toolbar">
            @if ($back)<a href="{{ $back }}" class="btn record-button"><i class="bi bi-arrow-left" aria-hidden="true"></i><span>{{ __($backLabel) }}</span></a>@endif
            {{ $actions ?? '' }}
        </div>
    </header>
    <x-admin.form-feedback />
    {{ $slot }}
</div>
