<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasMany(PlanUser::class, 'user_id');
    }

    /**
     * Get the owning imageable model.
     */
     public function model()
     {
         return $this->morphTo();
     }
}
