<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MuscleGroup;
use App\Models\Exercise;
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
        // Definicja grup miesniowych
        $groups = [ 'Legs', 'Chest', 'Back', 'Shoulders', 'Arms', 'Abs' ];

        //Tworzenie grupy w bazie i zapisywanie do tablicy dla późniejszego odwołania
        $createdGroups = [];
        foreach ($groups as $name) {
            $createdGroups[$name] = MuscleGroup::create(['name' => $name]);
        }

        // stworzenie uzytkownika
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);


        // Dodanie przykładowych cwiczen przypisując je do stworzonych grup miesniowych
        Exercise::create([
            'name' => 'BB Squats',
            'muscle_group_id' => $createdGroups['Legs']->id,
        ]);

        Exercise::create([
            'name' => 'DB Chest Press',
            'muscle_group_id' => $createdGroups['Chest']->id,
        ]);

        Exercise::create([
            'name' => 'Deadlift',
            'muscle_group_id' => $createdGroups['Back']->id,
        ]);

        Exercise::create([
            'name' => 'Military Press',
            'muscle_group_id' => $createdGroups['Shoulders']->id,
        ]);
    }
}
