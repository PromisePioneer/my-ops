@props(['active'])

@php
    $classes = ($active ?? false)
    ? 'menu-link active'
    : 'menu-link'
@endphp

<div class="menu-item">
    <a {{ $attributes->merge(['class' => $classes]) }}>
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
            {{ $parentIcon  }}
            </span>
        </span>
        <span class="menu-title">
            {{ $menuTitle  }}
        </span>
    </a>
</div>
