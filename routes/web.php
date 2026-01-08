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

Route::get('/dashboard', [WorkoutController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('workouts', WorkoutController::class)->middleware(['auth']);
Route::post('/workout-sets', [WorkoutSetController::class, 'store'])->name('workout_sets.store')->middleware(['auth']);
Route::resource('exercises', ExerciseController::class)->middleware(['auth']);
Route::delete('/workout-sets/{id}', [WorkoutSetController::class, 'destroy'])->name('workout_sets.destroy')->middleware(['auth']);

require __DIR__.'/auth.php';

