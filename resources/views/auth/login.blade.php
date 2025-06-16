<x-layout.eilinger>
    @section('title', 'Registrieren/Einloggen')

    <section class="py-16">
        <div class="container mx-auto px-4">
            <x-heading.decorative class="text-center">
                {{ __('regLog.loginTitle') }}
            </x-heading.decorative>

            <p class="text-primary mb-6">{{ __('regLog.loginSubTitle') }}</p>

            <div class="mb-8">
                <h3 class="font-ubuntu text-xl font-bold text-primary mb-2">{{ __('regLog.inputTitle') }}</h3>
                <p class="text-primary">{{ __('regLog.inputNotes') }}</p>
            </div>

            <div class="mb-8">
                <h3 class="font-ubuntu text-xl font-bold text-primary mb-4">{{ __('regLog.newAccount') }}</h3>
                <p class="text-primary">
                    {{ __('regLog.newAccountText1') }}
                    <a href="{{ route('registerPrivat', app()->getLocale()) }}"
                        class="text-primary hover:text-accent-hover font-bold">
                        {{ __('regLog.privat') }}
                    </a>
                    {{ __('regLog.newAccountText2') }}
                    <a href="{{ route('registerInst', app()->getLocale()) }}"
                        class="text-primary hover:text-accent-hover font-bold">
                        {{ __('regLog.org') }}
                    </a>
                </p>
            </div>

            <div class="mb-8">
                <h3 class="font-ubuntu text-xl font-bold text-primary mb-4">{{ __('regLog.alreadyRegistered') }}</h3>
                
                {{-- Email Verification Warning --}}
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-yellow-700 mb-1">{{ __('regLog.emailVerificationRequired') }}</h3>
                            <p class="text-sm text-yellow-700">{{ __('regLog.emailVerificationInfo') }}</p>
                        </div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('login', app()->getLocale()) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex flex-col">
                            <div>
                                <label for="email" class="block text-sm font-medium text-primary mb-1">
                                    {{ __('user.email') }}
                                </label>
                                <input type="email" name="email" id="email"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                                    placeholder="name@example.com">
                            </div>
                            <div class="mt-1">
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                            <div class="mt-4 flex items-center">
                                <input type="checkbox" name="remember" id="remember"
                                    class="rounded border-gray-300 text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                <label for="remember" class="ml-2 text-sm text-primary">
                                    {{ __('regLog.rememberMe') }}
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <div>
                                <label for="password" class="block text-sm font-medium text-primary mb-1">
                                    {{ __('user.password') }}
                                </label>
                                <input type="password" name="password" id="password"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                            </div>
                            <div class="mt-1">
                                <x-input-error :messages="$errors->get('password')" />
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('password.request', app()->getLocale()) }}"
                                    class="text-sm text-primary hover:text-accent-hover">
                                    {{ __('regLog.resetPassword') }}
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start pt-6">
                            <button type="submit"
                                class="w-full px-6 py-2 bg-primary text-white rounded-md hover:bg-danger-hover transition-colors">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <p class="text-primary">{{ __('regLog.loginNoteOrg') }}</p>
        </div>
    </section>
</x-layout.eilinger>
