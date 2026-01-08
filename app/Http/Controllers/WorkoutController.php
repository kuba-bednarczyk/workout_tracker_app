<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    // wyświetlenie kafelków na dashboardzie
    public function index() {
        $workouts = Workout::where('user_id', auth()->id())
            ->with('workoutSets.exercise')
            ->orderBy('date', 'desc')
            ->get();

        return view('dashboard', compact('workouts'));
    }

    // formularz - nazwa treningu
    public function create() {
        $exercises = Exercise::all();
        return view('workouts.create', compact('exercises'));
    }

    // odbieranie danych z formularza i zapis do bazy
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();
        // zapis do bazy
        $workout = Workout::create($validated);

        // przekierowanie do widoku dodawania serii
        return redirect()->route('workouts.show', $workout->id);
    }

    // wyświetlenie strony dodawania serii do konkretnego treningu
    public function show($id){
        $workout = Workout::with('workoutSets.exercise')->findOrFail($id);
        $exercises = Exercise::all();

        return view('workouts.show', compact('workout', 'exercises'));
    }

    public function destroy($id) {
        $workout = Workout::where('user_id', auth()->id())->findOrFail($id);

        // usuniecie wszystkich serii przypisanych do danego treningu
        $workout->workoutSets()->delete();
        // usuniecie samej instancji treningu
        $workout->delete();

        return redirect()->route('dashboard')->with('success', 'Trening usunięty');
    }
}
