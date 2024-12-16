<section>
    <header class="mb-6">
        <h3 class="text-2xl font-ubuntu text-primary font-semibold">
            {{ __('profile.information') }}
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('profile.update_info') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send', app()->getLocale()) }}">
        @csrf
    </form>

    <form method="post" action="{{ route('user_profile.update', app()->getLocale()) }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
                {{ __('profile.lastname') }}
            </label>
            <input type="text" name="lastname" id="lastname" value="{{ old('lastname', $user->lastname) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                required autofocus>
            @error('lastname')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
                {{ __('profile.firstname') }}
            </label>
            <input type="text" name="firstname" id="firstname" value="{{ old('firstname', $user->firstname) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                required autofocus>
            @error('firstname')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                {{ __('profile.email') }}
            </label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                required>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('profile.email_unverified') }}

                        <button form="send-verification"
                            class="text-sm text-primary hover:text-primary-600 underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            {{ __('profile.resend_verification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 font-medium">
                            {{ __('profile.verification_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end gap-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-sm text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                {{ __('attributes.save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600">
                    {{ __('attributes.saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
