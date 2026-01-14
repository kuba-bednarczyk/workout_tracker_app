<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'WorkoutTracker') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans selection:bg-indigo-500 selection:text-white">

<div class="min-h-screen flex flex-col justify-between">

    {{-- NAVBAR --}}
    <nav class="w-full py-6 px-6 lg:px-8 flex justify-between items-center max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <span class="font-black text-2xl tracking-tighter text-gray-900">
                Workout
                <span class="text-indigo-600"> Tracker</span>
            </span>
        </div>

        <div class="flex gap-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition">
                        Wróć do Panelu
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900 transition">
                        Zaloguj się
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition">
                            Załóż konto
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="max-w-5xl mx-auto text-center space-y-8">

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-gray-200 text-indigo-700 text-xs font-bold uppercase tracking-wide shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-indigo-600"></span>
                Wersja 1.0
            </div>

            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 leading-tight">
                Śledź swoje treningi.<br>
                <span class="text-indigo-600">Osiągaj cele.</span>
            </h1>

            <p class="text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed">
                Aplikacja do planowania treningów i śledzenia progresu siłowego.
                Twórz szablony, zapisuj serie i analizuj historię treningów.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Przejdź do aplikacji →
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Rozpocznij teraz
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-3.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-xl font-bold text-lg shadow-sm transition">
                        Zaloguj się
                    </a>
                @endauth
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left mt-16">

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-100 transition">
                    <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-xl mb-4">📝</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Szablony Treningowe</h3>
                    <p class="text-gray-500 text-sm">Twórz gotowe plany treningowe i uruchamiaj je jednym kliknięciem.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-green-100 transition">
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-xl mb-4">📈</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Historia i Progres</h3>
                    <p class="text-gray-500 text-sm">Pełny wgląd w historię. Sprawdzaj, jak rosną Twoje wyniki z tygodnia na tydzień.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-blue-100 transition">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-xl mb-4">🏋️‍♀️</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Baza Ćwiczeń</h3>
                    <p class="text-gray-500 text-sm">Zarządzaj swoją bazą ćwiczeń. Dodawaj własne, usuwaj i modyfikuj.</p>
                </div>

            </div>
        </div>
    </main>

    <footer class="py-8 text-center text-gray-400 text-sm">
        &copy; {{ date('Y') }} Jakub Bednarczyk. WorkoutTracker
    </footer>
</div>
</body>
</html>
