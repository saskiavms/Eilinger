<div class="border rounded-lg bg-white" x-data="{ open: false }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">{{ __('sibling.title') }}</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        @forelse ($siblings as $sibling)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('sibling.lastname') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $sibling->lastname }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('sibling.firstname') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $sibling->firstname }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('sibling.birthday') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $sibling->birthday }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('sibling.place_of_residence') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $sibling->place_of_residence }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('sibling.education') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $sibling->education }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('sibling.graduation_year') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $sibling->graduation_year }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('sibling.get_amount') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $sibling->get_amount }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('sibling.support_site') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $sibling->support_site }}</span>
                    </p>
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-500">
                {{ __('sibling.noSiblings') }}
            </div>
        @endforelse
    </div>
</div>
