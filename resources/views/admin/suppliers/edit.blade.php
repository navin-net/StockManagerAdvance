@extends('admin.layouts.master')
@section('title', __('messages.edit_supplier'))
@section('content')
<x-admin.page :title="__('messages.edit_supplier')" :back="route('suppliers.index')">
    <x-admin.panel :title="__('messages.supplier')" icon="bi-truck">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                @foreach(['name', 'email', 'phone', 'address', 'city', 'street', 'number_of_houses'] as $field)
                    <div class="col-md-6">
                        <label class="form-label" for="{{ $field }}">{{ __('messages.' . $field) }}</label>
                        <input class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}"
                            type="{{ $field === 'email' ? 'email' : 'text' }}" value="{{ old($field, $supplier->$field) }}"
                            maxlength="{{ $field === 'phone' ? 20 : ($field === 'number_of_houses' ? 50 : 255) }}"
                            @required(in_array($field, ['name', 'email', 'phone', 'address']))>
                        @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                @endforeach
                <div class="col-md-6">
                    <label class="form-label" for="logo">{{ __('messages.logo') }}</label>
                    <input type="file" class="form-control" name="logo" id="logo" accept="image/*">
                    @error('logo')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="note">{{ __('messages.note') }}</label>
                    <textarea class="form-control" id="note" name="note" maxlength="5000">{{ old('note', $supplier->note) }}</textarea>
                    @error('note')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">{{ __('messages.save') }}</button>
                    <a class="btn btn-secondary" href="{{ route('suppliers.index') }}">{{ __('messages.cancel') }}</a>
                </div>
            </div>
        </form>
    </x-admin.panel>
</x-admin.page>
@endsection
