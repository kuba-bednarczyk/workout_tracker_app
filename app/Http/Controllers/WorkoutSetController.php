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
            'weight' => 'required|numeric|min:1|max:999.99',
            'reps' =>'required|integer|min:1|max:100'
        ]);

        WorkoutSet::create($validated);
        return redirect()->back()->withInput($request->only('exercise_id', 'weight'));
    }

    public function edit($id) {
        $set = WorkoutSet::findOrFail($id);
        if ($set->workout->user_id !== auth()->id()) abort(403);

        return view('workout_sets.edit', compact('set'));
    }

    public function update(Request $request, $id) {
        $set = WorkoutSet::findOrFail($id);
        if ($set->workout->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'weight' => 'required|numeric|min:0|max:999',
            'reps' => 'required|integer|min:1',
        ]);

        $set->update($validated);
        return redirect()->route('workouts.show', $set->workout_id)->with('success', 'Seria zaktualizowana');
    }

    public function destroy($id) {
        $set = WorkoutSet::findOrFail($id);
        if ($set->workout->user_id !== auth()->id()) {
            abort(403);
        }
        $set->delete();
        return back()->with('success', 'Seria usunięta');
    }
}
