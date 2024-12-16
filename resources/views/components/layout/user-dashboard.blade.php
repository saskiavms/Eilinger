<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eilinger Stiftung - Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <div x-data="{ sidebarOpen: true }" class="relative">
            <!-- Sidebar -->
            <div :class="{ 'w-64': sidebarOpen, 'w-16': !sidebarOpen }"
                class="fixed top-0 left-0 z-30 h-screen transition-all duration-300 transform bg-primary">
                @include('components.layout.sidebar')
            </div>

            <!-- Main Content -->
            <div :class="{ 'ml-64': sidebarOpen, 'ml-16': !sidebarOpen }" class="transition-all duration-300">
                <!-- Topbar -->
                @include('components.layout.topbar')

                <!-- Main Content Area -->
                <main class="p-8">
                    <div class="container mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
