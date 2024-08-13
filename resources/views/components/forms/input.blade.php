<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    <label for="{{ $id }}" class="required form-label">{{ $label }}</label>
    <input type="{{ $type }}"
           class="form-control form-control-solid"
           name="{{ $name }}"
           id="{{ $id }}"
           placeholder="{{ $label }}"
    >
</div>