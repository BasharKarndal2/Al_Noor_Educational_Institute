@props(['label', 'id', 'name', 'required' => false])

<div class="col-md-6">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <select 
        class="form-select @error($name) is-invalid @enderror" 
        id="{{ $id }}" 
        name="{{ $name }}"
        @if($required) required @endif
    >
        <option value="">اختر...</option>

    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
