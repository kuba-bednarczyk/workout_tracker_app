<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercises = Exercise::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->with('muscleGroup')
            ->get()
            ->sortBy('name') // sortowanie po nazwie
            ->groupBy(function ($item) {
                return $item->muscleGroup->name;
            });

        return view('exercises.index', compact('exercises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $muscleGroups = MuscleGroup::all();
        return view('exercises.create', compact('muscleGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group_id' => 'required|exists:muscle_groups,id',
        ]);

        $validated['user_id'] = auth()->id();

        Exercise::create($validated);
        return redirect()->route('exercises.index')->with('success', 'Dodano ćwiczenie');
    }
    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exercise $exercise)
    {
        if ($exercise->user_id !== auth()->id()) {
            abort(403, 'Nie mozesz edytować cwiczen systemowych ');
        }

        $muscleGroups = MuscleGroup::all();

        return view('exercises.edit', compact('exercise', 'muscleGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exercise $exercise)
    {
        if ($exercise->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group_id' => 'required|exists:muscle_groups,id',
        ]);

        $exercise->update($validated);

        return redirect()->route('exercises.index')->with('status', 'Ćwiczenie zaktualizowano pomyślnie');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $exercise = Exercise::where('user_id', auth()->id())->findOrFail($id);

        $exercise->delete();

        return back()->with('success', 'Ćwiczenie usunięte!');
    }
}
