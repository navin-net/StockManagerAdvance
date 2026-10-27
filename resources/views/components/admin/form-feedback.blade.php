@if(session('success'))<div class="record-feedback record-feedback-success" role="status"><i class="bi bi-check-circle" aria-hidden="true"></i><span>{{ session('success') }}</span></div>@endif
@if(session('error'))<div class="record-feedback record-feedback-error" role="alert">{{ session('error') }}</div>@endif
@if($errors->any())
<div class="record-feedback record-feedback-error" role="alert" tabindex="-1" data-form-errors><div><strong>{{ __('Please check the following fields.') }}</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
@endif
