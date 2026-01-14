<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Moje Plany Treningowe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header sekcji --}}
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">Wybierz szablon, aby szybko rozpocząć trening.</p>

                {{-- Przycisk do tworzenia czystego szablonu --}}
                <a href="{{ route('workouts.create', ['is_template' => 1]) }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm">
                    + Stwórz nowy plan
                </a>
            </div>

            {{-- sprawdzenie czy w ogole sa jakies szablony, jak nie to wyswietlam komunikat --}}
            @if($templates->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center border border-dashed border-gray-300">
                    <h3 class="text-lg font-medium text-gray-900">Brak szablonów</h3>
                    <p class="mt-2 text-gray-500">Zapisz udany trening jako szablon w Historii, aby zobaczyć go tutaj.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{--  Grid z kartami szablonow --}}
                    @foreach($templates as $template)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        {{-- Nazwa szablonu --}}
                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $template->name ?: 'Plan bez nazwy' }}
                                        </h3>
                                        <span class="text-xs text-gray-500">
                                            {{ $template->workoutSets->count() }} ćwiczeń
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('workouts.edit', $template->id) }}"
                                           class="text-gray-400 hover:text-yellow-600 p-2 transition-colors"
                                           title="Edytuj szablon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('workouts.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Usunąć ten plan?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-2" title="Usuń plan">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                </div>

                                {{-- Podgląd ćwiczeń: tylko unikalne, żeby nie było dublowania --}}
                                <div class="mb-6">
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        @foreach($template->workoutSets->unique('exercise_id')->take(4) as $set)
                                            <li class="flex items-center">
                                                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></span>
                                                {{ $set->exercise->name }}
                                            </li>
                                        @endforeach
                                        @if($template->workoutSets->unique('exercise_id')->count() > 4)
                                            <li class="text-xs text-gray-400 pl-3.5">+ {{ $template->workoutSets->unique('exercise_id')->count() - 4 }} więcej...</li>
                                        @endif
                                    </ul>
                                </div>

                                {{-- NAJWAZNIEJSZE: id szablonu podaje w linku, żeby w formularzu wiedzieć co kopiować --}}
                                <a href="{{ route('workouts.create', ['source_template_id' => $template->id]) }}"
                                   class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                    Użyj tego planu
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
