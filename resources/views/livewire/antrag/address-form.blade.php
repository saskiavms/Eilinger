<form wire:submit="saveAddress">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('address.title') }}</h3>
        <p class="text-sm text-gray-600">{{ __('address.subTitle') }}</p>
        @if (!$isEditable)
            <div class="mt-2 p-3 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                <strong>{{ __('application.edit_restriction_hint') }}</strong> {{ __('application.edit_restriction_warning') }}
            </div>
        @endif
    </div>

    <x-notification />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Street -->
        <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('address.street') }} *
            </label>
            <input wire:model.blur="street" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 {{ !$isEditable ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                {{ !$isEditable ? 'readonly' : '' }}>
            @error('street')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Number -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('address.number') }}
            </label>
            <input wire:model.blur="number" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 {{ !$isEditable ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                {{ !$isEditable ? 'readonly' : '' }}>
            @error('number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- PLZ -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('address.plz') }} *
            </label>
            <input wire:model.blur="plz" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 {{ !$isEditable ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                {{ !$isEditable ? 'readonly' : '' }}>
            @error('plz')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Town -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('address.town') }} *
            </label>
            <input wire:model.blur="town" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 {{ !$isEditable ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                {{ !$isEditable ? 'readonly' : '' }}>
            @error('town')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Country -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('address.country') }} *
            </label>
            <select wire:model.blur="country_id"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 {{ !$isEditable ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                {{ !$isEditable ? 'disabled' : '' }}>
                <option value="">{{ __('attributes.please_select') }}</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
            @error('country_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    @if ($isEditable)
        <div class="mt-8 flex justify-center">
            <button type="submit"
                class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
                {{ __('attributes.save') }}
            </button>
        </div>
    @endif
</form>
