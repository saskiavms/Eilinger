<div class="py-6">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-ubuntu text-primary mb-6 font-semibold">
            {{ __('userOverview.welcome') }}
        </h2>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-xl font-ubuntu text-primary border-b pb-3 mb-6">
                {{ __('userOverview.overview') }}
            </h3>

            <!-- First Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Applications Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 flex flex-col">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h4 class="font-semibold text-primary">{{ __('userOverview.applications_header') }}</h4>
                    </div>
                    <div class="px-6 py-4 flex-grow">
                        <p class="text-gray-600">{{ __('userOverview.applications_body') }}</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50">
                        <a href="{{ route('user_antraege', app()->getLocale()) }}"
                            class="inline-block w-full px-4 py-2 bg-primary text-white text-center rounded-md hover:bg-primary-600 transition-colors">
                            {{ __('userOverview.applications_button') }}
                        </a>
                    </div>
                </div>

                <!-- Projects Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 flex flex-col">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h4 class="font-semibold text-primary">{{ __('userOverview.projects_header') }}</h4>
                    </div>
                    <div class="px-6 py-4 flex-grow">
                        <p class="text-gray-600">{{ __('userOverview.projects_body') }}</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50">
                        <a href="{{ route('user_gesuch', app()->getLocale()) }}"
                            class="inline-block w-full px-4 py-2 bg-primary text-white text-center rounded-md hover:bg-primary-600 transition-colors">
                            {{ __('userOverview.projects_button') }}
                        </a>
                    </div>
                </div>

                <!-- Messages Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 flex flex-col">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h4 class="font-semibold text-primary">{{ __('userOverview.message_header') }}</h4>
                    </div>
                    <div class="px-6 py-4 flex-grow">
                        <p class="text-gray-600">{{ __('userOverview.message_body') }}</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50">
                        <a href="{{ route('user_nachrichten', app()->getLocale()) }}"
                            class="inline-block w-full px-4 py-2 bg-primary text-white text-center rounded-md hover:bg-primary-600 transition-colors">
                            {{ __('userOverview.message_button') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 flex flex-col">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h4 class="font-semibold text-primary">{{ __('userOverview.profil_header') }}</h4>
                    </div>
                    <div class="px-6 py-4 flex-grow">
                        <p class="text-gray-600">{{ __('userOverview.profile_body') }}</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50">
                        <a href="{{ route('user_profile.edit', app()->getLocale()) }}"
                            class="inline-block w-full px-4 py-2 bg-primary text-white text-center rounded-md hover:bg-primary-600 transition-colors">
                            {{ __('userOverview.profile_button') }}
                        </a>
                    </div>
                </div>

                <!-- Files Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 flex flex-col">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h4 class="font-semibold text-primary">{{ __('userOverview.files_header') }}</h4>
                    </div>
                    <div class="px-6 py-4 flex-grow">
                        <p class="text-gray-600">{{ __('userOverview.files_body') }}</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50">
                        <a href="{{ route('user_dateien', app()->getLocale()) }}"
                            class="inline-block w-full px-4 py-2 bg-primary text-white text-center rounded-md hover:bg-primary-600 transition-colors">
                            {{ __('userOverview.files_button') }}
                        </a>
                    </div>
                </div>

                <!-- Logout Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 flex flex-col">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h4 class="font-semibold text-primary">{{ __('userOverview.logout_header') }}</h4>
                    </div>
                    <div class="px-6 py-4 flex-grow">
                        <p class="text-gray-600">{{ __('userOverview.logout_body') }}</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50">
                        <form method="POST" action="{{ route('logout', app()->getLocale()) }}">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2 bg-primary text-white text-center rounded-md hover:bg-primary-600 transition-colors">
                                {{ __('userOverview.logout_button') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
