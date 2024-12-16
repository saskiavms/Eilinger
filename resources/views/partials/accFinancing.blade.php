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
        @if ($financing)
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-convert-money :amount="$financing->personal_contribution" :text="__('financing.personal_contribution')" :currency="$application->currency->abbreviation" />
                    <x-convert-money :amount="$financing->netto_income" :text="__('financing.netto_income')" :currency="$application->currency->abbreviation" />
                    <x-convert-money :amount="$financing->assets" :text="__('financing.assets')" :currency="$application->currency->abbreviation" />
                    <x-convert-money :amount="$financing->scholarship" :text="__('financing.scholarship')" :currency="$application->currency->abbreviation" />
                    <x-convert-money :amount="$financing->other_income" :text="__('financing.other_income')" :currency="$application->currency->abbreviation" />
                </div>

                <!-- Additional Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-md">
                            <span class="font-medium text-gray-700">{{ __('financing.income_where') }}:</span>
                            <span class="text-gray-900 ml-1">{{ $financing->income_where }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-md">
                            <span class="font-medium text-gray-700">{{ __('financing.incomeWho') }}:</span>
                            <span class="text-gray-900 ml-1">{{ $financing->incomeWho }}</span>
                        </p>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="text-right">
                    <x-convert-money :amount="$financing->total_amount_financing" :text="__('financing.total')" :currency="$application->currency->abbreviation" />
                </div>
            </div>
        @else
            <div class="text-md text-gray-500">
                {{ __('financing.noFinancing') }}
            </div>
        @endif
    </div>
</div>
