<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Historia Treningów') }}
            </h2>
            <a href="{{ route('workouts.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-md transition">
                + Dodaj Nowy
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">

                <form method="GET" action="{{ route('workouts.history') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4" x-data="{}">

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nazwa treningu</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Zacznij pisać..."
                                   @input.debounce.500ms="$el.form.submit()"
                                   {{request('search') ? 'autofocus' : ''}}
                                   onfocus="var val=this.value; this.value=''; this.value=val;"
                                   class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">
                            <div class="absolute left-3 top-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Miesiąc</label>
                        <input type="month" name="month" value="{{ request('month') }}"
                               @change="$el.form.submit()"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 cursor-pointer">
                    </div>

                    <div class="flex items-end">
                        @if(request('search') || request('month'))
                            <a href="{{ route('workouts.history') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 px-4 rounded-lg transition text-center flex items-center justify-center gap-2">
                                ✕ Wyczyść
                            </a>
                        @endif
                    </div>
                </form>

                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kiedy?</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Trening</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Objętość</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Opcje</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($workouts as $workout)
                            <tr class="hover:bg-indigo-50 transition group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{----}}
                                        {{ \Carbon\Carbon::parse($workout->date)->translatedFormat('F Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($workout->date)->format('d.m (D) • H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-base font-bold text-gray-800 group-hover:text-indigo-600 transition">
                                        {{ $workout->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="bg-gray-100 px-2 py-1 rounded text-xs font-bold text-gray-600 border border-gray-200">
                                            {{ $workout->workout_sets_count }} serii
                                        </span>
                                </td>
                                {{-- przyciski do edycji/usuwania--}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2 items-center">
                                    <a href="{{ route('workouts.show', $workout->id) }}"
                                       class="group flex items-center gap-1 border border-indigo-600 text-indigo-600 bg-white hover:bg-indigo-600 hover:text-white transition-all duration-200 px-3 py-1.5 rounded-md text-xs uppercase tracking-wider font-bold shadow-sm">
                                        <span>Szczegóły</span>
                                    </a>

                                    <a href="{{ route('workouts.edit', $workout->id) }}"
                                       class="group flex items-center gap-1 border border-amber-500 text-amber-600 bg-white hover:bg-amber-500 hover:text-white transition-all duration-200 px-3 py-1.5 rounded-md text-xs uppercase tracking-wider font-bold shadow-sm">
                                        <span class="group-hover:text-white">✏️</span>
                                        <span>Info</span>
                                    </a>

                                    <form action="{{ route('workouts.destroy', $workout->id) }}" method="POST"
                                          onsubmit="return confirm('Czy na pewno chcesz usunąć ten trening? Wszystkie serie zostaną utracone.')"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="group flex items-center gap-1 border border-red-500 text-red-600 bg-white hover:bg-red-600 hover:text-white transition-all duration-200 px-3 py-1.5 rounded-md text-xs uppercase tracking-wider font-bold shadow-sm">
                                            <span class="group-hover:text-white">🗑️</span>
                                            <span>Usuń</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <span class="text-2xl mb-2">🔍</span>
                                        <p class="font-medium">Nie znaleziono treningów.</p>
                                        <p class="text-xs mt-1">Spróbuj zmienić miesiąc lub nazwę.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $workouts->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
