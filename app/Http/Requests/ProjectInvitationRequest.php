<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProjectInvitationRequest extends FormRequest
{
    protected $errorBag = 'project';
    public $invitee;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('manage', $this->project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd(request('email'));
        return [
            'email' => [
                'required', 
                'exists:users',
                // Rule::unique('project_members','user_id')->where(function ($query) {
                //     $user = User::whereEmail(request('email'))->first();
                //     return $query->where([
                //         ['project_id', $this->project->id],
                //         ['user_id', $user->id]
                //     ]);
                // })
                function ($_, $value, $fail) {
                    $user = User::whereEmail($value)->first();
                    $project = $this->project;
                    if($user && $project->hasMember($user)) {
                        $fail('You have already invited a user with :attribute: :input');
                    }

                    if($user && $user->is(auth()->user())) {
                        $fail('You cannot invite yourself to the project');
                    }

                    $this->invitee = $user;
                },
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'There is no user with this :attribute',
        ];
    }
}
