<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutSetController;
use App\Http\Controllers\WorkoutController;

use Illuminate\Support\Facades\Route;

// Striba startowa
Route::get('/', function () {
    return view('welcome');
});

// Routy wymagające zalogowania (Middleware Auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Główna logika aplikacji - wymaga logowania i weryfikacji e-maila
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [WorkoutController::class, 'index'])->name('dashboard');

    // Szablony i historia treningów
    Route::get('/workouts/templates', [WorkoutController::class, 'templates'])->name('workouts.templates');
    Route::get('/workouts/history', [WorkoutController::class, 'history'])->name('workouts.history');

    // Pełny CRUD dla treningów (index, create, store, show, edit, update, destroy)
    Route::resource('workouts', WorkoutController::class);

    // obsługa serii (dodawnaie, edycja, usuwanie)
    Route::post('/workout-sets', [WorkoutSetController::class, 'store'])->name('workout_sets.store');
    Route::delete('/workout-sets/{id}', [WorkoutSetController::class, 'destroy'])->name('workout_sets.destroy');
    Route::get('/workout-sets/{id}/edit', [WorkoutSetController::class, 'edit'])->name('workout_sets.edit');
    Route::put('/workout-sets/{id}', [WorkoutSetController::class, 'update'])->name('workout_sets.update');

    // Ćwiczenia, pełny CRUD
    Route::resource('exercises', ExerciseController::class);
});

require __DIR__.'/auth.php';

