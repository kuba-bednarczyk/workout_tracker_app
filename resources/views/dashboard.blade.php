<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Twoje Treningi') }}
            </h2>
            <a href="{{ route('workouts.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition">
                + Nowy Trening
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($workouts as $workout)
                    <div class="p-6 bg-gray-900 rounded-xl border border-gray-700 text-white shadow-md">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold">{{ $workout->name }}</h3>
                                <p class="text-gray-500 text-sm">{{ $workout->date }}</p>
                            </div>
                        </div>

                        <ul class="text-sm text-gray-400 mb-6 space-y-1">
                            @forelse($workout->workoutSets->groupBy('exercise_id') as $exerciseId => $sets)
                                <li>
                                    <span class="text-indigo-400 font-bold">•</span>
                                    {{ $sets->first()->exercise->name }}: {{ $sets->count() }} serie
                                </li>
                            @empty
                                <li class="italic text-gray-600">Brak serii</li>
                            @endforelse
                        </ul>

                        <div class="flex justify-between items-center">
                            <a href="{{ route('workouts.show', $workout->id) }}" class="text-blue-400 hover:text-blue-300 text-sm font-semibold">
                                Szczegóły / Edytuj →
                            </a>

                            <form action="{{ route('workouts.destroy', $workout->id) }}" method="POST" onsubmit="return confirm('Na pewno usunąć cały trening?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400 text-xs uppercase tracking-tighter">Usuń</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($workouts->isEmpty())
                <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700">
                    <p class="text-gray-500 dark:text-gray-400">Nie masz jeszcze żadnych treningów. Czas na pierwszy wycisk!</p>
                    <a href="{{ route('workouts.create') }}" class="mt-4 inline-block text-indigo-500 hover:text-indigo-400 font-semibold">
                        Stwórz swój pierwszy trening
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
