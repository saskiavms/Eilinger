<div class="border rounded-lg bg-white" x-data="{ open: false }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">Ausbildung</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        @if ($education)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('education.initial_education') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $education->initial_education }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('education.education') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $education->education }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('education.name') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $education->name }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('education.final') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $education->final }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('education.grade') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $education->grade }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('education.ects_points') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $education->ects_points }}</span>
                    </p>
                </div>
            </div>
        @else
            <div class="text-sm text-gray-500">
                {{ __('education.noEducation') }}
            </div>
        @endif
    </div>
</div>
