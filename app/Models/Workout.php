<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{

    protected $fillable = ['user_id', 'name', 'date', 'is_template'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function workoutSets() {
        return $this->hasMany(WorkoutSet::class);
    }
}
