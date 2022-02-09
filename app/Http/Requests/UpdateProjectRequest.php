<?php

namespace App\Http\Requests;

use App\Jobs\TestQueue;
use App\Events\ProjectUpdated;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Foundation\Http\FormRequest;
use App\Notifications\TestBroadcastNotification;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        return Gate::allows('update', $this->project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable|min:10|max:400'
        ];
    }

    public function save()
    {
        // $job = new TestQueue($this->project);
        // TestQueue::dispatch($this->project)->delay(now()->addSeconds(30))->onQueue('email');
        // TestQueue::dispatch($this->project)->delay(now()->addSeconds(30))->onQueue('important');
        
        $project = tap($this->project)->update($this->validated());
        // request()->user()->notify(new TestBroadcastNotification($this->project));
        // Bus::dispatch($job);
        event(new ProjectUpdated($project));
        // app(Dispatcher::class)->dispatch($job);
        // ProjectUpdated::broadcast($this->project);
        return $project;
    }
}
