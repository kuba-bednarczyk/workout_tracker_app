<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Przegląd') }}
            </h2>
            <a href="{{ route('workouts.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition shadow-md flex items-center gap-2">
                <span>+</span> Nowy trening
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-sm mb-1 uppercase tracking-wider font-semibold">W tym miesiącu</div>
                    <div class="text-3xl font-extrabold text-indigo-600">
                        {{ $stats['this_month'] }}
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-sm mb-1 uppercase tracking-wider font-semibold">Łącznie treningów</div>
                    <div class="text-3xl font-extrabold text-gray-900">
                        {{ $stats['total'] }}
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-sm mb-1 uppercase tracking-wider font-semibold">Baza ćwiczeń</div>
                    <div class="text-3xl font-extrabold text-gray-900">
                        {{ $stats['exercises_count'] }}
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-gray-500 text-sm mb-1 uppercase tracking-wider font-semibold">W tym tygodniu</div>
                    <div class="text-3xl font-extrabold text-green-600 flex items-center gap-2">
                        {{ $stats['this_week'] }} <span class="text-lg">🔥</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Ostatnie treningi</h3>
                    <a href="{{ route('workouts.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        Zobacz historię →
                    </a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($workouts as $workout)
                        <a href="{{ route('workouts.show', $workout->id) }}" class="block p-4 hover:bg-gray-50 transition duration-150 ease-in-out group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-start gap-4">
                                    <div class="w-1.5 h-12 rounded-full bg-indigo-500 group-hover:bg-indigo-600 transition-colors"></div>

                                    <div>
                                        <h4 class="font-bold text-gray-900 text-lg group-hover:text-indigo-600 transition-colors">
                                            {{ $workout->name }}
                                        </h4>
                                        <div class="flex items-center gap-3 text-sm text-gray-500 mt-1">
                                            <span>🏋️‍♀️ {{ $workout->workoutSets->unique('exercise_id')->count() }} ćwiczeń</span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span>🔄 {{ $workout->workout_sets_count }} serii</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-gray-900 font-medium">
                                        {{ \Carbon\Carbon::parse($workout->date)->format('d.m') }}
                                        <span class="text-gray-400 text-xs ml-1">({{ \Carbon\Carbon::parse($workout->date)->translatedFormat('D') }})</span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($workout->date)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            Brak treningów. Dodaj pierwszy!
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
