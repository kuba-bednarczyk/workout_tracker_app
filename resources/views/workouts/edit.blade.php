<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $workout->is_template ? __('Edycja Planu Treningowego') : __('Edycja Treningu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        {{-- Zmieniłem max-w-xl na max-w-2xl, żeby było nieco szerzej, jak chciałeś --}}
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-8">

                <form action="{{ route('workouts.update', $workout->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">
                            {{ $workout->is_template ? 'Nazwa planu' : 'Nazwa sesji' }}
                        </label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $workout->name) }}"
                               class="w-full p-3 rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition"
                               required>
                    </div>

                    {{-- Pole: Data (Tylko dla treningów, ukryte dla szablonów) --}}
                    @if(!$workout->is_template)
                        <div class="mb-8">
                            <label for="date" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">
                                Data i Godzina
                            </label>
                            <input type="datetime-local" name="date" id="date"
                                   value="{{ \Carbon\Carbon::parse($workout->date)->format('Y-m-d\TH:i') }}"
                                   class="w-full p-3 rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition"
                                   required>
                        </div>
                    @endif

                    {{-- Sekcja Przycisków --}}
                    <div class="space-y-3 mt-8 pt-6 border-t border-gray-100">

                        {{-- 1. Główny przycisk: ZAPISZ DANE i WRÓĆ --}}
                        {{-- name="action" value="save" --}}
                        <button type="submit" name="action" value="save"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-200 ease-in-out uppercase tracking-wide flex justify-center items-center gap-2">
                            Zapisz Informacje
                        </button>

                        <div class="grid grid-cols-2 gap-4">

                            {{-- 2. Przycisk: ZAPISZ i IDŹ DO ĆWICZEŃ --}}
                            {{-- To teraz jest button submit, ale wygląda jak tamten link --}}
                            <button type="submit" name="action" value="edit_exercises"
                                    class="flex justify-center items-center gap-2 w-full bg-white border-2 border-indigo-100 text-indigo-700 hover:bg-indigo-50 hover:border-indigo-200 font-bold py-3 px-4 rounded-lg transition duration-200 uppercase tracking-wide text-sm">
                                Edytuj Ćwiczenia
                            </button>

                            {{-- 3. Przycisk: ANULUJ (Zwykły link, bo nie chcemy zapisywać) --}}
                            <a href="{{ $workout->is_template ? route('workouts.templates') : route('workouts.index') }}"
                               class="flex justify-center items-center w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 px-4 rounded-lg transition duration-200 uppercase tracking-wide text-sm">
                                Anuluj
                            </a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
