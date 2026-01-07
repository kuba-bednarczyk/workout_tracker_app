<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSet extends Model
{
    protected $fillable = ['workout_id', 'exercise_id', 'reps', 'weight'];

    public function workout() {
        return $this->belongsTo(Workout::class);
    }

    public function exercise() {
        return $this->hasMany(Exercise::class);
    }
}
