@props(['title' => '', 'subtitle' => '', 'icon' => 'bi-grid'])
<section {{ $attributes->class(['record-panel']) }}>
    @if($title)
        <header class="record-panel-heading"><span class="record-panel-icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></span><div><h2>{{ $title }}</h2>@if($subtitle)<p>{{ $subtitle }}</p>@endif</div>{{ $actions ?? '' }}</header>
    @endif
    <div class="record-panel-body">{{ $slot }}</div>
</section>
