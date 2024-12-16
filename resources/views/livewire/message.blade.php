<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600">{{ $message->created_at->diffForHumans() }}</span>
            <span class="text-gray-400">•</span>
            <span class="text-sm font-medium text-primary">{{ $message->user->username }}</span>
        </div>

        <div class="flex items-center space-x-2">
            @if ($this->canReply)
                <button wire:click="$toggle('isReplying')" type="button"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-success rounded-md hover:bg-successHover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-500">
                    {{ __('message.reply') }}
                </button>
            @endif

            @can('update', $message)
                <button wire:click="$toggle('isEditing')" type="button"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    {{ __('message.edit') }}
                </button>
            @endcan

            @can('destroy', $message)
                <button wire:click="deleteMessage" wire:confirm="{{ __('message.confirmDelete') }}" type="button"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    {{ __('message.delete') }}
                </button>
            @endcan
        </div>
    </div>

    @if ($isEditing)
        <form wire:submit="editMessage" class="space-y-4">
            <div>
                <textarea wire:model="body" rows="3"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"></textarea>

                @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    {{ __('message.editMessage') }}
                </button>
            </div>
        </form>
    @else
        <div class="text-gray-700 whitespace-pre-wrap">{{ $message->body }}</div>
    @endif

    @if ($isReplying)
        <form wire:submit="postReply" class="mt-4 space-y-4">
            <div>
                <textarea wire:model="body" rows="3"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50"
                    placeholder="{{ __('message.writeReply') }}"></textarea>

                @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    {{ __('message.saveReply') }}
                </button>
            </div>
        </form>
    @endif

    @if ($message->replies->count() > 0)
        <div class="ml-6 mt-4 space-y-4 border-l-2 border-gray-200 pl-4">
            @foreach ($message->replies as $reply)
                @livewire('message', ['message' => $reply], key('reply-' . $reply->id))
            @endforeach
        </div>
    @endif
</div>
