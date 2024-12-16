<div class="py-6">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-ubuntu text-primary font-semibold">
            {{ __('message.allMessagesTitle') }}
        </h2>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if ($applications)
                    @forelse($applications as $application)
                        <div class="mb-4">
                            <div class="border border-gray-200 rounded-lg">
                                <button type="button"
                                    class="w-full px-4 py-3 text-left text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200"
                                    x-data=""
                                    x-on:click="$dispatch('toggle-collapse', { id: '{{ $application->id }}' })"
                                    aria-expanded="false" aria-controls="content_{{ $application->id }}">
                                    {{ $application->name }}
                                </button>

                                <div id="content_{{ $application->id }}" x-data="{ open: false }" x-show="open"
                                    x-on:toggle-collapse.window="open = $event.detail.id === '{{ $application->id }}' ? !open : false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2" class="hidden"
                                    style="display: none;">
                                    <div class="p-4 border-t border-gray-200">
                                        @livewire('messages-section', ['application' => $application])
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            {{ __('message.allMessagesTitle') }}
                        </div>
                    @endforelse
                @else
                    <div class="text-center py-4 text-gray-500">
                        {{ __('message.no_appl') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
