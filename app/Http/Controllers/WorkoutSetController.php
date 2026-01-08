<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSet;
use Illuminate\Http\Request;

class WorkoutSetController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'exercise_id' => 'required|exists:exercises,id',
            'weight' => 'required|numeric|min:0',
            'reps' =>'required|integer|min:1'
        ]);

        WorkoutSet::create($validated);

        return back()->with('success', 'Seria zapisana!');
    }

    public function destroy($id) {
        $set = WorkoutSet::findOrFail($id);

        // zabezpieczenie: sprawdź czy uzytkownik jest wlascicielem treningu
        if ($set->workout->user_id !== auth()->id()) {
            abort(403);
        }

        $set->delete();
        return back()->with('success', 'Seria usunięta');
    }
}
