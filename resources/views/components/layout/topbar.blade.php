<nav class="bg-white shadow-md">
    <div class="px-4 mx-auto">
        <div class="flex justify-between h-16">
            <!-- Left side -->
            <div class="flex items-center">
                <!-- Toggle Sidebar Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-primary hover:text-accent">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Logo -->
                <div class="ml-4">
                    <img src="{{ url('/images/logo_white.png') }}" alt="Logo" class="h-2">
                </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-primary hover:text-accent">
                        {{ strtoupper(app()->getLocale()) }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-50">
                        @foreach (config('app.languages') as $langLocale => $langName)
                            <a href="{{ url()->current() }}?change_language={{ $langLocale }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ strtoupper($langLocale) }} ({{ $langName }})
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Notifications -->
                <livewire:message-notification />

                <!-- Home Link -->
                <a href="{{ route('index', app()->getLocale()) }}" class="text-primary hover:text-accent">
                    Home
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout', app()->getLocale()) }}">
                    @csrf
                    <button type="submit" class="text-primary hover:text-accent">
                        Logout
                    </button>
                </form>

                <!-- User Name -->
                <span class="text-primary">
                    {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                </span>
            </div>
        </div>
    </div>
</nav>
