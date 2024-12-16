<!-- Page Header -->
<div>
    <div class="mb-6">
        <h1 class="text-3xl font-ubuntu text-primary font-semibold">{{ __('application.appl_overview') }}</h1>
        <p class="mt-2 text-sm text-gray-700">{{ __('application.appl_overview_text') }}</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4">
            <div class="w-72">
                <label for="filterBereich"
                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('application.area') }}</label>
                <select wire:model.live="filterBereich" id="filterBereich"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="">{{ __('Alle Bereiche') }}</option>
                    <option value="bildung">{{ __('application.bereichs_name.Bildung') }}</option>
                    <option value="menschen in not">{{ __('application.bereichs_name.Menschen') }}</option>
                    <option value="menschenrecht">{{ __('application.bereichs_name.Menschenrecht') }}</option>
                    <option value="tierschutz">{{ __('application.bereichs_name.Tierschutz') }}</option>
                    <option value="umwelt">{{ __('application.bereichs_name.Umwelt') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('application.name') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('application.area') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('user.lastname') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('user.firstname') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('user.email') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($applications as $application)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin_antrag', ['application_id' => $application->id, 'locale' => app()->getLocale()]) }}"
                                        class="text-primary hover:text-primary-600">
                                        {{ $application->name }}
                                    </a>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $application->appl_status_context === 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $application->appl_status_context === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $application->appl_status_context === 'danger' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $application->appl_status_context === 'info' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ __('application.status_name.' . $application->appl_status->name) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ __('application.bereichs_name.' . $application->bereich->name) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ optional($application->user)->lastname }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ optional($application->user)->firstname }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ optional($application->user)->email }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ __('application.no_applications') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white border-t border-gray-200 px-4 py-3 sm:px-6">
            {{ $applications->links() }}
        </div>
    </div>
</div>
