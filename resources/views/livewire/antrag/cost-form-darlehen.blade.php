<form wire:submit="saveCosts">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('cost.title_loan') }}</h3>
        <p class="text-sm text-gray-600">{{ __('cost.subtitle_loan') }}</p>
    </div>

    <x-notification />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($costs as $index => $cost)
            <!-- Cost Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('cost.cost') }}
                </label>
                <input type="text" wire:model.blur="costs.{{ $index }}.cost_name"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                @error('costs.' . $index . '.cost_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cost Amount -->
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('cost.cost_amount') }}
                </label>
                <div class="flex items-center">
                    <input type="number" step="0.01" wire:model.blur="costs.{{ $index }}.cost_amount"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">

                    <!-- Delete Button -->
                    <button type="button" wire:click="removeCost({{ $index }})"
                        class="ml-2 p-2 text-red-600 hover:text-red-800 focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                @error('costs.' . $index . '.cost_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <!-- Add Cost Button -->
        <div class="md:col-span-2">
            <button type="button" wire:click="addCost"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary hover:text-primary-dark focus:outline-none">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('cost.add_cost') }}
            </button>
        </div>

        <!-- Total Costs -->
        <div class="md:col-span-2 pt-4 border-t border-gray-200">
            <div class="flex justify-end">
                <p class="text-lg font-medium text-gray-900">
                    {{ __('cost.totalCosts') }}
                    <span class="ml-2 text-primary">@convert($total_amount, $myCurrency->abbreviation)</span>
                </p>
            </div>
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
