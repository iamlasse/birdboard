<?php

namespace App\Models;

use App\Models\User;
use DateTimeInterface;
use App\RecordsActivity;
use App\Traits\HasPlans;
use Laravel\Scout\Searchable;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\This;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, RecordsActivity, HasPlans;

    protected $guarded = [];
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
    
    public function incompleteTasks()
    {
        return $this->hasMany(Task::class)->where('completed', false);
    }

    public function completedTasks()
    {
        return $this->hasMany(Task::class)->where('completed', true);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

    public function hasMember(User $user)
    {
        return $this->members()->where(
            [
            ['project_id', $this->id],
            ['user_id', $user->id]
            ]
        )->exists();
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
                'completed' => false,
                'user_id' => auth()->id()
                ]
            )
        );
    }
   
    public function addTasks(array $tasks = [])
    {
        return $this->tasks()->createMany($tasks);
    }

    // Scopes
    public function scopeTaskStatus($query, $status = false)
    {
        return $query->whereHas(
            'tasks', function ($query) use ($status) {
                $query->where('completed', '=', $status);
            }
        );
    }

    public function scopeProjectTasks($query, $status = false)
    {

        return $query->with($status ? 'completedTasks' : 'incompleteTasks');
    }

    public function scopeCounts($query)
    {
        return $query->withCount(
            [
            'tasks as incomplete_tasks' => function ($b) {
                $b->where('completed', false);
            },
            'tasks as completed_tasks' => function ($b) {
                $b->where('completed', true);
            }
            ]
        );
    }

    public function scopeSearch($query, $search = '')
    {
        return $query->where(
            function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE',  '%' . $search . '%');
            }
        );
    }
}
