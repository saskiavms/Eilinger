<div class="border rounded-lg bg-white" x-data="{ open: false }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">Eltern</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        @forelse ($parents as $parent)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.parent_type') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->parent_type }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.lastname') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->lastname }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.firstname') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->firstname }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.birthday') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->birthday }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.phone') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->phone }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.address') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->address }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.plz_ort') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->plz_ort }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.since') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->since }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.job') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->job }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.employer') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->employer }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm">
                        <span class="font-medium text-gray-700">{{ __('parents.job_type') }}:</span>
                        <span class="text-gray-900 ml-1">{{ $parent->job_type }}</span>
                    </p>
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-500">
                {{ __('parents.noParents') }}
            </div>
        @endforelse
    </div>
</div>
