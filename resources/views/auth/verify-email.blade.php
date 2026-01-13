<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="font-extrabold text-2xl text-gray-900 mb-2 tracking-tight">
            Weryfikacja Email
        </h1>
        <div class="mb-4 text-sm text-gray-500 leading-relaxed">
            {{ __('Dzięki za rejestrację! Zanim zaczniesz, kliknij w link weryfikacyjny, który wysłaliśmy na Twój email.') }}
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-bold text-sm text-green-600 bg-green-50 p-3 rounded-lg text-center border border-green-100">
            {{ __('Nowy link weryfikacyjny został wysłany.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button class="w-full justify-center py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md transition transform hover:-translate-y-0.5 uppercase tracking-wide text-sm">
                    {{ __('Wyślij ponownie') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="w-full bg-white border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-bold py-3.5 px-4 rounded-xl transition uppercase tracking-wide text-sm">
                {{ __('Wyloguj się') }}
            </button>
        </form>
    </div>
</x-guest-layout>
