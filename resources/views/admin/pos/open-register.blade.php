@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-6"> <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h5 class="card-title mb-1 fw-bold">{{ __('messages.open_register') }}</h5>
                            <p class="text-muted small">Enter the starting cash amount in your drawer.</p>
                        </div>

                        <form method="POST" action="{{ route('pos.open-register.store') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="cash_in_hand" class="form-label fw-semibold">
                                    {{ __('messages.cash_in_hand') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           name="cash_in_hand"
                                           id="cash_in_hand"
                                           class="form-control border-start-0"
                                           required>
                                </div>

                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                    <i class="bi bi-door-open me-2"></i>{{ __('messages.open_register') }}
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
