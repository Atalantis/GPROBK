@props(['model'])

<div class="mt-6 border-t dark:border-gray-700 pt-4">
    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-2">Liens Utiles</h4>
    <ul class="space-y-2 mb-4">
        @forelse ($model->links as $link)
            <li class="flex items-center justify-between text-sm">
                <a href="{{ $link->url }}" target="_blank" class="text-blue-500 hover:underline">{{ $link->title }}</a>
                <form action="{{ route('links.destroy', $link) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:underline">Supprimer</button>
                </form>
            </li>
        @empty
            <li class="text-sm text-gray-500">Aucun lien ajouté.</li>
        @endforelse
    </ul>

    <form action="{{ route('links.store') }}" method="POST" class="space-y-2">
        @csrf
        <input type="hidden" name="linkable_type" value="{{ get_class($model) }}">
        <input type="hidden" name="linkable_id" value="{{ $model->id }}">
        <div>
            <x-input-label for="link_title" value="Titre du lien" class="sr-only" />
            <x-text-input id="link_title" name="title" class="w-full" placeholder="Titre du lien" required />
        </div>
        <div>
            <x-input-label for="link_url" value="URL" class="sr-only" />
            <x-text-input id="link_url" name="url" type="url" class="w-full" placeholder="https://..." required />
        </div>
        <x-primary-button>Ajouter le lien</x-primary-button>
    </form>
</div>
