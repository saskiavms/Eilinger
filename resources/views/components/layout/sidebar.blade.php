<!-- ========== Left Sidebar Start ========== -->
<div class="flex flex-col h-full">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-primary-700">
        <!-- Full Logo when expanded -->
        <span x-show="sidebarOpen" class="text-white text-xl font-bold">
            Eilinger Stiftung
        </span>
        <!-- Small Logo when collapsed -->
        <img x-show="!sidebarOpen" src="{{ url('/images/logo_white.png') }}" alt="Logo" class="h-8 w-8">
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-6 space-y-2">
        <a href="{{ route('user_dashboard', app()->getLocale()) }}"
            class="flex items-center text-white hover:bg-primary-600"
            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
            :title="!sidebarOpen ? '{{ __('userDashboard.dashboard') }}' : ''">
            <i class="bi bi-grid text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="sidebarOpen">{{ __('userDashboard.dashboard') }}</span>
        </a>

        <a href="{{ route('user_antraege', app()->getLocale()) }}"
            class="flex items-center text-white hover:bg-primary-600"
            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
            :title="!sidebarOpen ? '{{ __('userDashboard.applications') }}' : ''">
            <i class="bi bi-envelope-open text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="sidebarOpen">{{ __('userDashboard.applications') }}</span>
        </a>

        <a href="{{ route('user_gesuch', app()->getLocale()) }}"
            class="flex items-center text-white hover:bg-primary-600"
            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
            :title="!sidebarOpen ? '{{ __('userDashboard.requests') }}' : ''">
            <i class="bi bi-envelope-check text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="sidebarOpen">{{ __('userDashboard.requests') }}</span>
        </a>

        <a href="{{ route('user_nachrichten', app()->getLocale()) }}"
            class="flex items-center text-white hover:bg-primary-600"
            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
            :title="!sidebarOpen ? '{{ __('userDashboard.messages') }}' : ''">
            <i class="bi bi-chat-dots text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="sidebarOpen">{{ __('userDashboard.messages') }}</span>
        </a>

        <a href="{{ route('user_dateien', app()->getLocale()) }}"
            class="flex items-center text-white hover:bg-primary-600"
            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
            :title="!sidebarOpen ? '{{ __('userDashboard.files') }}' : ''">
            <i class="bi bi-folder-plus text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="sidebarOpen">{{ __('userDashboard.files') }}</span>
        </a>

        <a href="{{ route('user_profile.edit', app()->getLocale()) }}"
            class="flex items-center text-white hover:bg-primary-600"
            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
            :title="!sidebarOpen ? '{{ __('userDashboard.profile') }}' : ''">
            <i class="bi bi-person text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="sidebarOpen">{{ __('userDashboard.profile') }}</span>
        </a>

        <a href="{{ route('user_delete', app()->getLocale()) }}"
            class="flex items-center text-white hover:bg-primary-600"
            :class="sidebarOpen ? 'px-6' : 'justify-center px-3'"
            :title="!sidebarOpen ? '{{ __('userDashboard.delAccount') }}' : ''">
            <i class="bi bi-person-x text-xl" :class="sidebarOpen ? 'mr-3' : ''"></i>
            <span x-show="sidebarOpen">{{ __('userDashboard.delAccount') }}</span>
        </a>
    </nav>
</div>
<!-- Left Sidebar End -->
