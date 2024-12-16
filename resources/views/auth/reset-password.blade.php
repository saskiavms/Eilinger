<x-layout.eilinger>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <x-heading.decorative class="text-center">
                {{ __('regLog.resetPassword') }}
            </x-heading.decorative>

            <form method="POST" action="{{ route('password.store', app()->getLocale()) }}" novalidate>
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Email -->
                    <div class="flex flex-col">
                        <div>
                            <label for="email" class="block text-sm font-medium text-primary mb-1">
                                {{ __('Email') }}
                            </label>
                            <input type="email" name="email" id="email"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                                value="{{ old('email', $request->email) }}">
                        </div>
                        <div class="mt-1">
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col">
                        <div>
                            <label for="password" class="block text-sm font-medium text-primary mb-1">
                                {{ __('Password') }}
                            </label>
                            <input type="password" name="password" id="password"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                        </div>
                        <div class="mt-1">
                            <x-input-error :messages="$errors->get('password')" />
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="flex flex-col">
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-primary mb-1">
                                {{ __('Confirm Password') }}
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                        </div>
                        <div class="mt-1">
                            <x-input-error :messages="$errors->get('password_confirmation')" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-md hover:bg-danger-hover transition-colors">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-layout.eilinger>
