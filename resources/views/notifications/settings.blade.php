<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Paramètres de Notification') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Global Settings --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Notifications Globales</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Activer ou désactiver toutes les notifications (temps réel et email).
                    </p>
                    <form method="post" action="{{ route('notifications.global.toggle') }}" class="mt-6">
                        @csrf
                        <input type="hidden" name="enabled" value="0">
                        <label for="notifications_enabled" class="inline-flex items-center">
                            <input type="checkbox" id="notifications_enabled" name="enabled" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                   @if($user->notifications_enabled) checked @endif
                                   onchange="this.form.submit()">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Activer les notifications') }}</span>
                        </label>
                    </form>
                </div>
            </div>

            {{-- Muted Items --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Éléments en Sourdine</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Vous ne recevrez pas de notifications pour les projets, tâches ou utilisateurs listés ci-dessous.
                    </p>
                    <div class="mt-6 space-y-4">
                        @forelse ($mutedItems as $item)
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        @if ($item->notifiable)
                                            {{-- Display a user-friendly name for the muted item --}}
                                            @if ($item->notifiable_type === 'App\\Models\\Project')
                                                Projet: {{ $item->notifiable->title }}
                                            @elseif ($item->notifiable_type === 'App\\Models\\Task')
                                                Tâche: {{ $item->notifiable->title }}
                                            @elseif ($item->notifiable_type === 'App\\Models\\User')
                                                Utilisateur: {{ $item->notifiable->name }}
                                            @else
                                                Élément: {{ $item->notifiable_id }}
                                            @endif
                                        @else
                                            Élément introuvable (ID: {{ $item->notifiable_id }})
                                        @endif
                                    </span>
                                </div>
                                <form method="post" action="{{ route('notifications.mute.toggle') }}">
                                    @csrf
                                    <input type="hidden" name="notifiable_type" value="{{ $item->notifiable_type }}">
                                    <input type="hidden" name="notifiable_id" value="{{ $item->notifiable_id }}">
                                    <x-secondary-button type="submit">
                                        Réactiver
                                    </x-secondary-button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Aucun élément n'est actuellement en sourdine.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>