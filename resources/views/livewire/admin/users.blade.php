<div>
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-ubuntu text-primary font-semibold">Benutzerübersicht</h1>
        <p class="mt-2 text-sm text-gray-700">Verwalten Sie alle registrierten Benutzer und deren Anträge</p>
    </div>

    <!-- Search Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 space-y-4 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
            <div class="flex-1">
                <label for="searchUsername" class="block text-sm font-medium text-gray-700 mb-1">Benutzername</label>
                <input wire:model.live="searchUsername" type="text" id="searchUsername"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                    placeholder="Suchen nach Benutzernamen">
            </div>
            <div class="flex-1">
                <label for="searchUserEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input wire:model.live="searchUserEmail" type="text" id="searchUserEmail"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                    placeholder="Suchen nach Email">
            </div>
            <div class="flex-1">
                <label for="searchNameInst" class="block text-sm font-medium text-gray-700 mb-1">Vereinsname</label>
                <input wire:model.live="searchNameInst" type="text" id="searchNameInst"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                    placeholder="Suchen nach Vereinsname">
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Benutzername
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Vereinsname
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Anträge
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $user->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->name_inst }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->firstname }} {{ $user->lastname }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                                @unless ($user->email_verified_at)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                        Unverified
                                    </span>
                                @endunless
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($user->sendApplications->count() > 0)
                                    <div class="space-y-2">
                                        @foreach ($user->sendApplications as $application)
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin_antrag', ['application_id' => $application->id, 'locale' => app()->getLocale()]) }}"
                                                    class="text-primary hover:text-primary-600">
                                                    {{ $application->name }}
                                                </a>
                                                <span class="text-xs text-gray-500">
                                                    ({{ __('application.bereichs_name.' . $application->bereich->name) }})
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">Keine Anträge</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($user->sendApplications->count() > 0)
                                    <div class="space-y-2">
                                        @foreach ($user->sendApplications as $application)
                                            <div>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $application->appl_status_context === 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $application->appl_status_context === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $application->appl_status_context === 'danger' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $application->appl_status_context === 'info' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                    {{ __('application.status_name.' . $application->appl_status->name) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Keine Benutzer gefunden
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white border-t border-gray-200 px-4 py-3 sm:px-6">
            {{ $users->links() }}
        </div>
    </div>
</div>
