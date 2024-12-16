<form wire:submit="saveCost">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('cost.title') }}</h3>
        <p class="text-sm text-gray-600">{{ __('cost.subtitle') }}</p>
    </div>

    <x-notification />

    <!-- Education Costs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Semester Fees -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('cost.semester_fees') }}
            </label>
            <input wire:model.blur="semester_fees" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('semester_fees')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Fees -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('cost.fees') }}
            </label>
            <input wire:model.blur="fees" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('fees')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Educational Material -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('cost.educational_material') }}
            </label>
            <input wire:model.blur="educational_material" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('educational_material')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Excursion -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('cost.excursion') }}
            </label>
            <input wire:model.blur="excursion" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('excursion')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Travel Expenses -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('cost.travel_expenses') }}
            </label>
            <input wire:model.blur="travel_expenses" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('travel_expenses')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Number of Children -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('cost.number_of_children') }}
            </label>
            <input wire:model.blur="number_of_children" type="number"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
            @error('number_of_children')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Living Costs -->
    <div class="mb-6">
        <h4 class="text-lg font-medium text-primary mb-2">{{ __('cost.other_standard_of_living') }}</h4>
        <p class="text-sm text-gray-600 mb-4">{{ __('attributes.fillSelected') }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Living with Parents -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('cost.cost_of_living_with_parents') }}
                </label>
                <input wire:model.blur="cost_of_living_with_parents" type="number"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                @error('cost_of_living_with_parents')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Living Alone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('cost.cost_of_living_alone') }}
                </label>
                <input wire:model.blur="cost_of_living_alone" type="number"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                @error('cost_of_living_alone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Living as Single Parent -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('cost.cost_of_living_single_parent') }}
                </label>
                <input wire:model.blur="cost_of_living_single_parent" type="number"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                @error('cost_of_living_single_parent')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Living with Partner -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('cost.cost_of_living_with_partner') }}
                </label>
                <input wire:model.blur="cost_of_living_with_partner" type="number"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                @error('cost_of_living_with_partner')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Total Costs -->
    <div class="border-t border-gray-200 pt-4 mb-8">
        <p class="text-right text-lg font-medium">
            {{ __('cost.totalCosts') }} @convert($this->getAmountCost(), $this->myCurrency->abbreviation)
        </p>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-center">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
