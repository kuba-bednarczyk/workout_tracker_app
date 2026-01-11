<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    // Wyświetlenie kafelków na dashboardzie
    public function index() {
        $userId = auth()->id();

        $workouts = Workout::where('user_id', auth()->id())
            ->where('is_template', false)
            ->withCount('workoutSets')
            ->with(['workoutSets.exercise'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Statystyki
        $stats = [
            'this_month' => Workout::where('user_id', $userId)
                ->where('is_template', false)
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->count(),
            'total'=> Workout::where('user_id', $userId)
                    ->where('is_template', false)
                    ->count(),
            'exercises_count'=>Exercise::count(),
            'this_week'=>Workout::where('user_id', $userId)
                ->where('is_template', false)
                ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count()
        ];

        return view('dashboard', compact('workouts', 'stats'));
    }

    // Formularz - nazwa treningu (lub szablonu)
    public function create(Request $request) {
        $isTemplate = $request->boolean('is_template');

        $sourceTemplate = null;
        if ($request->has('source_template_id')) {
            $sourceTemplate = Workout::find($request->get('source_template_id'));
        }

        $exercises = Exercise::all();

        return view('workouts.create', compact('exercises', 'isTemplate', 'sourceTemplate'));
    }

    // Odbieranie danych z formularza i zapis do bazy
    public function store(Request $request)
    {
        $isTemplate = $request->boolean('is_template');

        $validated = $request->validate([
            'date' => $isTemplate ? 'nullable' : 'required|date',
            'name' => 'required|string|max:255',
            'is_template' => 'boolean',
            'source_template_id' => 'nullable|exists:workouts,id',
        ]);

        // 3. Zapis do bazy
        $workout = Workout::create([
            'user_id' => auth()->id(),
            'date' => $isTemplate ? null : Carbon::parse($request->date),
            'name' => $request->name,
            'is_template' => $isTemplate,
        ]);

        // kopoiowanie cwiczen
        if (!$isTemplate && $request->filled('source_template_id')) {
            $sourceWorkout  = Workout::with('workoutSets')->find($request->source_template_id);

            if($sourceWorkout) {
                foreach($sourceWorkout->workoutSets as $set) {
                    $newSet = $set->replicate();
                    $newSet->workout_id = $workout->id;
                    $newSet->save();
                }
            }
        }

        // przekierowanie
        if ($isTemplate) {
            return redirect()->route('workouts.show', $workout->id)->with('status', 'Plan utworzony! Dodaj ćwiczenia');
        }

        return redirect()->route('workouts.show', $workout->id)->with('status', 'Trening utworzenu na postawie planu treningowego.');
    }

    // Wyświetlenie strony dodawania serii do konkretnego treningu
    public function show($id){
        $workout = Workout::with('workoutSets.exercise')->findOrFail($id);
        $exercises = Exercise::all();

        return view('workouts.show', compact('workout', 'exercises'));
    }

    // Historia treningu z wyszukiwarką
    public function history(Request $request) {
        $query = Workout::where('user_id', auth()->id())
            ->where('is_template', false);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('month')) {
            try {
                $parts = explode('-', $request->month);
                if (count($parts) === 2) {
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

    // Wyświetlanie szablonów
    public function templates()
    {
        $templates = Workout::where('user_id', auth()->id())
            ->where('is_template', true)
            ->withCount('workoutSets') // Możesz tu dodać ->with('workoutSets.exercise') jeśli chcesz podgląd
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('workouts.templates', compact('templates'));
    }

    // Formularz edycji treningu
    public function edit(Workout $workout) {
        if ($workout->user_id !== auth()->id()) {
            abort(403);
        }

        return view('workouts.edit', compact('workout'));
    }

    public function update(Request $request, Workout $workout) {
        if ($workout->user_id !== auth()->id()) {
            abort(403);
        }

        // Walidacja (to co masz)
        $rules = [
            'name' => 'required|string|max:255'
        ];

        if (!$workout->is_template) {
            $rules['date'] = 'required|date';
        }

        $validated = $request->validate($rules);

        if(!$workout->is_template && $request->has('date')) {
            $validated['date'] = Carbon::parse($request->date);
        }

        // Aktualizacja nazwy w bazie
        $workout->update($validated);

        // --- TU JEST ZMIANA ---
        // Nieważne czy to szablon, czy trening - po zmianie nazwy
        // ZAWSZE kieruj do widoku szczegółów (show).
        // Tam masz już gotową tabelę do dodawania/usuwania serii.

        return redirect()
            ->route('workouts.show', $workout->id)
            ->with('status', 'Nazwa zapisana. Teraz możesz edytować ćwiczenia.');
    }

    public function destroy($id) {
        $workout = Workout::where('user_id', auth()->id())->findOrFail($id);

        // usuniecie wszystkich serii przypisanych do danego treningu
        $workout->workoutSets()->delete();
        // usuniecie samej instancji treningu
        $workout->delete();

        return redirect()->back()->with('success', 'Trening usunięty');
    }
}
