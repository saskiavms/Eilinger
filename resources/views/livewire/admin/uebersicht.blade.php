<div>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-ubuntu text-primary font-semibold">Admin Dashboard</h1>
        <p class="mt-2 text-sm text-gray-700">Willkommen im Administrationsbereich der Eilinger Stiftung</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Benutzer</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-full">
                    <i class="bi bi-people text-xl text-primary"></i>
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                {{ $unverifiedUsers }} unverified
            </p>
            <div class="mt-4">
                <a href="{{ route('admin_users', app()->getLocale()) }}"
                    class="text-sm text-primary hover:text-primary-600 flex items-center">
                    <i class="bi bi-person-plus mr-2"></i>
                    Benutzer verwalten
                </a>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Anträge</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalApplications }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full">
                    <i class="bi bi-envelope-check text-xl text-blue-600"></i>
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                {{ $pendingApplications }} ausstehend
            </p>
            <div class="mt-4">
                <a href="{{ route('admin_applications', app()->getLocale()) }}"
                    class="text-sm text-primary hover:text-primary-600 flex items-center">
                    <i class="bi bi-envelope-check mr-2"></i>
                    Anträge prüfen
                </a>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Aktive Projekte</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeProjects }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-full">
                    <i class="bi bi-folder-check text-xl text-green-600"></i>
                </div>
            </div>
            <a href="{{ route('admin_projects', app()->getLocale()) }}"
                class="mt-2 inline-flex text-sm text-primary hover:text-primary-600">
                Alle Projekte ansehen
                <i class="bi bi-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-gray-600">Schnellzugriff</p>
                <div class="p-3 bg-purple-50 rounded-full">
                    <i class="bi bi-lightning text-xl text-purple-600"></i>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin_profile.edit', app()->getLocale()) }}"
                    class="text-sm text-primary hover:text-primary-600 flex items-center">
                    <i class="bi bi-person mr-2"></i>
                    Profile verwalten
                </a>
                <a href="{{ route('admin_settings', app()->getLocale()) }}"
                    class="text-sm text-primary hover:text-primary-600 flex items-center">
                    <i class="bi bi-gear mr-2"></i>
                    Einstellungen
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Applications -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="border-b border-gray-200 p-4">
                <h2 class="text-lg font-medium text-gray-900">Neueste Anträge</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentApplications as $application)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $application->name }}</p>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $application->appl_status_context === 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $application->appl_status_context === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $application->appl_status_context === 'danger' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $application->appl_status_context === 'info' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ __('application.status_name.' . $application->appl_status->name) }}
                                    </span>
                                </div>

                                @if ($application->user)
                                    <p class="text-sm text-gray-500">
                                        {{ $application->user->firstname }} {{ $application->user->lastname }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500">Benutzer nicht verfügbar</p>
                                @endif
                            </div>
                            <a href="{{ route('admin_antrag', ['application_id' => $application->id, 'locale' => app()->getLocale()]) }}"
                                class="text-sm text-primary hover:text-primary-600 flex items-center">
                                Details
                                <i class="bi bi-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        Keine neuen Anträge vorhanden
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="border-b border-gray-200 p-4">
                <h2 class="text-lg font-medium text-gray-900">Neue Benutzer</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentUsers as $user)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center">
                                        <span class="text-primary font-medium">
                                            {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $user->firstname }}
                                        {{ $user->lastname }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $user->email_verified_at ? 'Verifiziert' : 'Ausstehend' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        Keine neuen Benutzer vorhanden
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
