<header class="sticky top-0 w-full bg-primary z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            {{-- Logo --}}
            <h1 class="text-white">
                <a href="{{ route('index', app()->getLocale()) }}"
                    class="font-primary text-[28px] tracking-[2px] uppercase hover:text-accent transition-colors">
                    Eilinger Stiftung
                </a>
            </h1>

            <nav class="hidden md:flex items-center space-x-4">
                <ul class="flex items-center space-x-6 font-primary text-base">
                    <x-nav.item href="{{ route('index', app()->getLocale()) }}#hero" :active="request()->is(app()->getLocale())">
                        {{ __('home.home') }}
                    </x-nav.item>

                    <x-nav.item href="{{ route('index', app()->getLocale()) }}#about" :active="request()->url() === url(app()->getLocale()) . '#about'">
                        {{ __('home.about') }}
                    </x-nav.item>

                    <x-nav.item href="{{ route('index', app()->getLocale()) }}#our-values" :active="request()->url() === url(app()->getLocale()) . '#our-values'">
                        {{ __('home.funding') }}
                    </x-nav.item>

                    <x-nav.item href="{{ route('index', app()->getLocale()) }}#projekte" :active="request()->url() === url(app()->getLocale()) . '#projekte'">
                        {{ __('home.projects') }}
                    </x-nav.item>

                    <x-nav.item href="{{ route('index', app()->getLocale()) }}#gesuche" :active="request()->url() === url(app()->getLocale()) . '#gesuche'">
                        {{ __('home.requests') }}
                    </x-nav.item>

                    @auth
                        <li class="flex items-center space-x-4">
                            <a href="{{ route('user_dashboard', app()->getLocale()) }}"
                                class="inline-block py-2 px-4 text-white bg-accent hover:bg-accent-hover rounded-md transition-colors">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout', app()->getLocale()) }}" class="inline">
                                @csrf
                                <button type="submit" class="py-2 px-4 text-white hover:text-accent transition-colors">
                                    {{ __('home.logout') }}
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login', app()->getLocale()) }}"
                                class="inline-block py-2 px-4 text-white bg-accent hover:bg-accent-hover rounded-md transition-colors">
                                {{ __('home.register') }}
                            </a>
                        </li>
                    @endauth

                    {{-- Language Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center text-white hover:text-accent transition-colors">
                            {{ strtoupper(app()->getLocale()) }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-50">
                            @foreach (config('app.languages') as $langLocale => $langName)
                                <a href="{{ url()->current() }}?change_language={{ $langLocale }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ strtoupper($langLocale) }} ({{ $langName }})
                                </a>
                            @endforeach
                        </div>
                    </div>
                </ul>
            </nav>

            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white hover:text-accent">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2" class="md:hidden">
            <nav class="py-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('index', app()->getLocale()) }}#hero"
                            class="block py-2 text-white hover:text-accent transition-colors">
                            {{ __('home.home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('index', app()->getLocale()) }}#about"
                            class="block py-2 text-white hover:text-accent transition-colors">
                            {{ __('home.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('index', app()->getLocale()) }}#funding"
                            class="block py-2 text-white hover:text-accent transition-colors">
                            {{ __('home.funding') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('index', app()->getLocale()) }}#projects"
                            class="block py-2 text-white hover:text-accent transition-colors">
                            {{ __('home.projects') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('index', app()->getLocale()) }}#requests"
                            class="block py-2 text-white hover:text-accent transition-colors">
                            {{ __('home.requests') }}
                        </a>
                    </li>
                    @auth
                        <li class="flex items-center space-x-4">
                            <a href="{{ route('user_dashboard', app()->getLocale()) }}"
                                class="inline-block py-2 px-4 text-white bg-accent hover:bg-accent-hover rounded-md transition-colors">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout', app()->getLocale()) }}" class="inline">
                                @csrf
                                <button type="submit" class="py-2 px-4 text-white hover:text-accent transition-colors">
                                    {{ __('home.logout') }}
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login', app()->getLocale()) }}"
                                class="inline-block py-2 px-4 text-white bg-accent hover:bg-accent-hover rounded-md transition-colors">
                                {{ __('home.register') }}
                            </a>
                        </li>
                    @endauth

                    <li class="pt-4" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center w-full py-2 text-white hover:text-accent transition-colors">
                            <span>{{ __('home.lang') }}: {{ strtoupper(app()->getLocale()) }}</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="mt-2 py-2 bg-white rounded-md shadow-xl">
                            @foreach (config('app.languages') as $langLocale => $langName)
                                <a href="{{ url()->current() }}?change_language={{ $langLocale }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ strtoupper($langLocale) }} ({{ $langName }})
                                </a>
                            @endforeach
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

{{-- Add Alpine.js state for mobile menu --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbar', () => ({
            mobileMenuOpen: false
        }))
    })
</script>
