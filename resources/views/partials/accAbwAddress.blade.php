<div class="border rounded-lg bg-white" x-data="{ open: false }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">Abweichende Adresse</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        @if ($abweichendeAddress)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('address.street') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $abweichendeAddress->street }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('address.number') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $abweichendeAddress->number }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('address.plz') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $abweichendeAddress->plz }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('address.town') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $abweichendeAddress->town }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('address.country') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $abweichendeAddress->country->name }}</span>
                    </p>
                </div>
            </div>
        @else
            <div class="text-sm text-gray-500">
                {{ __('address.noAddress') }}
            </div>
        @endif
    </div>
</div>
