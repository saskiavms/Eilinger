<x-layout.admin_dashboard>
    <div class="py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-ubuntu text-primary font-semibold">
                {{ __('userDashboard.profile') }}
            </h2>

            <div class="mt-6 space-y-6">
                {{-- Profile Information Section --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Password Update Section --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.admin_dashboard>
