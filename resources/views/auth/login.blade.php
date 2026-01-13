<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="font-extrabold text-3xl text-gray-900 mb-2 tracking-tight">
            Zaloguj się
        </h1>
        <p class="text-gray-500 text-sm">
            Witaj ponownie! Wpisz swoje dane.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Email" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Hasło" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />

            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-between items-center mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Zapamiętaj mnie') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition" href="{{ route('password.request') }}">
                    {{ __('Zapomniałeś hasła?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md transition transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                {{ __('Zaloguj się') }}
            </x-primary-button>
        </div>

        <div class="mt-8 text-center text-sm text-gray-500 pt-6 border-t border-gray-100">
            Nie masz jeszcze konta?
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition ml-1">Zarejestruj się</a>
        </div>
    </form>
</x-guest-layout>
