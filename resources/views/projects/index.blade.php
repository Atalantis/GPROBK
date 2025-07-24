<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tableau de Bord') }}
            </h2>
            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-insuractio-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-insuractio-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-insuractio-primary transition ease-in-out duration-150">
                    Créer un projet
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 border border-green-400 text-green-700 dark:text-green-200 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total des Projets</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Projets Actifs</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-red-500 dark:text-red-400 truncate">Tâches en Retard</h3>
                    <p class="mt-1 text-3xl font-semibold text-red-600 dark:text-red-300">{{ $stats['overdue_tasks'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Projects List -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Liste des Projets</h3>
                            <div class="flex space-x-2">
                                <a href="{{ route('dashboard') }}" class="{{ !request('filter.status') ? 'text-blue-600 font-bold' : '' }} text-sm hover:text-blue-500">Tous</a>
                                <a href="{{ route('dashboard', ['filter[status]' => 'active']) }}" class="{{ request('filter.status') == 'active' ? 'text-blue-600 font-bold' : '' }} text-sm hover:text-blue-500">Actifs</a>
                                <a href="{{ route('dashboard', ['filter[status]' => 'review']) }}" class="{{ request('filter.status') == 'review' ? 'text-blue-600 font-bold' : '' }} text-sm hover:text-blue-500">En Revue</a>
                                <a href="{{ route('dashboard', ['filter[status]' => 'completed']) }}" class="{{ request('filter.status') == 'completed' ? 'text-blue-600 font-bold' : '' }} text-sm hover:text-blue-500">Terminés</a>
                            </div>
                        </div>
                        <div class="mt-4 relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Titre du Projet</th>
                                        @if(auth()->user()->role === 'professeur')
                                            <th scope="col" class="px-6 py-3">Étudiant</th>
                                        @endif
                                        <th scope="col" class="px-6 py-3">Statut</th>
                                        <th scope="col" class="px-6 py-3">Progression</th>
                                        <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($projects as $project)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $project->title }}</td>
                                            @if(auth()->user()->role === 'professeur')
                                                <td class="px-6 py-4">{{ $project->student->name }}</td>
                                            @endif
                                            <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white
                                                    @switch($project->status)
                                                        @case('draft') bg-gray-500 @break
                                                        @case('active') bg-insuractio-secondary @break
                                                        @case('review') bg-insuractio-warning @break
                                                        @case('completed') bg-insuractio-success @break
                                                    @endswitch
                                                ">{{ ucfirst($project->status) }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $totalTasks = $project->tasks->count();
                                                    $completedTasks = $project->tasks->where('status', 'completed')->count();
                                                    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                @endphp
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                                <a href="{{ route('projects.show', $project) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Voir</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->role === 'professeur' ? 5 : 4 }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                Aucun projet à afficher.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Répartition des Projets</h3>
                        <div class="mt-4">
                            <canvas id="projectStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('projectStatusChart').getContext('2d');
            const projectStatusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Projets par Statut',
                        data: @json($chartData['data']),
                        backgroundColor: [
                            '#6B7280', // gray for draft
                            '#4F46E5', // indigo for active
                            '#F59E0B', // amber for review
                            '#10B981'  // emerald for completed
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: document.documentElement.classList.contains('dark') ? 'white' : 'black'
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
