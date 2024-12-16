<div class="py-6">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-ubuntu text-primary font-semibold">
            {{ __('userDashboard.delAccount') }}
        </h2>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
            <div class="p-6">
                <p class="text-gray-700">{{ __('user.deleteAccountText') }}</p>

                <div class="mt-6">
                    <button wire:click="delete"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        {{ __('userDashboard.delAccount') }}
                    </button>
                </div>
            </div>
        </div>

        @if ($showModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ __('userDashboard.delAccount') }}
                        </h3>
                        <button wire:click="close" type="button" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="destroy">
                        <div class="px-6 py-4 space-y-4">
                            <p class="text-gray-700">{{ __('user.delAccountConfirmation') }}</p>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ __('user.password') }}*
                                </label>
                                <input wire:model.live="current_password" type="password"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-2 rounded-b-lg">
                            <button wire:click="close" type="button"
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                                {{ __('attributes.close') }}
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                {{ __('userDashboard.delAccount') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
