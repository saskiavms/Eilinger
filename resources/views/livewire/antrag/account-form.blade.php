<form wire:submit="saveAccount">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('account.title') }}</h3>
        <p class="text-sm text-gray-600">{{ __('account.subtitle') }}</p>
    </div>

    <x-notification />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Bank Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('account.name_bank') }} *
            </label>
            <input wire:model.blur="name_bank" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('name_bank')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bank City -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('account.city_bank') }} *
            </label>
            <input wire:model.blur="city_bank" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('city_bank')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Account Owner -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('account.owner') }} *
            </label>
            <input wire:model.blur="owner" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('owner')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- IBAN -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('account.IBAN') }} *
            </label>
            <input wire:model.blur="IBAN" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('IBAN')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-8 flex justify-center">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
