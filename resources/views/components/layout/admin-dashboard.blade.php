<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <div x-data="{ sidebarOpen: true }" class="relative">
            <!-- Sidebar -->
            <div :class="{ 'w-64': sidebarOpen, 'w-16': !sidebarOpen }"
                class="fixed top-0 left-0 z-30 h-screen transition-all duration-300 transform bg-primary">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center justify-center h-16 bg-primary-700">
                        <!-- Full Logo when expanded -->
                        <span x-show="sidebarOpen" class="text-white text-xl font-bold">
                            Eilinger Stiftung
                        </span>
                        <!-- Small Logo when collapsed -->
                        <img x-show="!sidebarOpen" src="{{ url('/images/logo_white.png') }}" alt="Logo"
                            class="h-8 w-8">
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 py-6 space-y-2">
                        <a href="{{ route('admin_dashboard', app()->getLocale()) }}"
                            class="flex items-center text-white hover:bg-primary-600"
                            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
                            :title="!sidebarOpen ? 'Dashboard' : ''">
                            <i class="bi bi-grid text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
                            <span x-show="sidebarOpen">Dashboard</span>
                        </a>

                        <a href="{{ route('admin_users', app()->getLocale()) }}"
                            class="flex items-center text-white hover:bg-primary-600"
                            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
                            :title="!sidebarOpen ? 'Benutzerübersicht' : ''">
                            <i class="bi bi-people text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
                            <span x-show="sidebarOpen">Benutzerübersicht</span>
                        </a>

                        <a href="{{ route('admin_applications', app()->getLocale()) }}"
                            class="flex items-center text-white hover:bg-primary-600"
                            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
                            :title="!sidebarOpen ? 'Antragsübersicht' : ''">
                            <i class="bi bi-envelope-check text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
                            <span x-show="sidebarOpen">Antragsübersicht</span>
                        </a>

                        <a href="{{ route('admin_projects', app()->getLocale()) }}"
                            class="flex items-center text-white hover:bg-primary-600"
                            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
                            :title="!sidebarOpen ? 'Projektübersicht' : ''">
                            <i class="bi bi-folder text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
                            <span x-show="sidebarOpen">Projektübersicht</span>
                        </a>

                        <a href="{{ route('admin_profile.edit', app()->getLocale()) }}"
                            class="flex items-center text-white hover:bg-primary-600"
                            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
                            :title="!sidebarOpen ? 'Profil' : ''">
                            <i class="bi bi-person text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
                            <span x-show="sidebarOpen">Profil</span>
                        </a>

                        <a href="{{ route('admin_settings', app()->getLocale()) }}"
                            class="flex items-center text-white hover:bg-primary-600"
                            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
                            :title="!sidebarOpen ? 'Einstellungen' : ''">
                            <i class="bi bi-gear text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
                            <span x-show="sidebarOpen">Einstellungen</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div :class="{ 'ml-64': sidebarOpen, 'ml-16': !sidebarOpen }" class="transition-all duration-300">
                <!-- Topbar -->
                <x-layout.topbar :sidebarOpen="true" />

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
