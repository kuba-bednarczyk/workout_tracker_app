<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSet;
use Illuminate\Http\Request;

class WorkoutSetController extends Controller
{

    // DODAWANIE SERII:
    // "Dodaj" w widoku treningu
    public function store(Request $request) {
        // walidacja:
        $validated = $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'exercise_id' => 'required|exists:exercises,id',
            'weight' => 'required|numeric|min:0|max:999.99', //ciężar nieujemny
            'reps' =>'required|integer|min:1|max:100' //przynajmniej 1 powtorzenie
        ]);

        // zapis w bazie
        WorkoutSet::create($validated);
        // UX: wracamy do formularza, ale zostawiamy ostatnie ćwiczenie, żeby user nie wybierał z listy ćwiczenia za każdym razem
        return redirect()
            ->back()
            ->withInput($request->only('exercise_id', 'weight'));
    }

    // FORMULARZ EDYCJI SERII
    public function edit($id) {
        $set = WorkoutSet::findOrFail($id); // jesli nie znajdzie serii o danym ID
        if ($set->workout->user_id !== auth()->id()) abort(403); // sprawdzamy czy seria nalezy do zalogowanego usera

        return view('workout_sets.edit', compact('set')); //zwracamy widok i konkretną serie
    }

    // ZAPIS EDYCJI
    public function update(Request $request, $id) {
        $set = WorkoutSet::findOrFail($id);
        if ($set->workout->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'weight' => 'required|numeric|min:0|max:999',
            'reps' => 'required|integer|min:1',
        ]);

        $set->update($validated);

        // po edycji wracmay do widoku treningu
        return redirect()->route('workouts.show', $set->workout_id)->with('success', 'Seria zaktualizowana');
    }

    // USUWANIE SERII
    public function destroy($id) {

        $set = WorkoutSet::findOrFail($id);
        if ($set->workout->user_id !== auth()->id()) { //bezpieczenstwo przed nieautoryzowanym dostepem
            abort(403);
        }
        $set->delete();

        // odświeżamy strone na ktorej sie znajdujemy - metoda back()
        return back()->with('success', 'Seria usunięta');
    }
}
