<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $isTemplate ? __('Kreator Planu Treningowego') : __('Rozpocznij Nowy Trening') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-8">

                <form action="{{ route('workouts.store') }}" method="POST">
                    @csrf

                    {{-- ukryte pole żeby kontroler wiedział że tworzymy plan treningowy, a nie trening --}}
                    @if($isTemplate)
                        <input type="hidden" name="is_template" value="1">
                    @endif

                    {{-- Jeżeli przyszło id szablonu w url to wstawiamy w ukryty input, żeby kontroler mógł skopiować serie  --}}
                    @if(isset($sourceTemplate))
                        <input type="hidden" name="source_template_id" value="{{ $sourceTemplate->id }}">
                    @endif

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">
                            {{ $isTemplate ? 'Plan treningowy' : 'Sesja treningowa' }}
                        </label>
                        <input type="text" name="name" id="name"
                               value="{{ isset($sourceTemplate) ? $sourceTemplate->name : '' }}"
                               placeholder="{{ $isTemplate ? 'np. Plan FBW A' : 'np. Klatka i Barki' }}"
                               class="w-full p-3 rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition"
                               required>
                    </div>

                    {{-- Datę pokazujemy TYLKO jeśli to zwykły trening (Szablony nie mają daty )--}}
                    @if(!$isTemplate)
                        <div class="mb-8">
                            <label for="date" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">
                                Data i Godzina
                            </label>
                            <input type="datetime-local" name="date" id="date"
                                   value="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="w-full p-3 rounded-lg border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition"
                                   required>
                        </div>
                    @endif

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-200 ease-in-out transform hover:scale-[1.01] uppercase tracking-wide">
                        {{-- zmiana tekstu przycisku (plan/trening) --}}
                        {{ $isTemplate ? 'Utwórz Pusty Plan' : 'Dalej: Dodaj ćwiczenia →' }}
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
