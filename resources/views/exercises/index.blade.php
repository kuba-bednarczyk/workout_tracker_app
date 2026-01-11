<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Baza Ćwiczeń') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 border border-gray-200">

                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-lg font-bold text-gray-800">Biblioteka</h3>
                    <a href="{{ route('exercises.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-md flex items-center gap-2">
                        <span>+</span> Dodaj nowe
                    </a>
                </div>

                @if($exercises->isEmpty())
                    <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-500 text-lg font-medium">Brak ćwiczeń w bazie.</p>
                        <p class="text-sm text-gray-400 mt-1">Dodaj swoje pierwsze ćwiczenie!</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-8">
                        @foreach($exercises as $groupName => $groupExercises)

                            <div class="bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2 text-sm">
                                        💪 {{ $groupName }}
                                    </h3>
                                    <span class="text-xs font-mono text-gray-500 bg-white px-2 py-1 rounded-md border border-gray-200">
                                        {{ $groupExercises->count() }} szt.
                                    </span>
                                </div>

                                <div class="divide-y divide-gray-100">
                                    @foreach($groupExercises as $exercise)
                                        <div class="p-4 flex justify-between items-center hover:bg-indigo-50 transition-colors group">

                                            <div class="flex items-center gap-3">
                                                <span class="font-medium text-gray-900">{{ $exercise->name }}</span>

                                                @if($exercise->user_id)
                                                    <span class="text-[10px] font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded border border-indigo-200">
                                                        TWOJE
                                                    </span>
                                                @else
                                                    <span class="text-[10px] font-bold text-gray-500 bg-gray-100 border border-gray-200 px-1.5 py-0.5 rounded" title="Ćwiczenie systemowe">
                                                        SYSTEM
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-4 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                                @if($exercise->user_id == auth()->id())
                                                    <a href="{{ route('exercises.edit', $exercise) }}"
                                                       class="text-sm text-indigo-600 hover:text-indigo-900 font-bold transition-colors">
                                                        Edytuj
                                                    </a>
                                                    <form action="{{ route('exercises.destroy', $exercise->id) }}" method="POST" onsubmit="return confirm('Usunąć ćwiczenie {{ $exercise->name }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="text-sm text-red-400 hover:text-red-600 font-bold transition-colors">Usuń</button>
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
