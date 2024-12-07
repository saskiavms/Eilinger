@props(['text'])

<li {{ $attributes->merge(['class' => 'nav-item dropdown']) }}>
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $text }}
    </a>
    <ul class="dropdown-menu dropdown-menu-dark">
        {{ $slot }}
    </ul>
</li>
