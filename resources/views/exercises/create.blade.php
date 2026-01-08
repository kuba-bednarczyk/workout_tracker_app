<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nowe Ćwiczenie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('exercises.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Nazwa ćwiczenia</label>
                        <input type="text" name="name" class="w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Grupa mięśniowa</label>
                        <select name="muscle_group_id" class="w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-white">
                            @foreach($muscleGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Dodaj do bazy
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
