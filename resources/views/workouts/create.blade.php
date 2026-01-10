<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Rozpocznij Nowy Trening') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl border border-gray-200 dark:border-gray-700 p-8">

                <form action="{{ route('workouts.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-400 mb-2 uppercase tracking-wider">
                            Nazwa sesji
                        </label>
                        <input type="text" name="name" id="name" placeholder="np. Klatka i Barki"
                               class="w-full p-4 rounded-xl border-gray-700 bg-gray-900 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 shadow-inner transition"
                               required>
                    </div>

                    <div class="mb-10">
                        <label for="date" class="block text-sm font-medium text-gray-400 mb-2 uppercase tracking-wider">
                            Data i Godzina treningu
                        </label>
                        <input type="datetime-local" name="date" id="date"
                               value="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full p-4 rounded-xl border-gray-700 bg-gray-900 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 shadow-inner transition"
                               required>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-4 rounded-xl shadow-lg transition duration-200 ease-in-out transform hover:scale-[1.02] active:scale-95 uppercase tracking-widest">
                        Dalej: Dodaj ćwiczenia →
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
