<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProjectInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->route('project'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'exists:users,email', function ($attribute, $value, $fail) {
                $user = User::whereEmail($value)->first();
                $project = $this->route('project');
                
                if($user) {
                    if($project->members()->where('user_id', $user->id)->where('project_id', $project->id)->exists()) {
                        $fail('You have already invited a user with this email');
                    }
                }
            }
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'There is no user with this email address'
        ];
    }
}
