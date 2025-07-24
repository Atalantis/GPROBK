<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Édition du Brief : {{ $project->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('projects.brief.update', $project) }}">
                        @csrf
                        @method('PATCH')

                        @php
                            $brief = (object) ($project->brief_data ?? []);
                        @endphp

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="problematique" :value="__('Problématique')" />
                                <textarea id="problematique" name="problematique" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('problematique', $brief->problematique ?? '') }}</textarea>
                            </div>

                            <div>
                                <x-input-label for="objectifs" :value="__('Objectifs')" />
                                <textarea id="objectifs" name="objectifs" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('objectifs', $brief->objectifs ?? '') }}</textarea>
                            </div>

                            <div>
                                <x-input-label for="methodologie" :value="__('Méthodologie')" />
                                <textarea id="methodologie" name="methodologie" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('methodologie', $brief->methodologie ?? '') }}</textarea>
                            </div>

                            <div>
                                <x-input-label for="planning_previsionnel" :value="__('Planning Prévisionnel')" />
                                <textarea id="planning_previsionnel" name="planning_previsionnel" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('planning_previsionnel', $brief->planning_previsionnel ?? '') }}</textarea>
                            </div>

                            <div>
                                <x-input-label for="livrables_attendus" :value="__('Livrables Attendus')" />
                                <textarea id="livrables_attendus" name="livrables_attendus" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('livrables_attendus', $brief->livrables_attendus ?? '') }}</textarea>
                            </div>

                             <div>
                                <x-input-label for="bibliographie" :value="__('Bibliographie (une référence par ligne)')" />
                                <textarea id="bibliographie" name="bibliographie" rows="5" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('bibliographie', $brief->bibliographie ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('projects.show', $project) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Annuler
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Sauvegarder le Brief') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
