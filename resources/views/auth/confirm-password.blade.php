<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="font-extrabold text-2xl text-gray-900 mb-2 tracking-tight">
            Potwierdź dostęp
        </h1>
        <div class="mb-4 text-sm text-gray-500 leading-relaxed">
            {{ __('To jest bezpieczna strefa. Potwierdź swoje hasło, aby kontynuować.') }}
        </div>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" value="Hasło" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />

            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md transition transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                {{ __('Potwierdź') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
