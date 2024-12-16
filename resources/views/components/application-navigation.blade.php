<div class="flex justify-between mt-6">
    @if ($currentStep > 1)
        <button wire:click="decreaseStep" wire:loading.attr="disabled"
            class="flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('attributes.back') }}
        </button>
    @endif

    @if ($currentStep == $totalSteps)
        @if (!$completeApp)
            <button disabled class="px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed">
                {{ __('sending.submitApplication') }}
            </button>
        @else
            <button wire:click="saveApplication"
                class="px-4 py-2 bg-danger hover:bg-danger-hover text-white rounded-md transition-colors">
                {{ __('sending.submitApplication') }}
            </button>
        @endif
    @endif

    @if ($currentStep < $totalSteps)
        <button wire:click="increaseStep" wire:loading.attr="disabled"
            class="flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors ml-auto">
            {{ __('attributes.continue') }}
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    @endif
</div>

@if ($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-primary">
                    {{ __('sending.submitApplication') }}
                </h3>
                <button wire:click="close" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-6 py-4">
                <p class="text-gray-700">{{ __('sending.submitApplicationConfirmation') }}</p>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-2 rounded-b-lg">
                <button wire:click="close"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    {{ __('attributes.close') }}
                </button>
                <button wire:click="save"
                    class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors">
                    {{ __('sending.submit') }}
                </button>
            </div>
        </div>
    </div>
@endif
