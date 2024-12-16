<section>
    <header class="mb-6">
        <h3 class="text-2xl font-ubuntu text-primary font-semibold">
            {{ __('profile.update_password') }}
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('profile.password_security') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update', app()->getLocale()) }}" class="space-y-6">
        @csrf
        @method('put')

        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 mb-4">
                <div class="flex">
                    <div class="text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">
                {{ __('profile.current_password') }}
            </label>
            <input type="password" name="current_password" id="current_password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 @error('current_password') border-red-300 @enderror"
                autocomplete="current-password">
            @error('current_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
                {{ __('profile.new_password') }}
            </label>
            <input type="password" name="password" id="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 @error('password') border-red-300 @enderror"
                autocomplete="new-password">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">
                {{ __('profile.password_requirements') }}
            </p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                {{ __('profile.confirm_password') }}
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 @error('password_confirmation') border-red-300 @enderror"
                autocomplete="new-password">
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end gap-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-sm text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                {{ __('attributes.save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600">
                    {{ __('attributes.saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
