@extends('layouts.master')
@section('title', __('messages.dashboard'))

@section('content')

    <div id="importAlert"></div>

    <form id="importForm" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Import</button>
    </form>



@endsection
@push('scripts')
    <script>
        document.getElementById('importForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const alertBox = document.getElementById('importAlert');
            fetch('/users/import', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData})
                .then(res => res.json()).then(data => {if (!data.success) return;
                    let messages = data.messages.map(msg => `<li>${msg}</li>`).join('');
                    alertBox.innerHTML = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Import Result</strong>
                    <ul class="mb-0 mt-2">
                        ${messages}
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;}).catch(err => {
                    alertBox.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Something went wrong.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            });
        });
    </script>

@endpush
