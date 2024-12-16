<div class="border rounded-lg bg-white" x-data="{ open: false }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">{{ __('cost.cost') }}</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        @if ($cost)
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-convert-money :amount="$cost->semester_fees" :text="__('cost.semester_fees')" :currency="$application->currency->abbreviation" />
                    <x-convert-money :amount="$cost->fees" :text="__('cost.fees')" :currency="$application->currency->abbreviation" />
                    <x-convert-money :amount="$cost->educational_material" :text="__('cost.educational_material')" :currency="$application->currency->abbreviation" />
                    <x-convert-money :amount="$cost->excursion" :text="__('cost.excursion')" :currency="$application->currency->abbreviation" />
                    <x-convert-money :amount="$cost->travel_expenses" :text="__('cost.travel_expenses')" :currency="$application->currency->abbreviation" />

                    <div>
                        <p class="text-sm">
                            <span class="font-medium text-gray-700">{{ __('cost.number_of_children') }}:</span>
                            <span class="text-gray-900 ml-1">{{ $cost->number_of_children }}</span>
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('cost.other_standard_of_living') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-convert-money :amount="$cost->cost_of_living_with_parents" :text="__('cost.cost_of_living_with_parents')" :currency="$application->currency->abbreviation" />
                        <x-convert-money :amount="$cost->cost_of_living_alone" :text="__('cost.cost_of_living_alone')" :currency="$application->currency->abbreviation" />
                        <x-convert-money :amount="$cost->cost_of_living_single_parent" :text="__('cost.cost_of_living_single_parent')" :currency="$application->currency->abbreviation" />
                        <x-convert-money :amount="$cost->cost_of_living_with_partner" :text="__('cost.cost_of_living_with_partner')" :currency="$application->currency->abbreviation" />
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-sm font-medium">
                        {{ __('cost.totalCosts') }}:
                        <span class="text-gray-900">@convert($cost->total_amount_costs, $application->currency->abbreviation)</span>
                    </p>
                </div>
            </div>
        @else
            <div class="text-sm text-gray-500">
                {{ __('cost.noCost') }}
            </div>
        @endif
    </div>
</div>
