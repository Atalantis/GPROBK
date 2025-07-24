<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gérer les Champs Personnalisés') }}
            </h2>
            <a href="{{ route('custom-fields.create') }}" class="inline-flex items-center px-4 py-2 bg-insuractio-primary text-white rounded-md">
                Ajouter un champ
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Nom du Champ</th>
                                <th class="px-6 py-3">Type</th>
                                <th class="px-6 py-3">Appliqué à</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fields as $field)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $field->name }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($field->type) }}</td>
                                    <td class="px-6 py-4">{{ Str::afterLast($field->model_type, '\\') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('custom-fields.edit', $field) }}" class="text-indigo-600 hover:underline">Modifier</a>
                                        <form action="{{ route('custom-fields.destroy', $field) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Êtes-vous sûr ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">Aucun champ personnalisé défini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
