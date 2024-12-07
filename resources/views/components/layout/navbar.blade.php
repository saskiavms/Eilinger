<header id="header" class="fixed top-0 w-full bg-[#37517e] py-4">
    <div class="container mx-auto">
        <div class="flex justify-between items-center">
            {{-- Logo --}}
            <h1 class="logo">
                <a href="{{ route('index', app()->getLocale()) }}">Eilinger Stiftung</a>
            </h1>

            <nav id="navbar" class="navbar">
                <ul class="flex items-center">
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
                        <x-nav.item href="{{ route('user_dashboard', app()->getLocale()) }}" class="getstarted">
                            Dashboard
                        </x-nav.item>
                    @else
                        <x-nav.item href="{{ route('login', app()->getLocale()) }}" class="getstarted">
                            {{ __('home.register') }}
                        </x-nav.item>
                    @endauth

                    {{-- Language Dropdown --}}
                    <x-nav.dropdown :text="strtoupper(app()->getLocale())">
                        @foreach (config('app.languages') as $langLocale => $langName)
                            <x-nav.dropdown-item href="{{ url()->current() }}?change_language={{ $langLocale }}">
                                {{ strtoupper($langLocale) }} ({{ $langName }})
                            </x-nav.dropdown-item>
                        @endforeach
                    </x-nav.dropdown>
                </ul>

                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </div>
</header>
