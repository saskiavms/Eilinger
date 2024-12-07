<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Eilinger Stiftung - @yield('title')</title>
    <link rel="canonical" href="https://www.eilingerstiftung.ch/@yield('link')" />
    <link rel="alternate" hreflang="de" href="https://www.eilingerstiftung.ch/de/@yield('link')" />
    <link rel="alternate" hreflang="en" href="https://www.eilingerstiftung.ch/en/@yield('link')" />
    <link rel="alternate" hreflang="x-default" href="https://www.eilingerstiftung.ch/@yield('link')" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/js/eilinger.js', 'resources/sass/eilinger.scss'])
    @livewireStyles

</head>

<body>
    <x-layout.navbar />

    <x-hero />

    {{ $slot }}

    @if (session()->has('success'))
        <div class="position-absolute top-0 start-100 translate-middle p-3 mb-2 bg-success text-white">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    <!-- ======= Footer ======= -->
    @include('components.layout.footer')

    @livewireScripts
</body>

</html>
