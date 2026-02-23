@extends('admin.layouts.master')

@section('title', __('messages.add_payments'))

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <div class="pagetitle">
                <h1 class="h3 fw-bold mb-2">{{ $pageTitle . ' - ' . $sale->reference}} </h1>
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
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">{{ __('messages.add_new_sale') }}</h5>
                        <div id="alertsContainer" class="mb-4"></div>
                        {{-- <div id="alertsContainer" class="alert alert-danger d-none"></div> --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('sales.storePayment') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                            <input type="hidden" name="status" value="paid">

                            <div class="mb-3">
                                <label>Amount *</label>
                                <input type="number" name="amount" value="{{ $balance }}" step="0.01" min="0.01" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Payment Method *</label>
                                <select name="method" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="bank">Bank Transfer</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Paid At *</label>
                                <input type="datetime-local" name="paid_at" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Reference</label>
                                <input type="text" name="reference" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Attachment</label>
                                <input type="file" name="attachment" class="form-control">
                            </div>

                            <button class="btn btn-primary" id="btnSubmitPayment">
                                Confirm Payment
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {

            $('#paymentForm').on('submit', function (e) {
                e.preventDefault();

                const form = this;
                const btn = $('#btnSubmitPayment');
                const alert = $('#alertsContainer');

                alert.addClass('d-none').text('');
                btn.prop('disabled', true)
                    .html('<i class="bi bi-hourglass-split"></i> Processing...');

                let formData = new FormData(form);

                $.ajax({
                    url: '{{ route('sales.storePayment') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function (res) {
                        if (res.redirect) {
                            window.location.href = res.redirect;
                        }
                    },

                    error: function (xhr) {
                        btn.prop('disabled', false)
                            .html('Confirm Payment');

                        let message = 'Something went wrong. Please try again.';

                        if (xhr.status === 422) {
                            message = 'Please fix the highlighted fields.';
                        } else if (xhr.status === 419) {
                            message = 'Session expired. Refresh and try again.';
                        } else if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }

                        alert.removeClass('d-none').text(message);
                    }
                });
            });

        });
    </script>
@endpush
