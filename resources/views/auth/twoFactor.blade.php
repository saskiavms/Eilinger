<x-layout.eilinger>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <x-heading.decorative class="text-center">
                {{ __('regLog.2FA') }}
            </x-heading.decorative>

            <form method="POST" action="{{ route('verify.store', app()->getLocale()) }}" novalidate>
                @csrf
                <p class="text-muted">
                    {{ __('regLog.2FANote') }}
                    {{ __('regLog.2FAResend') }} <a href="{{ route('verify.resend', app()->getLocale()) }}"></a>.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="flex flex-col md:col-span-2">
                        <div>
                            <label for="two_factor_code" class="block text-sm font-medium text-primary mb-1">
                                {{ __('regLog.2FA') }}
                            </label>
                            <input type="text" name="two_factor_code" id="two_factor_code"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                                required autofocus>
                        </div>
                        <div class="mt-1">
                            <x-input-error :messages="$errors->get('two_factor_code')" />
                        </div>
                    </div>

                    <div class="flex items-start pt-6">
                        <button type="submit"
                            class="w-full px-6 py-2 bg-primary text-white rounded-md hover:bg-danger-hover transition-colors">
                            {{ __('notify.verify_button') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-layout.eilinger>
