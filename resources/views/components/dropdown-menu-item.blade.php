@props(['active'])

@php
    $classes = ($active ?? false)
               ? 'menu-link active'
               : 'menu-link'
@endphp

<div class="menu-item">
    <a {{ $attributes->merge(['class' => $classes]) }}>
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
        <span class="menu-title">{{ $slot }}</span>
    </a>
</div>
