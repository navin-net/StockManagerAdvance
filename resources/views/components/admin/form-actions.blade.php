@props(['back', 'label' => 'Save changes', 'form' => null])
<div class="record-form-actions">
    <span class="record-hint"><i class="bi bi-asterisk" aria-hidden="true"></i> {{ __('Required fields are marked with an asterisk.') }}</span>
    <div><a href="{{ $back }}" class="btn record-button">{{ __('Cancel') }}</a><button type="submit" @if($form) form="{{ $form }}" @endif class="btn btn-primary"><i class="bi bi-check2" aria-hidden="true"></i> {{ __($label) }}</button></div>
</div>
