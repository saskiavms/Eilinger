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
