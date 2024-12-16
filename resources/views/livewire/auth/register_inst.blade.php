<section class="py-16">
    <div class="container mx-auto px-4">
        <x-heading.decorative class="text-center">
            {{ __('regLog.regOrg') }}
        </x-heading.decorative>

        <form wire:submit="registerInst">
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
                </div>

                <!-- Institution Name -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="name_inst">
                        {{ __('user.name_inst') }} *
                    </label>
                    <input wire:model.blur="name_inst" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('name_inst') ? 'border-red-500' : (session('valid-name_inst') ? 'border-green-500' : 'border-gray-300') }}"
                        id="name_inst" placeholder="Firma Mustermann">
                    @error('name_inst')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Street -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="street">
                        {{ __('address.street') }} *
                    </label>
                    <input wire:model.blur="street" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('street') ? 'border-red-500' : (session('valid-street') ? 'border-green-500' : 'border-gray-300') }}"
                        id="street" placeholder="Mustergasse">
                    @error('street')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Number -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="number">
                        {{ __('address.number') }}
                    </label>
                    <input wire:model.blur="number" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('number') ? 'border-red-500' : (session('valid-number') ? 'border-green-500' : 'border-gray-300') }}"
                        id="number" placeholder="12">
                    @error('number')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- PLZ -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="plz">
                        {{ __('address.plz') }} *
                    </label>
                    <input wire:model.blur="plz" type="number"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('plz') ? 'border-red-500' : (session('valid-plz') ? 'border-green-500' : 'border-gray-300') }}"
                        id="plz" placeholder="7000">
                    @error('plz')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Town -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="town">
                        {{ __('address.town') }} *
                    </label>
                    <input wire:model.blur="town" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('town') ? 'border-red-500' : (session('valid-town') ? 'border-green-500' : 'border-gray-300') }}"
                        id="town" placeholder="Musterhausen">
                    @error('town')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Country -->
                <div class="flex flex-col">
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

                <!-- Institution Phone -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="phone_inst">
                        {{ __('user.phone_inst') }}
                    </label>
                    <input wire:model.blur="phone_inst" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('phone_inst') ? 'border-red-500' : (session('valid-phone_inst') ? 'border-green-500' : 'border-gray-300') }}"
                        id="phone_inst" placeholder="+41 81 123 4567">
                    @error('phone_inst')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Institution Email -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="email_inst">
                        {{ __('user.email_inst') }}
                    </label>
                    <input wire:model.blur="email_inst" type="email"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('email_inst') ? 'border-red-500' : (session('valid-email_inst') ? 'border-green-500' : 'border-gray-300') }}"
                        id="email_inst" placeholder="muster@muster.ch">
                    @error('email_inst')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Website -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="website">
                        {{ __('user.website') }}
                    </label>
                    <input wire:model.blur="website" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('website') ? 'border-red-500' : (session('valid-website') ? 'border-green-500' : 'border-gray-300') }}"
                        id="website" placeholder="https://www.musterfirma.ch">
                    @error('website')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Person Fields -->
                <!-- Salutation -->
                <div class="flex flex-col">
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

                <!-- Contact First Name -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="firstname">
                        {{ __('user.firstname') }} {{ __('user.contact') }} *
                    </label>
                    <input wire:model.blur="firstname" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('firstname') ? 'border-red-500' : (session('valid-firstname') ? 'border-green-500' : 'border-gray-300') }}"
                        id="firstname" placeholder="Max">
                    @error('firstname')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Last Name -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="lastname">
                        {{ __('user.lastname') }} {{ __('user.contact') }} *
                    </label>
                    <input wire:model.blur="lastname" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('lastname') ? 'border-red-500' : (session('valid-lastname') ? 'border-green-500' : 'border-gray-300') }}"
                        id="lastname" placeholder="Muster">
                    @error('lastname')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Phone -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="phone">
                        {{ __('user.phone') }} {{ __('user.contact') }} *
                    </label>
                    <input wire:model.blur="phone" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('phone') ? 'border-red-500' : (session('valid-phone') ? 'border-green-500' : 'border-gray-300') }}"
                        id="phone" placeholder="+41 81 123 4567">
                    @error('phone')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Mobile -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="mobile">
                        {{ __('user.mobile') }} {{ __('user.contact') }} *
                    </label>
                    <input wire:model.blur="mobile" type="text"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('mobile') ? 'border-red-500' : (session('valid-mobile') ? 'border-green-500' : 'border-gray-300') }}"
                        id="mobile" placeholder="+41 79 123 4567">
                    @error('mobile')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Email -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="email">
                        {{ __('user.email') }} {{ __('user.contact') }} *
                    </label>
                    <input wire:model.blur="email" type="email"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('email') ? 'border-red-500' : (session('valid-email') ? 'border-green-500' : 'border-gray-300') }}"
                        id="email" placeholder="max@mustermann.ch">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Password fields in one row -->
            <div class="col-span-2 grid grid-cols-2 gap-6 mt-6">
                <!-- Password -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="password">
                        {{ __('user.password_register') }} *
                    </label>
                    <input wire:model.blur="password" type="password"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('password') ? 'border-red-500' : (session('valid-password') ? 'border-green-500' : 'border-gray-300') }}"
                        id="password">
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="flex flex-col">
                    <label class="block text-sm font-medium text-primary mb-1" for="password_confirmation">
                        {{ __('user.password_confirmation') }} *
                    </label>
                    <input wire:model.blur="password_confirmation" type="password"
                        class="w-full rounded-md shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50
                        {{ $errors->has('password_confirmation') ? 'border-red-500' : (session('valid-password_confirmation') ? 'border-green-500' : 'border-gray-300') }}"
                        id="password_confirmation">
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
                    {{ __('regLog.register') }}
                </button>
            </div>
        </form>
    </div>
</section>
