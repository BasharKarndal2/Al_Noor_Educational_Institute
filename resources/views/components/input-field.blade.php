@props(['nameinput', 'label', 'type' => 'text', 'value' => '','id'=>''])
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="col-md-6">
    <label for="{{ $nameinput }}" class="form-label">{{ $label }}</label>
    <input  required
        type="{{ $type }}" 
        class="form-control @error($nameinput) is-invalid @enderror" 
        id="{{ $id }}" 
        name="{{ $nameinput }}" 
        value="{{ old($nameinput, $value) }}" 
        {{ $attributes }}
        
    >
    @error($nameinput)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
