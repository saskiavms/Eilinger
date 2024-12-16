<main id="main">

    <section class="py-16">
        <div class="container mx-auto px-4">
            <x-heading.decorative class="text-center">
                {{ __('regLog.regPrivat') }}
            </x-heading.decorative>

            <form wire:submit="registerPrivat">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Username -->
                    <div class="flex flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="username">
                            {{ __('user.username') }} *
                        </label>
                        <input wire:model.blur="username" type="text"
                            class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                            {{ $errors->has('username') ? 'border-red-500' : (session('valid-username') ? 'border-green-500' : 'border-gray-300') }}"
                            id="username" placeholder="Wählen Sie einen Benutzernamen" autofocus autocomplete="off">
                        @error('username')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                        @if (session()->has('valid-username'))
                            <span class="text-green-500 text-sm mt-1">{{ session('valid-username') }}</span>
                        @endif
                    </div>

                    <!-- Salutation -->
                    <div class="flex
                            flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="salutation">
                            {{ __('user.salutation') }} *
                        </label>
                        <select wire:model.blur="salutation"
                            class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                            {{ $errors->has('salutation') ? 'border-red-500' : (session('valid-salutation') ? 'border-green-500' : 'border-gray-300') }}"
                            id="salutation">
                            <option value="">{{ __('attributes.please_select') }}</option>
                            @foreach (App\Enums\Salutation::cases() as $salutation)
                                <option value="{{ $salutation->value }}">
                                    {{ __('user.salutation_name.' . $salutation->name) }}</option>
                            @endforeach
                        </select>
                        @error('salutation')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div class="flex
                            flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="firstname">
                            {{ __('user.firstname') }} *
                        </label>
                        <input wire:model.blur="firstname" type="text"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 @error('firstname') border-red-500 @endif @if (session('valid-firstname')) border-green-500 @endif"
                            id="firstname" placeholder="Max">
                        @error('firstname')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="flex
                            flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="lastname">
                            {{ __('user.lastname') }} *
                        </label>
                        <input wire:model.blur="lastname" type="text"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 @error('lastname') border-red-500 @endif @if (session('valid-lastname')) border-green-500 @endif"
                            id="lastname" placeholder="Muster">
                        @error('lastname')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Street -->
                    <div class="flex
                            flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="street">
                            {{ __('address.street') }} *
                        </label>
                        <input wire:model.blur="street" type="text"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 @error('street') border-red-500 @endif @if (session('valid-street')) border-green-500 @endif"
                            id="street" placeholder="Mustergasse">
                        @error('street')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Number -->
                    <div class="flex
                            flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="number">
                            {{ __('address.number') }}
                        </label>
                        <input wire:model.blur="number" type="text"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 @error('number') border-red-500 @endif @if (session('valid-number')) border-green-500 @endif"
                            id="number" placeholder="12">
                        @error('number')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- PLZ -->
                    <div class="flex
                            flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="plz">
                            {{ __('address.plz') }} *
                        </label>
                        <input wire:model.blur="plz" type="number"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 @error('plz') border-red-500 @endif @if (session('valid-plz')) border-green-500 @endif"
                            id="plz" placeholder="7000">
                        @error('plz')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Town -->
                    <div class="flex
                            flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="town">
                            {{ __('address.town') }} *
                        </label>
                        <input wire:model.blur="town" type="text"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 @error('town') border-red-500 @endif @if (session('valid-town')) border-green-500 @endif"
                            id="town" placeholder="Musterhausen">
                        @error('town')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="flex
                            flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="country">
                            {{ __('address.country') }} *
                        </label>
                        <select wire:model.blur="country_id"
                            class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
						{{ $errors->has('country_id') ? 'border-red-500' : (session('valid-country_id') ? 'border-green-500' : 'border-gray-300') }}"
                            id="country">
                            <option value="">{{ __('attributes.please_select') }}</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="flex
                            flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="phone">
                            {{ __('user.phone') }}
                        </label>
                        <input wire:model.blur="phone" type="text"
                            class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
							{{ $errors->has('phone') ? 'border-red-500' : (session('valid-phone') ? 'border-green-500' : 'border-gray-300') }}"id="phone"
                            placeholder="+41 81 123 4567">
                        @error('phone')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="flex
                                flex-col">
                        <label class="block text-sm font-medium text-primary mb-1" for="email">
                            {{ __('user.email') }} *
                        </label>
                        <input wire:model.blur="email" type="email"
                            class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
							{{ $errors->has('email') ? 'border-red-500' : (session('valid-email') ? 'border-green-500' : 'border-gray-300') }}"id="email"
                            placeholder="max@mustermann.ch">
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-2
                                grid grid-cols-2 gap-6">
                        <!-- Password -->
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-primary mb-1" for="password">
                                {{ __('user.password_register') }} *
                            </label>
                            <input wire:model.blur="password" type="password"
                                class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
								{{ $errors->has('password') ? 'border-red-500' : (session('valid-password') ? 'border-green-500' : 'border-gray-300') }}"id="password">
                            @error('password')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="flex
                                    flex-col">
                            <label class="block text-sm font-medium text-primary mb-1" for="password_confirmation">
                                {{ __('user.password_confirmation') }} *
                            </label>
                            <input wire:model.blur="password_confirmation" type="password"
                                class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
								{{ $errors->has('password_confirmation') ? 'border-red-500' : (session('valid-password_confirmation') ? 'border-green-500' : 'border-gray-300') }}"id="password_confirmation">
                            @error('password_confirmation')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Terms -->
                    <div class="mt-8">
                        <label class="flex items-center">
                            <input wire:model.blur="terms" type="checkbox"
                                class="rounded border-gray-300 text-accent shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-primary">{{ __('regLog.accept') }}</span>
                        </label>
                        @error('terms')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="px-6 py-2 bg-primary text-white rounded-md hover:bg-danger-hover transition-colors">
                            {{ __('Registrieren') }}
                        </button>
                    </div>
            </form>
        </div>
    </section>

</main><!-- End #main -->
