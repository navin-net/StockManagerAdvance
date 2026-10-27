@extends('admin.layouts.master')
@section('title', $pageTitle)
@section('content')
<x-admin.page :title="$pageTitle" :back="route('products.index')" eyebrow="Products">
    <x-admin.panel :title="__('messages.add_adjustment')" icon="bi-box-seam">
        <form method="POST" action="{{ route('products.adjustment.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="product_id" class="form-label">{{ __('messages.product') }}</label>
                    <select id="product_id" name="product_id" class="form-select" required>
                        <option value="">{{ __('messages.select_product') }}</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }} ({{ $product->code }}) — {{ $product->stock_quantity }} {{ __('messages.in_stock') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">{{ __('messages.adjustment_type') }}</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="increase" @selected(old('type') === 'increase')>{{ __('messages.increase_stock') }}</option>
                        <option value="decrease" @selected(old('type') === 'decrease')>{{ __('messages.decrease_stock') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="quantity" class="form-label">{{ __('messages.quantity') }}</label>
                    <input id="quantity" name="quantity" class="form-control" type="number" min="1" max="1000000" step="1" value="{{ old('quantity') }}" required>
                </div>
                <div class="col-12">
                    <label for="reason" class="form-label">{{ __('messages.reason') }}</label>
                    <textarea id="reason" name="reason" class="form-control" maxlength="1000" required>{{ old('reason') }}</textarea>
                </div>
                <div class="col-12"><button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button></div>
            </div>
        </form>
    </x-admin.panel>
    <x-admin.panel :title="__('messages.adjustment_history')" icon="bi-clock-history">
        <div class="table-responsive">
            <table class="table dt-static-table">
                <thead><tr>
                    @foreach(['date', 'product', 'quantity_before', 'quantity_change', 'quantity_after', 'reason'] as $label)<th>{{ __('messages.' . $label) }}</th>@endforeach
                </tr></thead>
                <tbody>
                    @forelse($adjustments as $adjustment)
                        <tr><td>{{ $adjustment->created_at }}</td><td>{{ $adjustment->product_name }} ({{ $adjustment->product_code }})</td><td>{{ $adjustment->quantity_before }}</td><td>{{ $adjustment->quantity_change > 0 ? '+' : '' }}{{ $adjustment->quantity_change }}</td><td>{{ $adjustment->quantity_after }}</td><td>{{ $adjustment->reason }}</td></tr>
                    @empty
                        <tr><td colspan="6">{{ __('messages.no_data_available') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $adjustments->links() }}
    </x-admin.panel>
</x-admin.page>
@endsection
