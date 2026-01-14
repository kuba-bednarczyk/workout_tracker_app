<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Grupy miesniowe:
        $muscleGroups = ['Klatka', 'Plecy', 'Barki', 'Triceps', 'Biceps', 'Nogi', 'Brzuch'];
        $groupModels = []; // tablica pomocnicza

        foreach ($muscleGroups as $groupName) {
            // zapisujemy obiekt do $groupModels żeby znać jego ID
            $groupModels[$groupName] = MuscleGroup::firstOrCreate(['name' => $groupName]);
        }

        // Ćwiczenia domyśle (dla wszystkich użytkowników)
        $defaultExercisesList = [
            ['name' => 'Wyciskanie sztangi na ławce płaskiej', 'muscle_group' => 'Klatka'],
            ['name' => 'Rozpiętki na wyciągu', 'muscle_group' => 'Klatka'],
            ['name' => 'Podciąganie na drążku (nachwyt)', 'muscle_group' => 'Plecy'],
            ['name' => 'Wiosłowanie sztangą', 'muscle_group' => 'Plecy'],
            ['name' => 'Przysiady ze sztangą', 'muscle_group' => 'Nogi'],
            ['name' => 'Martwy ciąg', 'muscle_group' => 'Nogi'],
            ['name' => 'Wyciskanie hantli nad głowę', 'muscle_group' => 'Barki'],
            ['name' => 'Unoszenie hantli bokiem', 'muscle_group' => 'Barki'],
            ['name' => 'Prostowanie ramion na wyciągu', 'muscle_group' => 'Triceps'],
            ['name' => 'Pompki na poręczach', 'muscle_group' => 'Triceps'],
            ['name' => 'Uginanie ramion z hantlami', 'muscle_group' => 'Biceps'],
            ['name' => 'Uginanie ramion na wyciągu', 'muscle_group' => 'Biceps'],
            ['name' => 'Brzuszki', 'muscle_group' => 'Brzuch'],
        ];

        foreach ($defaultExercisesList as $exerciseData) {
            Exercise::firstOrCreate([
                'name' => $exerciseData['name'],
                // brak ID usera - ogolnodostepne cwiczenia
                'user_id' => null,
            ], [
                // id do grupy miesniowej z tabeli wyzej
                'muscle_group_id' => $groupModels[$exerciseData['muscle_group']]->id,
            ]);
        }

        // stworzenie testowego usera
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
        ]);

        // Lista ćwiczeń stworzonych przez użytkownika
        $userExercisesList = [
            ['name' => 'Wyciskanie hantli na skosie', 'group' => 'Klatka'],
            ['name' => 'Martwy Ciąg (Trapbar)', 'group' => 'Nogi'], // Użytkownik woli Trapbar zamiast klasyka
            ['name' => 'Przysiady bułgarskie', 'group' => 'Nogi'],
            ['name' => 'Wyciskanie OHP', 'group' => 'Barki'],
            ['name' => 'Wyciskanie francuskie', 'group' => 'Triceps'],
        ];

        // Tablica pomocnicza do trzymania ID ćwiczenia, żeby nie wyciągać tego "manualnie" z bazy i potem móc użyć w zapisywaniu ćwiczeń do danego treningu (używamy tego niżej)
        $exerciseMap = [];

        // Dodanie prywatnych ćwiczeń do mapy
        foreach ($userExercisesList as $data) {
            $ex = Exercise::create([
                'user_id' => $user->id, // przypisanie id usera (cwiczenie prywatne)
                'muscle_group_id' => $groupModels[$data['group']]->id,
                'name' => $data['name'],
            ]);
            // zapisanie nazwy do mapy po utworzeniu cwiczenia
            $exerciseMap[$data['name']] = $ex;
        }

        // Dodanie systemowych ćwiczeń do mapy (żeby użyć ich w historii)
        $systemExercisesToMap = [
            'Wyciskanie sztangi na ławce płaskiej',
            'Podciąganie na drążku (nachwyt)',
            'Przysiady ze sztangą',
            'Brzuszki'
        ];

        foreach ($systemExercisesToMap as $sysName) {
            $sysEx = Exercise::where('name', $sysName)->whereNull('user_id')->first();
            if ($sysEx) {
                $exerciseMap[$sysName] = $sysEx;
            }
        }

        // Tworzenie historii treningów
        // Trening 1: Rozruch (5 dni od dzisiejszej daty)
        $workout1 = Workout::create([
            'user_id' => $user->id,
            'name' => 'Rozruch po kontuzji',
            'date' => Carbon::now()->subDays(5)->hour(16)->minute(45),
            'is_template' => false,
        ]);

        // Dodawanie serii Trening 1
        // systemowe: Wyciskanie na płąskiej
        if (isset($exerciseMap['Wyciskanie sztangi na ławce płaskiej'])) {
            $exId = $exerciseMap['Wyciskanie sztangi na ławce płaskiej']->id;
            WorkoutSet::create(['workout_id' => $workout1->id, 'exercise_id' => $exId, 'reps' => 20, 'weight' => 20]); // sama sztanga
            WorkoutSet::create(['workout_id' => $workout1->id, 'exercise_id' => $exId, 'reps' => 15, 'weight' => 40]);
        }

        // prywatne: Wyciskanie hantli na skosie
        if (isset($exerciseMap['Wyciskanie hantli na skosie'])) {
            $exId = $exerciseMap['Wyciskanie hantli na skosie']->id;
            WorkoutSet::create(['workout_id' => $workout1->id, 'exercise_id' => $exId, 'reps' => 12, 'weight' => 22]);
        }


        // Trening 2: Leg Day (2 dni od dzisiejszej daty)
        $workout2 = Workout::create([
            'user_id' => $user->id,
            'name' => 'Leg Day',
            'date' => Carbon::now()->subDays(2)->hour(18)->minute(0),
            'is_template' => false,
        ]);

        // Dodawanie serii Trening 2:
        // systemowe: przysiady
        if (isset($exerciseMap['Przysiady ze sztangą'])) {
            $exId = $exerciseMap['Przysiady ze sztangą']->id;
            WorkoutSet::create(['workout_id' => $workout2->id, 'exercise_id' => $exId, 'reps' => 10, 'weight' => 60]);
            WorkoutSet::create(['workout_id' => $workout2->id, 'exercise_id' => $exId, 'reps' => 8, 'weight' => 80]);
        }

        // prywatne: martwy ciąg trapbar
        if (isset($exerciseMap['Martwy Ciąg (Trapbar)'])) {
            $exId = $exerciseMap['Martwy Ciąg (Trapbar)']->id;
            WorkoutSet::create(['workout_id' => $workout2->id, 'exercise_id' => $exId, 'reps' => 5, 'weight' => 100]);
            WorkoutSet::create(['workout_id' => $workout2->id, 'exercise_id' => $exId, 'reps' => 5, 'weight' => 110]);
        }


        // Plan Treningowy 1: Plan FBW
        $template = Workout::create([
            'user_id' => $user->id,
            'name' => 'Plan FBW A',
            'date' => null,
            'is_template' => true,
        ]);

        // Dodajemy po jednej serii z zerowym ciezarem i powtorzeniami (testowo)
        // klatka (systemowe)
        if (isset($exerciseMap['Wyciskanie sztangi na ławce płaskiej'])) {
            WorkoutSet::create(['workout_id' => $template->id, 'exercise_id' => $exerciseMap['Wyciskanie sztangi na ławce płaskiej']->id, 'reps' => 0, 'weight' => 0]);
        }
        // plecy (systemowe)
        if (isset($exerciseMap['Podciąganie na drążku (nachwyt)'])) {
            WorkoutSet::create(['workout_id' => $template->id, 'exercise_id' => $exerciseMap['Podciąganie na drążku (nachwyt)']->id, 'reps' => 0, 'weight' => 0]);
        }
        // barki (prywatne)
        if (isset($exerciseMap['Wyciskanie OHP'])) {
            WorkoutSet::create(['workout_id' => $template->id, 'exercise_id' => $exerciseMap['Wyciskanie OHP']->id, 'reps' => 0, 'weight' => 0]);
        }
    }
}
