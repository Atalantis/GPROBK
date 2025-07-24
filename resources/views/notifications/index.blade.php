<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Toutes les Notifications') }}
            </h2>
            <form action="{{ route('notifications.readall') }}" method="POST">
                @csrf
                <x-secondary-button type="submit">Tout marquer comme lu</x-secondary-button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <ul class="space-y-4">
                        @forelse ($notifications as $notification)
                            <li class="{{ $notification->read() ? 'opacity-60' : '' }}">
                                <a href="{{ route('notifications.read', $notification->id) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700 p-4 rounded-md">
                                    <p class="font-semibold">{{ $notification->data['title'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $notification->data['message'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </a>
                            </li>
                        @empty
                            <li class="text-center text-gray-500">Vous n'avez aucune notification.</li>
                        @endforelse
                    </ul>

                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
