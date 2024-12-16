<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative text-primary hover:text-accent">
        <i class="bi bi-bell text-xl"></i>
        @if ($notificationCount > 0)
            <span
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                {{ $notificationCount }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false"
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50">
        <!-- Header -->
        <div class="px-4 py-3 border-b">
            <h3 class="text-sm font-semibold text-gray-700">{{ __('message.messages') }}</h3>
        </div>

        <!-- Notifications List -->
        <div class="max-h-64 overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="p-4 border-b hover:bg-gray-50">
                    @if (
                        $notification->type == App\Notifications\MessageAddedAdmin::class ||
                            $notification->type == App\Notifications\MessageAddedUser::class)
                        <a href="{{ $notification->data['url'] }}" class="block">
                            <p class="text-sm text-gray-800">
                                <span class="font-semibold">{{ $notification->data['username'] }}</span>
                                {{ __('message.commented') }}
                                <span class="font-medium">{{ $notification->data['appl_name'] }}</span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification->data['message_body'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </a>
                    @elseif($notification->type == App\Notifications\NewApplication::class)
                        <a href="{{ $notification->data['url'] }}" class="block">
                            <p class="text-sm text-gray-800">
                                {{ __('message.newAppl') }}
                                <span class="font-semibold">{{ $notification->data['appl_name'] }}</span>
                                {{ __('message.submitted') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </a>
                    @else
                        <a href="{{ $notification->data['url'] }}" class="block">
                            <p class="text-sm text-gray-800">
                                {{ __('message.status') }}
                                <span class="font-semibold">{{ $notification->data['appl_name'] }}</span>
                                {{ __('message.changed') }}
                                <span
                                    class="font-semibold">{{ __('application.status_name.' . strtoupper($notification->data['appl_status'])) }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </a>
                    @endif
                </div>
            @empty
                <div class="p-4 text-sm text-gray-600 text-center">
                    {{ __('message.noMessages') }}
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if ($notificationCount > 0)
            <div class="p-4 border-t">
                <button wire:click="markAllAsRead"
                    class="w-full px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-600 transition-colors">
                    {{ __('message.markAllRead') }}
                </button>
            </div>
        @endif
    </div>
</div>
