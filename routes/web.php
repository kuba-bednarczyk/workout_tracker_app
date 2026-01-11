<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutSetController;
use App\Http\Controllers\WorkoutController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Route::get('/dashboard', [WorkoutController::class, 'index'])
//    ->middleware(['auth', 'verified'])
//    ->name('dashboard');
//
//Route::resource('workouts', WorkoutController::class)->middleware(['auth']);
//Route::post('/workout-sets', [WorkoutSetController::class, 'store'])->name('workout_sets.store')->middleware(['auth']);
//Route::resource('exercises', ExerciseController::class)->middleware(['auth']);
//Route::delete('/workout-sets/{id}', [WorkoutSetController::class, 'destroy'])->name('workout_sets.destroy')->middleware(['auth']);

Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [WorkoutController::class, 'index'])->name('dashboard');

    // 2. SZABLONY I HISTORIA TRENINGÓW
    Route::get('/workouts/templates', [WorkoutController::class, 'templates'])->name('workouts.templates');
    Route::get('/workouts/history', [WorkoutController::class, 'history'])->name('workouts.history');

    // 3. Pełny CRUD dla treningów (index, create, store, show, edit, update, destroy)
    Route::resource('workouts', WorkoutController::class);

    // 4. Obsługa Serii (Dodawanie, Edycja, Usuwanie)
    Route::post('/workout-sets', [WorkoutSetController::class, 'store'])->name('workout_sets.store');
    Route::delete('/workout-sets/{id}', [WorkoutSetController::class, 'destroy'])->name('workout_sets.destroy');

    // Nowe trasy do edycji serii:
    Route::get('/workout-sets/{id}/edit', [WorkoutSetController::class, 'edit'])->name('workout_sets.edit');
    Route::put('/workout-sets/{id}', [WorkoutSetController::class, 'update'])->name('workout_sets.update');

    // 5. Ćwiczenia
    Route::resource('exercises', ExerciseController::class);
});

require __DIR__.'/auth.php';

