<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="font-extrabold text-3xl text-gray-900 mb-2 tracking-tight">
            Załóż konto
        </h1>
        <p class="text-gray-500 text-sm">
            Dołącz do nas i zacznij działać.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" value="Imię / Nick" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />
            <x-text-input id="name" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Hasło" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />
            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Potwierdź hasło" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md transition transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                {{ __('Zarejestruj się') }}
            </x-primary-button>
        </div>

        <div class="mt-8 text-center text-sm text-gray-500 pt-6 border-t border-gray-100">
            Masz już konto?
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition ml-1">
                Zaloguj się
            </a>
        </div>
    </form>
</x-guest-layout>
