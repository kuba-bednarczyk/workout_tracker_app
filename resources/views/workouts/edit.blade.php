<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edycja {{ $workout->is_template ? 'Planu' : 'Treningu' }}: <span class="text-indigo-600">{{ $workout->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Lewa kolumna: Formularz edycji --}}
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-8">

                        <form action="{{ route('workouts.update', $workout->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-6">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    {{ $workout->is_template ? 'Nazwa planu' : 'Nazwa sesji' }}
                                </label>
                                <input type="text" name="name" id="name"
                                       value="{{ old('name', $workout->name) }}"
                                       class="w-full p-3 rounded-lg border-gray-300 focus:ring-indigo-500 bg-white text-gray-900" required>
                            </div>

                            @if(!$workout->is_template)
                                <div class="mb-8">
                                    <label for="date" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Data i Godzina</label>
                                    <input type="datetime-local" name="date" id="date"
                                           value="{{ \Carbon\Carbon::parse($workout->date)->format('Y-m-d\TH:i') }}"
                                           class="w-full p-3 rounded-lg border-gray-300 focus:ring-indigo-500 bg-white text-gray-900" required>
                                </div>
                            @endif

                            <div class="flex gap-4">
                                <button type="submit" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg shadow-md transition">
                                    Zapisz zmiany
                                </button>
                                <a href="{{ $workout->is_templates ? route('workouts.templates') : route('workouts.show', $workout->id) }}"
                                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 px-4 rounded-lg text-center transition">
                                    Anuluj
                                </a>
                            </div>
                        </form>

                    </div>
                </div>

                {{-- Prawa kolumna: Edycja ćwiczeń --}}
                @if($workout->is_template)
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-8">
                            <h3 class="text-lg font-bold text-gray-800 mb-6 uppercase tracking-wider">
                                Ćwiczenia w planie
                            </h3>

                            @if($workout->workoutSets->isEmpty())
                                <div class="flex flex-col items-center justify-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center">
                                    <div class="text-4xl mb-4">📝</div>
                                    <h3 class="text-gray-900 font-bold mb-1">Pusty plan</h3>
                                    <p class="text-gray-500 text-sm">Przejdź do widoku szczegółów, aby dodać ćwiczenia</p>
                                </div>
                            @else
                                <div class="space-y-4 mb-6">
                                    @foreach($workout->workoutSets->groupBy('exercise_id') as $exerciseId => $sets)
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                            <div class="flex justify-between items-center mb-3">
                                                <h4 class="font-bold text-gray-800">{{ $sets->first()->exercise->name }}</h4>
                                                <span class="text-xs bg-white border border-gray-200 text-gray-500 px-2 py-1 rounded-full">
                                                {{ $sets->count() }} serii
                                            </span>
                                            </div>

                                            <div class="space-y-2">
                                                @foreach($sets as $set)
                                                    <div class="flex justify-between items-center bg-white p-2 rounded border border-gray-200">
                                                    <span class="text-sm text-gray-700">
                                                        {{ $set->weight }} kg × {{ $set->reps }} powt.
                                                    </span>
                                                        <form action="{{ route('workout_sets.destroy', $set->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-gray-400 hover:text-red-600 text-sm transition">
                                                                🗑️
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <a href="{{ route('workouts.show', $workout->id) }}"
                                   class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                    Edytuj ćwiczenia
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
