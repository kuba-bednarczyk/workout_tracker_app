<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
{{ $workout->name }} <span class="text-gray-500 text-sm ml-2">({{ $workout->date }})</span>
</h2>
<a href="{{ route('dashboard') }}" class="text-indigo-400 hover:text-indigo-300">← Powrót do listy</a>
</div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1">
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 sticky top-6">
                <h3 class="text-lg font-bold text-white mb-6 uppercase tracking-widest text-sm">Dodaj serię</h3>

                <form action="{{ route('workout_sets.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="workout_id" value="{{ $workout->id }}">

                    <div class="mb-4">
                        <label class="block text-sm text-gray-400 mb-2">Ćwiczenie</label>
                        <select name="exercise_id" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white p-3">
                            @foreach($exercises as $exercise)
                                <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Ciężar (kg)</label>
                            <input type="number" step="0.5" name="weight" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white p-3" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Powtórzenia</label>
                            <input type="number" name="reps" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white p-3" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition shadow-lg uppercase tracking-widest">
                        Zapisz serię
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">
                <h3 class="text-lg font-bold text-white mb-6 uppercase tracking-widest text-sm">Twoja sesja</h3>

                @foreach($workout->workoutSets->groupBy('exercise_id') as $exerciseId => $sets)
                    <div class="mb-6 bg-gray-900 rounded-xl p-4 border border-gray-700">
                        <h4 class="font-bold text-indigo-400 mb-3">{{ $sets->first()->exercise->name }}</h4>
                        <div class="space-y-2">
                            @foreach($sets as $index => $set)
                                <div class="flex justify-between items-center bg-gray-800 p-3 rounded-lg group">
                                    <span class="text-gray-400 text-sm font-bold">SERIA {{ $index + 1 }}</span>
                                    <div class="flex items-center gap-4">
                                        <span class="text-white font-mono tracking-tighter">{{ $set->weight }}kg x {{ $set->reps }}</span>

                                        <form action="{{ route('workout_sets.destroy', $set->id) }}" method="POST" onsubmit="return confirm('Usunąć tę serię?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                @if($workout->workoutSets->isEmpty())
                    <div class="text-center py-20">
                        <p class="text-gray-500 italic">Brak zapisanych serii. Wybierz ćwiczenie po lewej i zacznij logować!</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
</x-app-layout>
