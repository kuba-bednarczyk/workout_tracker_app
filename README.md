# Projekt zaliczeniowy z laboratorium "Programowanie aplikacji internetowych"

## Tematyka projektu: Workout Tracker (Planer treningów na siłownię)
Aplikacja internetowa do śledzenia postępów na siłowni. Umożliwia rejestrowanie treningów, zarządzanie planami treningowymi oraz analizę postępów.

## Autor
Jakub Bednarczyk

## Funkcjonalności
- **Uwierzytelnianie:** Logowanie, rejestracja, reset hasła.
- **Treningi:** Tworzenie sesji treningowych, dodawanie serii (ciężar/powtórzenia).
- **System Szablonów:** Możliwość stworzenia planu treningowego i szybkiego rozpoczęcia treningu na jego podstawie (kopiowanie serii).
- **Baza Ćwiczeń:** Hybrydowy system ćwiczeń – ćwiczenia systemowe (dla wszystkich) oraz prywatne ćwiczenia użytkownika.
- **Dashboard:** Statystyki tygodniowe, miesięczne oraz lista ostatnich aktywności.
- **Historia:** Przeglądanie i filtrowanie odbytych treningów.
- **Zabezpieczenia:** Pełna walidacja danych i autoryzacja (użytkownik widzi i edytuje tylko swoje dane).

## Narzędzia i technologie
- **Backend:** Laravel 12, PHP 8.2
- **Baza danych:** SQLite 
- **Frontend:** Laravel Blade, TailwindCSS
- **Inne:** Laravel Breeze (Auth), Carbon (Data/Czas)

## Wymagania
Wersje programów wykorzystane do tworzenia aplikacji:
- PHP 8.2.x
- Laravel Framework 12.45.2
- Composer 2.9.3
- Node.js 24.12.0
- NPM 10.8.0

## Uruchomienie aplikacji
1. **Przygotowanie plików:**
   Wypakuj archiwum z projektem do dowolnego folderu lub sklonuj repozytorium za pomocą komendy:
    ```bash
    git clone https://github.com/kuba-bednarczyk/workout-tracker-app/
   cd workout_tracker_app
   ```

2. **Instalacja zależności:**
   Otwórz terminal w folderze projektu i wykonaj komendy:
   ```bash
   composer install
   npm install
   ```
3. **Konfiguracja środowiska:** Skopiuj plik `.env.example` do `.env`: 

    ```bash
    cp .env.example .env
    ```
   _(W systemie Windows można ręcznie skopiować w eksploratorze i zmienić nazwę na .env)_

    Następnie wygeneruj klucz szyfrowania aplikacji:
    ```bash
   php artisan key:generate
    ```

4. **Baza danych:**
   Aplikacja skonfigurowana jest pod SQLite, więc nie wymaga zewnętrznego serwera MySQL. Stwórz plik bazy danych o nazwie database.sqlite w folderze /database (jeśli nie istnieje).

    Lub wpisz w terminalu (w folderze projektu):
    - Windows (PowerShell): `New-Item database/database.sqlite`
    - Linux/Mac/Git Bash: `touch database/database.sqlite `


5. **Migracja i Seeder:**
Uruchom migracje, aby utworzyć tabele oraz wypełnić przykładowymi danymi:
    ```bash
    php artisan migrate:fresh --seed
    ```
6. **Start serwera**

    W jednym oknie terminalu uruchom serwer PHP:
    ```bash
    php artisan serve
    ```
   
    W drugim oknie terminalu uruchom kompilator zasobów (dla styli CSS):
    
    ```bash
   npm run dev
    ```
7. **Dostęp:**

    Otwórz przeglądarkę pod adresem: `http://localhost:8000`

### Konta testowe
W procesie seedowania tworzone jest automatycznie konto z pełną historią i ćwiczeniami:
- **Użytkownik Admin:** 
  - **Login:** admin@example.com
  - **Hasło:** admin

### Uwagi
1. Problem z uruchomieniem serwera (zajęty port): jeśli komenda `php artisan serve` zwraca błąd `Failed to listen on 127.0.0.1:8000` (port zajęty), możesz wymusić uruchomienie aplikacji na innym porcie, np. 8080:
    ```bash
   php artisan serve --port=8080
    ```
2. W przypadku problemów z wyglądem strony (brak stylów), upewnij się, że komenda `npm run dev` jest uruchomiona w tle i wtyczki typu AdBlock są wyłączone.

3. Aplikacja wykorzystuje mechanizm `SoftDeletes` dla modelu `Exercise`. Usunięcie ćwiczenia z bazy nie powoduje błędów w historii odbytych treningów.
    
    
