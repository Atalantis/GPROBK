<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer un nouveau projet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Titre du Projet')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Student -->
                        <div class="mt-4">
                            <x-input-label for="student_id" :value="__('Attribuer à l\'étudiant')" />
                            <select id="student_id" name="student_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Choisir un étudiant --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Statut')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Brouillon (Draft)</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif (Active)</option>
                                <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>En revue (Review)</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Terminé (Completed)</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Start Date -->
                            <div>
                                <x-input-label for="start_date" :value="__('Date de début')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <x-input-label for="end_date" :value="__('Date de fin')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mt-4">
                            <x-input-label for="categories" :value="__('Catégories')" />
                            <select name="categories[]" id="categories" multiple class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm h-32">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(in_array($category->id, old('categories', [])))>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @include('projects.partials.custom-fields-form', ['customFields' => $customFields])

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Créer le projet') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
