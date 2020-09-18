<?php

namespace App;

use App\Models\Activity;

trait RecordsActivity
{
    public $oldAttributes = [];

    protected static function bootRecordsActivity()
    {
        collect(self::recordableEvents())->each(
            function ($event) {
                static::$event(
                    function ($model) use ($event) {
        

                        $model->recordActivity($model->activityType($event));
                    }
                );

                if($event == 'updated') {
                    static::updating(
                        function ($model) {
                            $model->oldAttributes = $model->getRawOriginal();
                        }
                    );
                }
            }
        );
    }

    protected function activityOwner()
    {
      if(auth()->check()) {
        return auth()->user();
      }

      $project = $this->project ?? $this;

      return $project->owner;
    }

    protected function activityType($type)
    {
        return "{$type}_" . strtolower(class_basename($this));
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function recordActivity($description)
    {
        
        $this->activity()->create(
            [
            'user_id' => $this->activityOwner()->id,
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project->id,
            'description' => $description,
            'changes' => $this->attributeChanges()
            ]
        );
    }

    protected function attributeChanges()
    {
        if($this->wasChanged()) {
            return  [
            'before' => collect($this->oldAttributes)->except(['updated_at', 'id', 'owner_id', 'created_at']),
            'after' => collect($this->getChanges())->except(['updated_at', 'id', 'owner_id', 'created_at'])
            ];
        }
        return null;
    }

    protected static function recordableEvents()
    {
        if(isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        }

        return ['created', 'updated'];
    }
}

