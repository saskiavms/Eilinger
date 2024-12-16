<div class="border rounded-lg bg-white" x-data="{ open: false }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">Kosten</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        @if ($costDarlehen)
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($costDarlehen as $cost)
                        <div>
                            <p class="text-md">
                                <span class="font-medium text-gray-700">{{ __('cost.cost_name') }}:</span>
                                <span class="text-gray-900 ml-1">{{ $cost->cost_name }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-md">
                                <span class="font-medium text-gray-700">{{ __('cost.cost_amount') }}:</span>
                                <span class="text-gray-900 ml-1">@convert($cost->cost_amount, $application->currency->abbreviation)</span>
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="text-right">
                    <p class="text-md font-medium">
                        {{ __('cost.totalCosts') }}
                        <span class="text-gray-900 ml-1">@convert($this->getTotalCostDarlehen(), $application->currency->abbreviation)</span>
                    </p>
                </div>
            </div>
        @else
            <div class="text-md text-gray-500">
                {{ __('cost.noCost') }}
            </div>
        @endif
    </div>
</div>
