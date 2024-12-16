@props(['active' => false])

@php
    $classes = $active ?? false ? 'text-accent' : 'text-white hover:text-accent transition-colors';
@endphp

<li>
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>
