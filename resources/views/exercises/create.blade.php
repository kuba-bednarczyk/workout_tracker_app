<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dodaj Nowe Ćwiczenie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm sm:rounded-xl border border-gray-200">

                <form action="{{ route('exercises.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            Nazwa ćwiczenia
                        </label>
                        <input type="text" name="name" placeholder="np. Wyciskanie sztangielek"
                               class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 p-3 shadow-sm transition" required>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            Grupa mięśniowa
                        </label>
                        <select name="muscle_group_id" class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 p-3 shadow-sm transition">
                            @foreach($muscleGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition transform hover:scale-[1.02]">
                            Zapisz ćwiczenie
                        </button>
                        <a href="{{ route('exercises.index') }}" class="text-gray-500 hover:text-gray-700 font-medium px-4">
                            Anuluj
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
