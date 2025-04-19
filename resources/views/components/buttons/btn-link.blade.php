<div>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn btn-light-' . $color . ' btn-sm']) }}>
        {{ $icon }}
        {{ $content }}
    </a>
</div>
