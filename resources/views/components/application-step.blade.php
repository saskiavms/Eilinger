@if ($currentStep == $stepNumber)
    <div class="bg-white rounded-lg shadow-sm mb-4">
        <div class="border-b border-gray-200">
            <div class="bg-primary text-white px-6 py-3 rounded-t-lg">
                {{ __('attributes.step') }} {{ $stepNumber }}/{{ $totalSteps }} - {{ $title }}
            </div>
        </div>
        <div class="p-4">
            @livewire($component, key($component . '-' . $stepNumber))
        </div>
    </div>

    {{-- Debug info --}}
    @if (config('app.debug'))
        <div class="text-sm text-gray-500 mb-2">
            Current Step: {{ $currentStep }}, Step Number: {{ $stepNumber }}, Component: {{ $component }}
        </div>
    @endif
@endif
