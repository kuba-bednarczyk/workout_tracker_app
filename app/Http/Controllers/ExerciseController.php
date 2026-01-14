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
    public function index(Request $request)
    {
        $query = Exercise::query();

        // Dołączamy relację (Eager Loading)
        $query->with('muscleGroup');

        // Aby posortować po nazwie grupy mięśniowej, musimy dołączyć jej tabelę
        // Używamy leftJoin, żeby nie zgubić ćwiczeń, które nie mają przypisanej grupy (jeśli takie są)
        $query->select('exercises.*') // Ważne: wybieramy tylko kolumny z ćwiczeń, żeby ID się nie nadpisało
        ->leftJoin('muscle_groups', 'exercises.muscle_group_id', '=', 'muscle_groups.id');

        // Warunek: Pokaż systemowe LUB użytkownika
        // Musimy uściślić 'exercises.user_id', bo po joinie nazwy kolumn mogą się dublować
        $query->where(function($q) {
            $q->where('exercises.user_id', auth()->id())
                ->orWhereNull('exercises.user_id');
        });

        // Filtr 1: Wyszukiwanie po nazwie ćwiczenia
        if ($request->filled('search')) {
            $query->where('exercises.name', 'like', '%' . $request->search . '%');
        }

        // Filtr 2: Wybór grupy mięśniowej
        if ($request->filled('muscle_group_id')) {
            $query->where('exercises.muscle_group_id', $request->muscle_group_id);
        }

        // SORTOWANIE:
        // 1. po nazwie grupy mięśniowej
        // 2. po nazwie ćwiczenia
        $exercises = $query
            ->orderBy('muscle_groups.name')
            ->orderBy('exercises.name')
            ->paginate(10);

        // Listy do dropdowna
        $muscleGroups = MuscleGroup::orderBy('name')->get();

        return view('exercises.index', compact('exercises', 'muscleGroups'));
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
