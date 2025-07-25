<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Détail du Projet : {{ $project->title }}
                </h2>
                @php
                    $isMuted = Auth::user()->isMuted(App\Models\Project::class, $project->id);
                @endphp
                <form id="mute-form" method="post" action="{{ route('notifications.mute.toggle') }}" class="m-0">
                    @csrf
                    <input type="hidden" name="notifiable_type" value="{{ App\Models\Project::class }}">
                    <input type="hidden" name="notifiable_id" value="{{ $project->id }}">
                    <button type="submit" title="{{ $isMuted ? 'Réactiver les notifications' : 'Mettre les notifications en sourdine' }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        @if ($isMuted)
                            {{-- Bell-slash icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.86 5.86a14.95 14.95 0 00-2.12 2.12m14.14 0a14.95 14.95 0 00-2.12-2.12M12 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        @else
                            {{-- Bell icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        @endif
                    </button>
                </form>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('projects.calendar', $project) }}" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500">
                    Calendrier
                </a>
                <a href="{{ route('projects.gantt', $project) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                    GANTT
                </a>
                <a href="{{ route('projects.kanban', $project) }}" class="inline-flex items-center px-4 py-2 bg-insuractio-accent border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                    Kanban
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informations Générales</h3>
                    <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <p><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Titre:</strong> <span>{{ $project->title }}</span></p>
                        <p><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Étudiant:</strong> <span>{{ $project->student->name }}</span></p>
                        <p class="flex items-center"><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Statut:</strong>
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white
                                @switch($project->status)
                                    @case('draft') bg-gray-500 @break
                                    @case('active') bg-insuractio-secondary @break
                                    @case('review') bg-insuractio-warning @break
                                    @case('completed') bg-insuractio-success @break
                                @endswitch
                            ">
                                {{ ucfirst($project->status) }}
                            </span>
                        </p>
                        <p><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Créé le:</strong> <span>{{ $project->created_at->format('d/m/Y H:i') }}</span></p>
                        <p><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Mis à jour le:</strong> <span>{{ $project->updated_at->format('d/m/Y H:i') }}</span></p>
                        @if($project->categories->isNotEmpty())
                        <div class="flex items-center pt-2">
                            <strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Catégories:</strong>
                            <div class="flex flex-wrap gap-1">
                                @foreach($project->categories as $category)
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-800 rounded-full">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Description</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {!! nl2br(e($project->description)) ?: 'Aucune description fournie.' !!}
                    </p>
                </div>
            </div>

            @if($project->customFields->isNotEmpty())
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informations Complémentaires</h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                        @foreach($project->customFields as $field)
                            <div>
                                <strong class="font-semibold text-gray-900 dark:text-gray-100">{{ $field->definition->name }}:</strong>
                                <span>{{ $field->value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Jalons du Projet</h3>
                        @can('update', $project)
                            <a href="{{ route('projects.milestones.create', $project) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md text-xs">
                                Ajouter un jalon
                            </a>
                        @endcan
                    </div>
                    <ul class="space-y-3">
                        @forelse ($project->milestones as $milestone)
                            <li class="flex justify-between items-center p-3 rounded-md {{ $milestone->status == 'completed' ? 'bg-green-50 dark:bg-green-900' : 'bg-gray-50 dark:bg-gray-900' }}">
                                <div>
                                    <p class="font-semibold">{{ $milestone->title }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $milestone->due_date?->format('d M Y') ?? 'Pas de date' }}</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $milestone->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $milestone->status == 'completed' ? 'Terminé' : 'Ouvert' }}
                                    </span>
                                    @can('update', $project)
                                        <a href="{{ route('milestones.edit', $milestone) }}" class="text-sm text-indigo-600 hover:underline">Modifier</a>
                                    @endcan
                                </div>
                            </li>
                        @empty
                            <p class="text-sm text-center text-gray-500">Aucun jalon défini pour ce projet.</p>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Liste des Tâches</h3>
                            @can('update', $project)
                                <a href="{{ route('tasks.create', $project) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Ajouter une tâche
                                </a>
                            @endcan
                        </div>

                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Titre</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3">Début</th>
                                    <th scope="col" class="px-6 py-3">Fin</th>
                                    <th scope="col" class="px-6 py-3 min-w-[150px]">Progression</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($project->tasks->where('parent_id', null) as $task)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $task->title }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @switch($task->status)
                                                    @case('todo') bg-gray-200 text-gray-800 @break
                                                    @case('in_progress') bg-blue-200 text-blue-800 @break
                                                    @case('review') bg-yellow-200 text-yellow-800 @break
                                                    @case('completed') bg-green-200 text-green-800 @break
                                                @endswitch
                                            ">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                        </td>
                                        <td class="px-6 py-4">{{ $task->start_date?->format('d/m/y') ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $task->end_date?->format('d/m/y') ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-2">
                                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $task->progress }}%"></div>
                                                </div>
                                                <span class="text-xs font-medium">{{ $task->progress }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ route('tasks.edit', $task) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                        </td>
                                    </tr>
                                    {{-- Display sub-tasks --}}
                                    @foreach ($task->children as $child)
                                        <tr class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="pl-12 pr-6 py-3 font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                                <span class="mr-2">└</span> {{ $child->title }}
                                            </td>
                                            <td class="px-6 py-3">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @switch($child->status)
                                                        @case('todo') bg-gray-200 text-gray-800 @break
                                                        @case('in_progress') bg-blue-200 text-blue-800 @break
                                                        @case('review') bg-yellow-200 text-yellow-800 @break
                                                        @case('completed') bg-green-200 text-green-800 @break
                                                    @endswitch
                                                ">{{ ucfirst(str_replace('_', ' ', $child->status)) }}</span>
                                            </td>
                                            <td class="px-6 py-3">{{ $child->start_date?->format('d/m/y') ?? '-' }}</td>
                                            <td class="px-6 py-3">{{ $child->end_date?->format('d/m/y') ?? '-' }}</td>
                                            <td class="px-6 py-3">
                                                <div class="flex items-center">
                                                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-2">
                                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $child->progress }}%"></div>
                                                    </div>
                                                    <span class="text-xs font-medium">{{ $child->progress }}%</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                                <a href="{{ route('tasks.edit', $child) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Aucune tâche pour ce projet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pièces Jointes</h3>

                    @can('update', $project)
                        <form action="{{ route('attachments.store', $project) }}" method="POST" enctype="multipart/form-data" class="mb-6 p-4 border rounded-lg dark:border-gray-700">
                            @csrf
                            <label for="attachment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ajouter un fichier</label>
                            <div class="flex items-center">
                                <input type="file" name="attachment" id="attachment" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                <x-primary-button class="ml-4">Envoyer</x-primary-button>
                            </div>
                            @error('attachment')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </form>
                    @endcan

                    <ul class="space-y-2">
                        @forelse ($project->attachments as $attachment)
                            <li class="flex items-center justify-between p-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div>
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        {{ $attachment->original_name }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Ajouté par {{ $attachment->user->name }} le {{ $attachment->created_at->format('d/m/Y') }} ({{ round($attachment->file_size / 1024, 2) }} KB)
                                    </p>
                                </div>
                                @can('update', $project)
                                    <form action="{{ route('attachments.destroy', $attachment) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button>
                                    </form>
                                @endcan
                            </li>
                        @empty
                            <li class="text-sm text-center text-gray-500 dark:text-gray-400 py-4">
                                Aucune pièce jointe pour ce projet.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Communication</h3>

                    <!-- New Comment Form -->
                    <form action="{{ route('comments.store', $project) }}" method="POST">
                        @csrf
                        <textarea id="comment-body" name="body" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Écrire un commentaire..."></textarea>
                        <div class="mt-2 flex justify-between items-center">
                            <div>
                                <x-secondary-button type="button" id="ai-assist-button" class="!py-1 !px-2">
                                    ✨ Aide IA
                                </x-secondary-button>
                                <x-secondary-button type="button" id="ai-reformulate-button" class="!py-1 !px-2 ml-2">
                                    🔄 Reformuler
                                </x-secondary-button>
                            </div>
                            <x-primary-button>
                                Envoyer
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Comments List -->
                    <div class="mt-6 space-y-4">
                        @forelse ($project->comments as $comment)
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm">
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</span>
                                        <span class="text-gray-500 dark:text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                                        {!! nl2br(e($comment->body)) !!}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-center text-gray-500 dark:text-gray-400 mt-4">
                                Aucun commentaire pour le moment.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Brief du Projet</h3>
                        @can('update', $project)
                            <a href="{{ route('projects.brief', $project) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700">
                                Éditer le Brief
                            </a>
                        @endcan
                    </div>
                    @php
                        $brief = (object) ($project->brief_data ?? []);
                    @endphp
                    <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <p><strong class="w-48 inline-block font-semibold text-gray-900 dark:text-gray-100">Problématique:</strong> <span>{{ $brief->problematique ?? 'Non définie' }}</span></p>
                        <p><strong class="w-48 inline-block font-semibold text-gray-900 dark:text-gray-100">Objectifs:</strong> <span>{{ $brief->objectifs ?? 'Non définis' }}</span></p>
                    </div>

                    @can('update', $project)
                        @include('links.partials.form', ['model' => $project])
                    @endcan
                </div>
            </div>

             <div class="flex justify-start">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Retour au Tableau de Bord
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const commentBody = document.getElementById('comment-body');
            const assistButton = document.getElementById('ai-assist-button');
            const reformulateButton = document.getElementById('ai-reformulate-button');

            const callAiAssistant = (type) => {
                const prompt = commentBody.value;
                if (!prompt.trim()) {
                    alert('Veuillez entrer un texte ou une idée de base.');
                    return;
                }

                // Disable buttons and show loading state
                assistButton.disabled = true;
                reformulateButton.disabled = true;
                commentBody.disabled = true;
                commentBody.value = '🤖 IA en cours de réflexion...';

                fetch('{{ route('ai.assist') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ prompt, type })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.suggestion) {
                        commentBody.value = data.suggestion;
                    } else {
                        commentBody.value = 'Désolé, une erreur est survenue.';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    commentBody.value = 'Erreur de communication avec l\'assistant IA.';
                })
                .finally(() => {
                    // Re-enable buttons
                    assistButton.disabled = false;
                    reformulateButton.disabled = false;
                    commentBody.disabled = false;
                });
            };

            assistButton.addEventListener('click', () => callAiAssistant('assist'));
            reformulateButton.addEventListener('click', () => callAiAssistant('reformulate'));
        });
    </script>
    @endpush
</x-app-layout>
