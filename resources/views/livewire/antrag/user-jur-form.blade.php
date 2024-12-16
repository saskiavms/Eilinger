<form wire:submit="saveUserJur">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('user.candidate') }}</h3>
        <p class="text-sm text-gray-600">{{ __('user.subtitleOrg') }}</p>
    </div>

    <x-notification />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Organization Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="name_inst">
                {{ __('user.name_inst') }}
            </label>
            <input wire:model.blur="name_inst" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('name_inst')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Organization Phone -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="phone_inst">
                {{ __('user.phone_inst') }}
            </label>
            <input wire:model.blur="phone_inst" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('phone_inst')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Organization Email -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="email_inst">
                {{ __('user.email_inst') }}
            </label>
            <input wire:model.blur="email_inst" type="email"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('email_inst')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Website -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="website">
                {{ __('user.website') }}
            </label>
            <input wire:model.blur="website" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('website')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contact Person Section -->
        <div class="md:col-span-2 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">{{ __('user.details') }} {{ __('user.contact') }}</p>
        </div>

        <!-- Salutation -->
        <div class="md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="salutation">
                {{ __('user.salutation') }}
            </label>
            <select wire:model.blur="salutation"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach (App\Enums\Salutation::cases() as $salutation)
                    <option value="{{ $salutation }}">{{ $salutation }}</option>
                @endforeach
            </select>
            @error('salutation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contact First Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="firstname">
                {{ __('user.firstname') }}
            </label>
            <input wire:model.blur="firstname" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('firstname')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contact Last Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="lastname">
                {{ __('user.lastname') }}
            </label>
            <input wire:model.blur="lastname" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('lastname')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contact Phone -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="phone">
                {{ __('user.phone') }}
            </label>
            <input wire:model.blur="phone" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contact Mobile -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="mobile">
                {{ __('user.mobile') }}
            </label>
            <input wire:model.blur="mobile" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
        </div>

        <!-- Contact Aboard -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="contact_aboard">
                {{ __('user.contact_aboard') }}
            </label>
            <input wire:model.blur="contact_aboard" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-center mt-6">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
