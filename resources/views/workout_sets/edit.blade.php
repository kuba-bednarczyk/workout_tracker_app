<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edycja Serii: <span class="text-indigo-600">{{ $set->exercise->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-200 p-8">

                <form action="{{ route('workout_sets.update', $set->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="mb-4">
                        <label class="block text-gray-600 text-xs font-bold uppercase mb-2">Ciężar (kg)</label>
                        <input type="number" step="0.5" name="weight" value="{{ $set->weight }}"
                               class="w-full rounded-lg border-gray-300 p-3 text-lg font-mono font-bold bg-white text-gray-900 focus:ring-indigo-500 focus:border-indigo-500"
                               required>
                    </div>

                    <div class="mb-8">
                        <label class="block text-gray-600 text-xs font-bold uppercase mb-2">Powtórzenia</label>
                        <input type="number" name="reps" value="{{ $set->reps }}"
                               class="w-full rounded-lg border-gray-300 p-3 text-lg font-mono font-bold bg-white text-gray-900 focus:ring-indigo-500 focus:border-indigo-500"
                               required>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow transition transform active:scale-95">
                            Zapisz zmiany
                        </button>

                        <a href="{{ route('workouts.show', $set->workout_id) }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-lg text-center transition">
                            Anuluj
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
