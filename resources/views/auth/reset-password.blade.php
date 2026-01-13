<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="font-extrabold text-2xl text-gray-900 mb-2 tracking-tight">
            Ustaw nowe hasło
        </h1>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="Email" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Nowe Hasło" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />
            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Potwierdź Hasło" class="uppercase tracking-wide text-xs font-bold text-gray-700 mb-1" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg border-gray-300 p-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md transition transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                {{ __('Zresetuj hasło') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
