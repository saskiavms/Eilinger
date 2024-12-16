<form wire:submit="saveaboardAddress">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('address.titleAboard') }}</h3>
        <p class="text-sm text-gray-600">{{ __('address.subTitleAboard') }}</p>
    </div>

    <x-notification />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Street -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="street">
                {{ __('address.street') }}*
            </label>
            <input wire:model.blur="street" type="text" id="street"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('street')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Number -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="number">
                {{ __('address.number') }}*
            </label>
            <input wire:model.blur="number" type="text" id="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
        </div>

        <!-- PLZ -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="plz">
                {{ __('address.plz') }}*
            </label>
            <input wire:model.blur="plz" type="text" id="plz"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('plz')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Town -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="town">
                {{ __('address.town') }}*
            </label>
            <input wire:model.blur="town" type="text" id="town"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            @error('town')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Country -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1" for="country">
                {{ __('address.country') }}*
            </label>
            <select wire:model.blur="country_id" id="country"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
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
    <div class="flex justify-center mt-6">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
