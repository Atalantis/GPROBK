<div class="p-6">
    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $task->title }}</h3>

    <div class="mt-4 space-y-4 text-sm">
        <div>
            <h4 class="font-semibold text-gray-600 dark:text-gray-400">Description</h4>
            <p class="text-gray-800 dark:text-gray-200">{!! nl2br(e($task->description)) ?: 'Aucune description.' !!}</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <h4 class="font-semibold text-gray-600 dark:text-gray-400">Statut</h4>
                <p class="text-gray-800 dark:text-gray-200">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-600 dark:text-gray-400">Priorité</h4>
                <p class="text-gray-800 dark:text-gray-200">{{ ucfirst($task->priority) }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-600 dark:text-gray-400">Date de début</h4>
                <p class="text-gray-800 dark:text-gray-200">{{ $task->start_date?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-600 dark:text-gray-400">Date de fin</h4>
                <p class="text-gray-800 dark:text-gray-200">{{ $task->end_date?->format('d M Y') ?? '-' }}</p>
            </div>
        </div>

        @if($task->categories->isNotEmpty())
        <div>
            <h4 class="font-semibold text-gray-600 dark:text-gray-400">Catégories</h4>
            <div class="flex flex-wrap gap-1 mt-1">
                @foreach($task->categories as $category)
                    <span class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-800 rounded-full">{{ $category->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($task->prerequisites->isNotEmpty())
        <div>
            <h4 class="font-semibold text-gray-600 dark:text-gray-400">Dépend de</h4>
            <ul class="list-disc list-inside">
                @foreach($task->prerequisites as $prereq)
                    <li class="text-gray-800 dark:text-gray-200">{{ $prereq->title }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($task->children->isNotEmpty())
        <div>
            <h4 class="font-semibold text-gray-600 dark:text-gray-400">Sous-tâches</h4>
            <ul class="list-disc list-inside">
                @foreach($task->children as $child)
                    <li class="text-gray-800 dark:text-gray-200">{{ $child->title }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if($task->customFields->isNotEmpty())
            <div class="border-t pt-4">
                <h4 class="font-semibold text-gray-600 dark:text-gray-400 mb-2">Informations Complémentaires</h4>
                @foreach($task->customFields as $field)
                    <div>
                        <strong class="font-semibold text-gray-700 dark:text-gray-300">{{ $field->definition->name }}:</strong>
                        <span class="text-gray-800 dark:text-gray-200">{{ $field->value }}</span>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <div class="mt-6 flex justify-end space-x-2">
        <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md text-xs">
            Modifier la tâche
        </a>
        <x-secondary-button x-on:click="show = false">
            Fermer
        </x-secondary-button>
    </div>
</div>
