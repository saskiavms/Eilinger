<div class="border rounded-lg bg-white" x-data="{ open: false }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">{{ __('financing.title') }}</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        @if ($financingOrganisation)
            <div class="space-y-4">
                @foreach ($financingOrganisation as $financing)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm">
                                <span class="font-medium text-gray-700">{{ __('financing.financing_name') }}:</span>
                                <span class="text-gray-900 ml-1">{{ $financing->financing_name }}</span>
                            </p>
                        </div>
                        <x-convert-money :amount="$financing->financing_amount" :text="__('financing.financing_amount')" :currency="$application->currency->abbreviation" />
                    </div>
                @endforeach

                <div class="text-right">
                    <x-convert-money :amount="$this->getTotalFinancingOrganisation()" :text="__('financing.total')" :currency="$application->currency->abbreviation" class="justify-end" />
                </div>
            </div>
        @else
            <div class="text-sm text-gray-500">
                Keine Finanzierung eingetragen
            </div>
        @endif
    </div>
</div>
