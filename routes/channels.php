<?php

use App\Models\Project;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('pusher', function ($user, $id) {
    return true;
});

// Broadcast::channel('projects', function ($user, Project $project) {
//     return true;
// });
// Broadcast::channel('test.{test}', function ($user) {
//     return true;
// });

// // Broadcast::channel('projects', function ($user, Project $project) {
// //     // return $user->id === $project->owner_id || $project->members->contains($user);
// //     return true;
// // });

// Broadcast::channel('projects.{project}', function ($user, Project $project) {
//     return $project->members->contains($user);
// });

Broadcast::channel('projects.{project}', function ($user, Project $project) {
    return $user->is($project->owner) || $project->members->contains($user);
});

Broadcast::channel('projects-chat.{project}', function ($user, Project $project) {
    if ($user->is($project->owner) || $project->members->contains($user)) {
        return new UserResource($user);
    }

    return false;
});
