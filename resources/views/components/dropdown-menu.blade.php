@props([
    'active' => false,
    'parentIcon' => '',
    'menuTitle' => '',
    'menuItem' => ''
])

@php
    $classes = ($active ?? false)
        ? 'menu-item menu-accordion active hover show'
        : 'menu-item menu-accordion';
@endphp

<div data-kt-menu-trigger="click" {{ $attributes->merge(['class' => $classes]) }}>
    <span class="menu-link">
        <x-parent-menu-icon>
            {{ $parentIcon }}
        </x-parent-menu-icon>
        <span class="menu-title">{{ $menuTitle }}</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion menu-active-bg">
        {{ $menuItem }}
    </div>
</div>