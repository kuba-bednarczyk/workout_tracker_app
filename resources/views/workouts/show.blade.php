<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $workout->name }}

                @if($workout->is_template)
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-2 py-1 rounded ml-2">
                        PLAN TRENINGOWY
                    </span>
                @else
                    <span class="text-gray-500 text-sm ml-2 font-normal">
                        ({{ \Carbon\Carbon::parse($workout->date)->format('d.m.Y H:i') }})
                    </span>
                @endif
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('workouts.edit', $workout->id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold transition">
                    ✏️ Edytuj info
                </a>
                <span class="text-gray-300">|</span>
                @if($workout->is_template)
                    <a href="{{ route('workouts.templates') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition">
                        ← Powrót
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition">
                        ← Powrót
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-200 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 uppercase tracking-wider text-sm border-b pb-2 border-gray-200">
                        Dodaj serię
                    </h3>

                    <form action="{{ route('workout_sets.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="workout_id" value="{{ $workout->id }}">

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ćwiczenie</label>
                            <select name="exercise_id" class="w-full border-gray-300 rounded-lg text-gray-900 p-2.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                                @foreach($exercises as $exercise)
                                    <option value="{{ $exercise->id }}" {{ old('exercise_id') == $exercise->id ? 'selected' : '' }}>
                                        {{ $exercise->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ciężar (kg)</label>
                                <input type="number" step="0.5" name="weight" placeholder="0" class="w-full border-gray-300 rounded-lg text-gray-900 p-2.5 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                @error('weight')
                                    <span class="text-red-500 text-sm">{{$message}}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Powtórzenia</label>
                                <input type="number" name="reps" placeholder="0" class="w-full border-gray-300 rounded-lg text-gray-900 p-2.5 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                @error('reps')
                                    <span class="text-red-500 text-sm">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition shadow-md uppercase tracking-wide text-sm flex justify-center items-center gap-2">
                            <span>+</span> Zapisz serię
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 min-h-[400px]">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 uppercase tracking-wider text-sm flex justify-between items-center">
                        <span>Zapisane serie</span>
                        <span class="text-xs font-normal text-gray-400 normal-case">
                            Łączna objętość: {{ $workout->workoutSets->sum(fn($s) => $s->weight * $s->reps) }} kg
                        </span>
                    </h3>

                    @forelse($workout->workoutSets->groupBy('exercise_id') as $exerciseId => $sets)
                        <div class="mb-6 bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                                <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                    🏋️‍♀️ {{ $sets->first()->exercise->name }}
                                </h4>
                                <span class="bg-white border border-gray-200 text-xs text-gray-500 px-2 py-0.5 rounded-full font-medium">
                                    {{ $sets->count() }} serii
                                </span>
                            </div>

                            <div class="divide-y divide-gray-100">
                                @foreach($sets as $index => $set)
                                    <div class="flex justify-between items-center p-3 hover:bg-indigo-50 transition group">
                                        <div class="flex items-center gap-4">
                                            <span class="text-gray-400 text-xs font-bold w-12 text-center bg-gray-100 rounded py-1">#{{ $index + 1 }}</span>
                                            <span class="text-gray-900 font-mono font-bold text-lg">
                                                {{ $set->weight }} <span class="text-sm text-gray-500 font-normal">kg</span>
                                                <span class="text-gray-300 mx-2">×</span>
                                                {{ $set->reps }} <span class="text-sm text-gray-500 font-normal">powt.</span>
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('workout_sets.edit', $set->id) }}" class="text-gray-400 hover:text-yellow-600 p-2 transition-colors" title="Edytuj serię">
                                                ✏️
                                            </a>

                                            <form action="{{ route('workout_sets.destroy', $set->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 p-2 transition-colors" title="Usuń serię">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center">
                            <div class="text-4xl mb-4">📝</div>
                            <h3 class="text-gray-900 font-bold mb-1">Pusty trening</h3>
                            <p class="text-gray-500 text-sm">Wybierz ćwiczenie z panelu po lewej<br>i dodaj swoją pierwszą serię!</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-8 flex gap-4">
                    @if($workout->is_template)
                        <a href="{{ route('workouts.templates') }}"
                           class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-3 px-4 rounded-xl text-center hover:bg-gray-50 transition shadow-sm uppercase tracking-wide text-sm flex justify-center items-center">
                            Zapisz plan
                        </a>
                        <a href="{{ route('workouts.create', ['source_template_id' => $workout->id]) }}"
                           class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-center transition shadow-md uppercase tracking-wide text-sm flex justify-center items-center gap-2">
                            Rozpocznij trening
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-xl text-center transition shadow-md uppercase tracking-wide text-sm flex justify-center items-center gap-2"
                        >
                            Zakończ trening
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
