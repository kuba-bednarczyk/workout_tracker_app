<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edytuj ćwiczenie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-4 lg:px-6">
            <div class="bg-white shadow-lg sm:rounded-xl overflow-hidden border border-gray-100">

                <div class="p-8">
                    <div class="border-b border-gray-100 pb-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Edycja: {{ $exercise->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Zaktualizuj dane ćwiczenia.</p>
                    </div>

                    <form action="{{ route('exercises.update', $exercise) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- GRID SYSTEM: Dwie kolumny na dużych ekranach --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                            {{-- 1. Nazwa ćwiczenia --}}
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nazwa ćwiczenia</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', $exercise->name) }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5"
                                       placeholder="np. Wyciskanie sztangi">
                                @error('name')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 2. Partia mięśniowa (SELECT) --}}
                            <div>
                                <label for="muscle_group_id" class="block text-sm font-bold text-gray-700 mb-2">Partia mięśniowa</label>

                                {{-- ZMIANA NAME: teraz to muscle_group_id --}}
                                <select name="muscle_group_id"
                                        id="muscle_group_id"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5">

                                    <option value="" disabled>Wybierz partię...</option>

                                    @foreach($muscleGroups as $group)
                                        <option value="{{ $group->id }}"
                                            {{ (old('muscle_group_id', $exercise->muscle_group_id) == $group->id) ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('muscle_group_id')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Footer z przyciskami --}}
                        <div class="bg-white -mx-8 -mb-8 px-8 py-4 flex items-center justify-end gap-3 border-t border-gray-100 mt-auto">
                            <a href="{{ route('exercises.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Anuluj
                            </a>
                            <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all transform hover:scale-105">
                                Zapisz zmiany
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
