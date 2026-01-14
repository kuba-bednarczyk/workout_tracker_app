<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    // Dashboard: wyświetla statystyki i ostatnie treningi. Pierwsze co widzi uzytkownik po zalogowaniu
    public function index() {
        $userId = auth()->id(); // pobranie id zalogowanego uzytkownika

        // pobieramy 5 ostatnich treningow do widoku dashboard
        $workouts = Workout::where('user_id', auth()->id())
            ->where('is_template', false) // TYLKO odbyte treningi, nie plany treningowe
            ->withCount('workoutSets') // liczba serii (ogólna)
            ->with(['workoutSets']) // do liczenia cwiczen ( w widoku to zliczamy)
            ->orderBy('date', 'desc') // od najnowszych treningow
            ->limit(5) //tylko 5 najnowszych
            ->get();

        // statystyki do dashboardu
        $stats = [
            'this_month' => Workout::where('user_id', $userId)
                ->where('is_template', false)
                //filtrowanie po miesiacu i roku
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->count(),
            'total'=> Workout::where('user_id', $userId) //liczba wszystkich ćwiczeń w bazie
                    ->where('is_template', false)
                    ->count(),
            'exercises_count'=>Exercise::count(), //wszystkie dostępne ćwiczenia
            'this_week'=>Workout::where('user_id', $userId)
                ->where('is_template', false)
                ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count()
        ];

        return view('dashboard', compact('workouts', 'stats'));
    }

    // formularz tworzenia treningu - tez obsluguje plany treningowe
    public function create(Request $request) {
        // sprawdzamy flagę czy to szablon
        $isTemplate = $request->boolean('is_template');

        // zmienna sourceTemplate do przechowania danych o planie - trzyma ID workoutu ktory jest szablonem
        $sourceTemplate = null;
        if ($request->has('source_template_id')) {
            $sourceTemplate = Workout::find($request->get('source_template_id'));
        }

        // pobranie listy cwiczen
        $exercises = Exercise::all();

        // zwrocenie widoku create z dostarczeniem mu zmiennych exercises, isTemplate i sourceTemplate
        return view('workouts.create', compact('exercises', 'isTemplate', 'sourceTemplate'));
    }

    // Zapis treningu do bazy danych
    public function store(Request $request)
    {
        $isTemplate = $request->boolean('is_template');

        // walidacja danych z formularza
        $validated = $request->validate([
            'date' => $isTemplate ? 'nullable' : 'required|date',
            'name' => 'required|string|max:255',
            'is_template' => 'boolean',
            'source_template_id' => 'nullable|exists:workouts,id',
        ]);

        //Zapis do bazy rekordu treninngu
        $workout = Workout::create([
            'user_id' => auth()->id(),
            'date' => $isTemplate ? null : Carbon::parse($request->date),
            'name' => $request->name,
            'is_template' => $isTemplate,
        ]);

        // Kopiowanie ćwiczeń do nowo utworzonego treningu na podstawie szablonu na podstawie soruceTemplateId
        if (!$isTemplate && $request->filled('source_template_id')) {
            // szukamy source_templateId do skopiowania wszystkich danych
            $sourceWorkout  = Workout::with('workoutSets')->find($request->source_template_id);


            if($sourceWorkout) {
                foreach($sourceWorkout->workoutSets as $set) {
                    $newSet = $set->replicate(); // metoda Eloquent - klonuje obiekt w pamięci bez ID
                    $newSet->workout_id = $workout->id; //przypisanie ID do nowego treningu
                    $newSet->save(); //zapis do bazy
                }
            }
        }

        // przekierowanie w zależności czy utworzono plan treningowy czy sesje treningową
        if ($isTemplate) {
            return redirect()->route('workouts.show', $workout->id)->with('status', 'Plan utworzony! Dodaj ćwiczenia');
        }

        return redirect()->route('workouts.show', $workout->id)->with('status', 'Trening utworzenu na postawie planu treningowego.');
    }

    // Widok treningu: dodawanie serii i ćwiczeń
    public function show($id){
        $workout = Workout::with('workoutSets.exercise')->findOrFail($id); //findOrFail - wyrzuci błąd jeśli trening nie istnieje
        $exercises = Exercise::all();

        return view('workouts.show', compact('workout', 'exercises'));
    }

    // Historia treningu z filtrowaniem i pagniacją
    public function history(Request $request) {
        // budowa zapytania
        $query = Workout::where('user_id', auth()->id())
            ->where('is_template', false); //szukamy tylko i wyłącznie treningów


        // wyszukiwanie po nazwie
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // wybór miesiąca
        if ($request->filled('month')) {
            try {
                $parts = explode('-', $request->month);
                if (count($parts) === 2) {  // rozbijamy na rok i miesiąc (filtrowanie tylko po miesiący danego roku)
                    $year = $parts[0];
                    $month = $parts[1];

                    $query->whereYear('date', $year)
                        ->whereMonth('date', $month);
                }
            } catch (\Exception $e) {
                // ignoruj błąd parsowania
            }
        }

        $workouts = $query->withCount('workoutSets')
            ->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('workouts.history', compact('workouts'));
    }

    // Widok szablonów (is_template = true)
    public function templates()
    {
        $templates = Workout::where('user_id', auth()->id())
            ->where('is_template', true)
            ->withCount('workoutSets')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('workouts.templates', compact('templates'));
    }

    // Formularz edycji treningu (nazwa i data)
    public function edit(Workout $workout) {
        // obsługa nieautoryzowanej proby edycji treningu
        if ($workout->user_id !== auth()->id()) {
            abort(403);
        }

        return view('workouts.edit', compact('workout'));
    }

    // Aktualizacja: zapis zmian z formularza edycji
    public function update(Request $request, Workout $workout)
    {
        if ($workout->user_id !== auth()->id()) {
            abort(403);
        }

        // Walidacja danych
        $rules = [
            'name' => 'required|string|max:255'
        ];

        // jeżeli to nie jest szablon treningu, wymagamy daty
        if (!$workout->is_template) {
            $rules['date'] = 'required|date';
        }

        $validated = $request->validate($rules);
        $workout->update($validated);

        // przekierowanie w zaleznosci od tego jaki przycisk kliknął uzytkownik
        $action = $request->input('action');

        if ($action === 'edit_exercises') {
            // jeżeli kliknięto "edytuj ćwiczenia" - idziemy do workout.show (widok dodawania serii)
            return to_route('workouts.show', $workout)
                ->with('success', 'Dane zapisane. Możesz edytować ćwiczenia.');
        }

        // jeżeli kliknięto "zapisz informacje" wracamy do listy treningów lub szablonów
        if ($workout->is_template) { //sprawdzenie czy to jest szablon czy sesja treningowa
            return to_route('workouts.templates')
                ->with('success', 'Zaktualizowano nazwę planu.');
        } else {
            return to_route('workouts.history')
            ->with('success', 'Zaktualizowano informacje o treningu.');
        }
    }

    //USuwanie treningu - łącznie z seriami (kaskadowo)
    public function destroy($id) {
        $workout = Workout::where('user_id', auth()->id())->findOrFail($id);

        // usuniecie wszystkich serii przypisanych do danego treningu
        $workout->workoutSets()->delete();
        // usuniecie samej instancji treningu
        $workout->delete();

        return redirect()->back()->with('success', 'Trening usunięty');
    }
}
