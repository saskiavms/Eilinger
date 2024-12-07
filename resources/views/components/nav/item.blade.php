@props(['href', 'active' => false])

<li>
    <a href="{{ $href }}"
        {{ $attributes->merge([
            'class' =>
                'nav-link' . ($active ? ' active' : '') . ($attributes->get('class') ? ' ' . $attributes->get('class') : ''),
        ]) }}>
        {{ $slot }}
    </a>
</li>
