<div class="py-6">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 mb-6">
            <h2 class="text-3xl font-ubuntu text-primary font-semibold">
                {{ __('message.messageRequest') }}
                <span>{{ $application->name }}</span>
                <span class="text-gray-600 text-lg">
                    ({{ __('application.area') }}: {{ $application->bereich }})
                </span>
            </h2>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @livewire('messages-section', ['application' => $application])
            </div>
        </div>
    </div>
</div>
