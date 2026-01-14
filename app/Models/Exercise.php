<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    // hasFactory: pozwala używać fabryk (dane testowe - do seedera)
    // softDeletes: kolumna deleted_at - zapobieganie znikaniu cwiczen z historii treningow po usunieciu cwiczenia z bazy cwiczen
    use HasFactory, SoftDeletes;

    // zabezpieczenie Mass Assingment: tylko te pola mozna wyplenic przez formularz
    protected $fillable = ['name', 'user_id', 'muscle_group_id'];

    public function muscleGroup() {
        return $this->belongsTo(MuscleGroup::class);
    }

    public function workoutSets() {
        return $this->hasMany(WorkoutSet::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
