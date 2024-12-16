@php use App\Enums\CivilStatus; @endphp
<form wire:submit="saveUserNat">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('user.applicant') }}</h3>
        <p class="text-sm text-gray-600">{{ __('user.subtitle') }}</p>
    </div>

    <x-notification />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Salutation -->
        <div>
            <label for="salutation" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.salutation') }} *
            </label>
            <select wire:model.blur="salutation"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach (App\Enums\Salutation::cases() as $salutation)
                    <option value="{{ $salutation }}">{{ __('user.salutation_name.' . $salutation->name) }}</option>
                @endforeach
            </select>
            @error('salutation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- First Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.firstname') }} *
            </label>
            <input wire:model.blur="firstname" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('firstname')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Last Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.lastname') }} *
            </label>
            <input wire:model.blur="lastname" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('lastname')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nationality -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.nationality') }} *
            </label>
            <select wire:model.blur="nationality"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->short_code }}">{{ $country->name }}</option>
                @endforeach
            </select>
            @error('nationality')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Birthday -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.birthday') }} *
            </label>
            <input wire:model.blur="birthday" type="date"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('birthday')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Civil Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.civil_status') }} *
            </label>
            <select wire:model.blur="civil_status"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach (CivilStatus::cases() as $status)
                    <option value="{{ $status->value }}">{{ __('user.civil_status_name.' . $status->name) }}</option>
                @endforeach
            </select>
            @error('civil_status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Contact Information -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.phone') }}
            </label>
            <input wire:model.blur="phone" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.mobile') }}
            </label>
            <input wire:model.blur="mobile" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.soz_vers_nr') }}
            </label>
            <input wire:model.blur="soz_vers_nr" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
        </div>

        <!-- Switzerland Information -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.in_ch_since') }}
            </label>
            <input wire:model.live="in_ch_since" type="date"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('user.granting') }}
            </label>
            <select wire:model.blur="granting"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach (App\Enums\Bewilligung::cases() as $granting)
                    <option value="{{ $granting->value }}">{{ __('user.permit_name.' . $granting->name) }}</option>
                @endforeach
            </select>
            @error('granting')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Partner Form (Conditional) -->
    @if ($this->partnerVisible)
        <div class="mt-8">
            @livewire('antrag.parent-form')
        </div>
    @endif

    <!-- Submit Button -->
    <div class="mt-8 flex justify-center">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
