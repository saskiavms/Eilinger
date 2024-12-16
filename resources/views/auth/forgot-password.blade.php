<x-layout.eilinger>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <x-heading.decorative class="text-center">
                {{ __('regLog.forgottenPassword') }}
            </x-heading.decorative>

            <p class="text-primary mb-6">{{ __('regLog.forgottenPasswordNote') }}</p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email', app()->getLocale()) }}" novalidate>
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col md:col-span-2">
                        <div>
                            <label for="email" class="block text-sm font-medium text-primary mb-1">
                                {{ __('Email') }}
                            </label>
                            <input type="email" name="email" id="email"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                                :value="old('email')" required autofocus>
                        </div>
                        <div class="mt-1">
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>

                    <div class="flex items-start pt-6">
                        <button type="submit"
                            class="w-full px-6 py-2 bg-primary text-white rounded-md hover:bg-danger-hover transition-colors">
                            {{ __('regLog.resetPasswordLink') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-layout.eilinger>
