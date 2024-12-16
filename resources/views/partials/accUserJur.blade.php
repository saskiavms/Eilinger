<div class="border rounded-lg bg-white" x-data="{ open: true }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">Gesuchssteller</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.name_inst') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->name_inst }}</span>
                </p>
            </div>

            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.phone_inst') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->phone_inst }}</span>
                </p>
            </div>

            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.email_inst') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->email_inst }}</span>
                </p>
            </div>

            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.website') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->website }}</span>
                </p>
            </div>

            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.salutation') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->salutation }}</span>
                </p>
            </div>

            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.lastname') }} {{ __('user.contact') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->lastname }}</span>
                </p>
            </div>

            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.firstname') }}
                        {{ __('user.contact') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->firstname }}</span>
                </p>
            </div>

            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.email') }} {{ __('user.contact') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->email }}</span>
                </p>
            </div>

            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.phone') }} {{ __('user.contact') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->phone }}</span>
                </p>
            </div>

            <div>
                <p class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('user.mobile') }} {{ __('user.contact') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $user->mobile }}</span>
                </p>
            </div>
        </div>
    </div>
</div>
