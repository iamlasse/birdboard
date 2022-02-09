<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Models\Project;

class ProjectInvitesController extends Controller
{
    public function store(ProjectInvitationRequest $request, Project $project)
    {
        $project->invite($request->invitee);
        session()->flash('message', 'Succesfully added member to the project');
        dispatch(
            function () {
                logger('Test Queue');
            }
        )->delay(now()->addMinutes(2));
        return redirect($project->path());
    }
}
