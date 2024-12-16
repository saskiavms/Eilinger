<div class="border rounded-lg bg-white" x-data="{ open: false }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">Auszahlung</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        @if ($account)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-md">
                        <span class="font-medium text-gray-700">{{ __('account.name_bank') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $account->name_bank }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-md">
                        <span class="font-medium text-gray-700">{{ __('account.city_bank') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $account->city_bank }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-md">
                        <span class="font-medium text-gray-700">{{ __('account.owner') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $account->owner }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-md">
                        <span class="font-medium text-gray-700">{{ __('account.IBAN') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $account->IBAN }}</span>
                    </p>
                </div>
            </div>
        @else
            <div class="text-md text-gray-500">
                {{ __('account.noAccount') }}
            </div>
        @endif
    </div>
</div>
