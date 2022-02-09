<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GithubAuthController extends Controller
{
    public function create()
    {
        return Socialite::driver('github')
            ->with(['hd' => 'example.com'])
            ->setScopes(['read:user', 'public_repo'])
            ->redirect();
    }

    public function store()
    {
        $user = Socialite::driver('github')->user();
        $useremail = $user->email ?? $user->user['login'] . '@birdboard.test';
        $newUser = User::firstOrCreate(
            [
            'email' => $useremail
            ], [
            'email' => $useremail,
            'name' => $user->name,
            'password' => Hash::make(str::random(32)),
            'profile_photo_path' => $user->getAvatar()
            ]
        );
            
        $newUser->oauths()->updateOrCreate(
            [
            'email' => $newUser->email,
            ], [
            'provider' => 'github',
            'email' => $newUser->email,
            'token' => $user->token,
            'username' => $user->user['login']
            ]
        );

        auth()->login($newUser);

        return redirect(route('projects.index'));


    }
}
