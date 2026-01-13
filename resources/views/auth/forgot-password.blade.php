<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="font-extrabold text-2xl text-gray-900 mb-2 tracking-tight">
            Reset Hasła
        </h1>
        <div class="mb-4 text-sm text-gray-500 leading-relaxed">
            {{ __('Zapomniałeś hasła? Żaden problem. Podaj swój adres email, a wyślemy Ci link do zresetowania hasła.') }}
        </div>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Twój Email" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md transition transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                {{ __('Wyślij link resetujący') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">
                ← Wróć do logowania
            </a>
        </div>
    </form>
</x-guest-layout>
