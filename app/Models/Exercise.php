<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use hasFactory, SoftDeletes;
    protected $fillable = ['name', 'muscle_group_id', 'description', 'user_id'];

    public function muscleGroup() {
        return $this->belongsTo(MuscleGroup::class);
    }

    public function workoutSets() {
        return $this->hasMany(WorkoutSet::class);
    }
}
