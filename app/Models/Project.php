<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\RecordsActivity;

class Project extends Model
{
    use HasFactory, RecordsActivity;

    protected $guarded = [];

    protected $with = ['activity'];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->orderByDesc('created_at');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function members()
    {
      return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

    public function invite(User $user)
    {
      return $this->members()->attach($user);
    }

    public function addTask(array $attributes = [])
    {
        return $this->tasks()->create(
            array_merge(
                $attributes, [
                'completed' => false
                ]
            )
        );
    }
}
