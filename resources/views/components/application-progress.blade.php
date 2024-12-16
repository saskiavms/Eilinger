<div class="mb-8">
    <div class="flex justify-between mb-2">
        <span class="text-sm font-medium text-primary">{{ __('attributes.step') }}
            {{ $currentStep }}/{{ $totalSteps }}</span>
        <span class="text-sm font-medium text-primary">{{ round(($currentStep / $totalSteps) * 100) }}%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="bg-primary h-2.5 rounded-full transition-all duration-500"
            style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
    </div>
</div>
