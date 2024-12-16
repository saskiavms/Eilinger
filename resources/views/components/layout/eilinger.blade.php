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
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire -->
    @livewireStyles

</head>

<body class="font-primary bg-bodyBg">
    <div class="min-h-screen flex flex-col" x-data="navbar">
        <x-layout.navbar />

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="fixed top-20 right-4 bg-success text-white px-6 py-3 rounded-lg shadow-lg z-50">
                <p>{{ session('success') }}</p>
            </div>
        @endif


        <x-hero />


        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        @include('components.layout.footer')
    </div>

    @livewireScripts
</body>

</html>
