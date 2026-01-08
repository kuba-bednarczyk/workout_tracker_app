<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Baza Ćwiczeń') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">

                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-lg font-medium">Biblioteka ćwiczeń</h3>
                    <a href="{{ route('exercises.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-lg flex items-center gap-2">
                        <span>+</span> Dodaj nowe
                    </a>
                </div>

                @if($exercises->isEmpty())
                    <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-600">
                        <p class="text-gray-500 text-lg">Brak ćwiczeń w bazie.</p>
                        <p class="text-sm text-gray-400 mt-2">Dodaj swoje pierwsze ćwiczenie lub uruchom Seeder!</p>
                    </div>
                @else
                    <div class="space-y-8">
                        @foreach($exercises as $groupName => $groupExercises)

                            <div class="bg-gray-50 dark:bg-gray-900 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                    <h3 class="font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider flex items-center gap-2">
                                        💪 {{ $groupName }}
                                    </h3>
                                    <span class="text-xs font-mono text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-2 py-1 rounded-md border border-gray-200 dark:border-gray-600">
                                        {{ $groupExercises->count() }} ćwiczeń
                                    </span>
                                </div>

                                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($groupExercises as $exercise)
                                        <div class="p-4 flex justify-between items-center hover:bg-white dark:hover:bg-gray-800 transition-colors group">

                                            <div class="flex items-center gap-3">
                                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $exercise->name }}</span>

                                                @if($exercise->user_id)
                                                    <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/30 px-2 py-0.5 rounded border border-indigo-200 dark:border-indigo-800">
                                                        TWOJE
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-gray-400 border border-gray-300 dark:border-gray-600 px-1.5 py-0.5 rounded" title="Ćwiczenie systemowe">
                                                        SYSTEM
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-4 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                                {{-- <a href="#" class="text-sm text-gray-500 hover:text-blue-400">Edytuj</a> --}}

                                                @if($exercise->user_id == auth()->id())
                                                    <form action="{{ route('exercises.destroy', $exercise->id) }}" method="POST" onsubmit="return confirm('Usunąć ćwiczenie {{ $exercise->name }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="text-sm text-red-400 hover:text-red-600 font-medium">Usuń</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
