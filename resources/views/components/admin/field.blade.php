@props(['name', 'label', 'type' => 'text', 'value' => '', 'required' => false, 'hint' => '', 'wide' => false])
<div @class(['record-field', 'record-field-wide' => $wide])>
    <label for="{{ $name }}">{{ $label }} @if($required)<span class="record-required" aria-hidden="true">*</span>@endif</label>
    @if (trim((string) $slot) !== '')
        {{ $slot }}
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $type === 'password' ? '' : old($name, $value) }}" {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }} @required($required) @if($hint) aria-describedby="{{ $name }}-hint" @endif>
    @endif
    @if($hint)<small id="{{ $name }}-hint" class="record-hint">{{ $hint }}</small>@endif
    @error($name)<span class="record-error" role="alert">{{ $message }}</span>@enderror
</div>
