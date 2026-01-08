<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MuscleGroup;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // lista domyślnych ćwiczeń dla użytkownika
        $starterKit = [
            'Chest' => ['Bench Press', 'DB Incline Press', 'Cable Flies'],
            'Back' => ['BB Row', 'DB Row', "Lat Pulldown's"],
            'Arms' => ['DB Curls', 'Preacher Curls', 'Tricep Extensions', 'French Press'],
            'Shoulders' => ['Over-Head Press', 'Lateral Raises', 'Face Pull'],
            'Legs' => ['Squats', 'Deadlifts', 'Hip-Thrusts'],
            'ABS' => ['Cable Crunch', 'Plank', 'Russian Twist']
        ];

        foreach ($starterKit as $muscleName => $exercises) {
            $muscleGroup = MuscleGroup::firstOrCreate(['name' => $muscleName]);

            foreach ($exercises as $exerciseName) {
                Exercise::firstOrCreate([
                    'name' => $exerciseName,
                    'muscle_group_id' => $muscleGroup->id
                ], [
                    'user_id' => null
                ]);
            }
        }
    }
}
