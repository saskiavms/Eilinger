<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="p-6">
        <h3 class="text-2xl font-ubuntu text-primary font-semibold mb-6">
            {{ __('message.messages') }}
        </h3>

        <form wire:submit="postMessage" class="mb-8">
            <div class="space-y-4">
                <div>
                    <textarea wire:model="body" rows="4"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                        placeholder="{{ __('message.writeMessage') }}"></textarea>

                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors">
                        {{ __('message.saveMessage') }}
                    </button>
                </div>
            </div>
        </form>

        <div class="space-y-6">
            @forelse ($messages as $message)
                <div class="border rounded-lg p-4 bg-gray-50">
                    @livewire('message', ['message' => $message], key('message-' . $message->id))
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">
                    {{ __('message.noMessages') }}
                </p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </div>
</div>
