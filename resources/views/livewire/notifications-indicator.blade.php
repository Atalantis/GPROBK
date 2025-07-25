<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-2 -right-2 h-5 w-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-50"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         style="display: none;">

        <div class="p-3 border-b border-gray-200 dark:border-gray-700">
            <span class="font-semibold">Notifications</span>
        </div>

        <div class="py-1">
            @forelse ($notifications as $notification)
                <a href="{{ route('notifications.read', $notification->id) }}"
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ $notification->read() ? '' : 'font-bold' }}">
                    <p class="truncate">{{ $notification->data['title'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $notification->created_at->shortRelativeDiffForHumans() }}</p>
                </a>
            @empty
                <p class="px-4 py-2 text-sm text-gray-500">Aucune notification.</p>
            @endforelse
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('notifications.index') }}" class="block w-full text-center px-4 py-2 text-sm text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                Voir toutes les notifications
            </a>
        </div>
    </div>
</div>
