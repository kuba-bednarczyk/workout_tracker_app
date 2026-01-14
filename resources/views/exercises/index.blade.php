<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 {{ __(' Baza Ćwiczeń') }}
            </h2>
            <a href="{{ route('exercises.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center gap-2">
                <span>+</span> Dodaj Nowe
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">

                {{-- FORMULARZ FILTROWANIA --}}
                <form method="GET" action="{{ route('exercises.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4" x-data="{}">

                    {{-- 1. Szukaj po nazwie --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nazwa ćwiczenia</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Np. Wyciskanie..."
                                    {{-- automatyczne wysylanie co 0,5 sekundy po wpisaniu frazy--}}
                                   @input.debounce.500ms="$el.form.submit()"
                                   onfocus="var val=this.value; this.value=''; this.value=val;"
                                   class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 transition">
                            <div class="absolute left-3 top-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Filtruj po Grupie Mięśniowej (Relacja) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grupa mięśniowa</label>
                        <select name="muscle_group_id" @change="$el.form.submit()" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 cursor-pointer">
                            <option value="">Wszystkie grupy</option>
                            @foreach($muscleGroups as $group)
                                <option value="{{ $group->id }}" {{ request('muscle_group_id') == $group->id ? 'selected' : '' }}>
                                    {{ ucfirst($group->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Przycisk czyszczenia --}}
                    <div class="flex items-end">
                        @if(request('search') || request('muscle_group_id'))
                            <a href="{{ route('exercises.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 px-4 rounded-lg transition text-center flex items-center justify-center gap-2 border border-gray-200">
                                ✕ Wyczyść
                            </a>
                        @endif
                    </div>
                </form>

                {{-- TABELA --}}
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ćwiczenie</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grupa Mięśniowa</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Typ</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Opcje</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($exercises as $exercise)
                            <tr class="hover:bg-indigo-50 transition group">

                                {{-- Nazwa --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-base font-bold text-gray-800 group-hover:text-indigo-600 transition">
                                        {{ $exercise->name }}
                                    </div>
                                </td>

                                {{-- Grupa Mięśniowa (Pobierana z relacji + Kolorowe Badges) --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        // Pobieramy nazwę z relacji (bezpiecznie, gdyby relacja była pusta, dajemy 'Inne')
                                        $groupName = optional($exercise->muscleGroup)->name ?? 'Inne';

                                        // Logika kolorów
                                        $colors = match(strtolower($groupName)) {
                                            'klatka', 'chest' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'plecy', 'back' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'nogi', 'legs' => 'bg-rose-100 text-rose-700 border-rose-200',
                                            'barki', 'shoulders' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'ramiona', 'arms', 'biceps', 'triceps' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'brzuch', 'abs', 'core' => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                                            default => 'bg-gray-100 text-gray-600 border-gray-200'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold border {{ $colors }}">
                                        {{ ucfirst($groupName) }}
                                    </span>
                                </td>

                                {{-- Typ (Systemowe/Twoje) --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($exercise->user_id)
                                        <span class="text-[10px] font-bold text-indigo-600 bg-white border border-indigo-200 px-2 py-1 rounded-full shadow-sm">
                                            TWOJE
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400 bg-gray-50 border border-gray-100 px-2 py-1 rounded-full">
                                            SYSTEM
                                        </span>
                                    @endif
                                </td>

                                {{-- Opcje --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($exercise->user_id == auth()->id())
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('exercises.edit', $exercise) }}"
                                               class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-xs font-bold transition">
                                                Edytuj
                                            </a>

                                            <form action="{{ route('exercises.destroy', $exercise) }}" method="POST"
                                                  onsubmit="return confirm('Usunąć ćwiczenie {{ $exercise->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md text-xs font-bold transition">
                                                    Usuń
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic mr-2">Brak akcji</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <span class="text-3xl mb-3">🏋️</span>
                                        <p class="font-medium text-lg">Brak ćwiczeń.</p>
                                        <p class="text-sm mt-1 text-gray-400">Zmień filtry lub dodaj nowe ćwiczenie.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginacja (Zachowuje filtry przy przechodzeniu stron) --}}
                <div class="mt-6">
                    {{ $exercises->appends(request()->query())->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
