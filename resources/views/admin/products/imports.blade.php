@extends('admin.layouts.master')
@section('title', __($pageTitle))
@section('content')
    <div class="container-fluid">
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
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div id="importAlert"></div>

                    <div class="card border-0 shadow-sm rounded-3" style="height: 300px">
                        <div class="card-body p-4">
                            <div class="row align-items-center mb-4">
                                <div class="col-md-6 mb-5">
                                    <h5 class="card-title mb-0 fw-semibold">
                                        Select an Excel file (.xlsx, .xls, .csv) to import your data
                                    </h5>
                                </div>
                                <div class="col-md-6 text-end mb-5">
                                    <button class="btn btn-primary" onclick="downloadFile()">
                                        <i class="bi bi-download me-2"></i>
                                        {{ __('messages.download_file_excel') }}
                                    </button>
                                </div>
                            </div>
<form action="{{ url('users/import') }}" method="POST" enctype="multipart/form-data">
    @csrf
                                    <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">
                                                {{ __('messages.upload_excel_file') }}
                                            </label>

                                            <input type="file" name="file" id="excelInput" class="d-none"
                                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                                onchange="onExcelChange(event)">

                                            <label for="excelInput" class="btn btn-outline-primary w-100">
                                                {{ __('messages.choose_file') }}
                                            </label>

                                            <small id="excelFileName" class="text-muted d-block mt-1">
                                                {{ __('messages.no_file_chosen') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-primary rounded-3"
                                            id="saveBtn">{{ __('messages.import_data') }}</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        function downloadFile() {
            const url = "/backend/file/products.csv";
            const a = document.createElement("a");
            a.href = url;
            a.download = "";
            document.body.appendChild(a);
            a.click();
            a.remove();
        }

        function onExcelChange(event) {
            const input = event.target;
            const fileNameDisplay = document.getElementById('excelFileName');

            if (input.files && input.files.length > 0) {
                fileNameDisplay.textContent = input.files[0].name;
            } else {
                fileNameDisplay.textContent = "{{ __('messages.no_file_chosen') }}";
            }
        }

    </script>

@endpush
