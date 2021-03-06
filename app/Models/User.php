<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\PlanUser;
use App\Traits\HasPlans;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasPlans;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'avatar',
        'profile_photo_url'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id')->latest('updated_at');
    }

    public function oauths()
    {
        return $this->hasMany(Oauth::class);
    }

    public function getAvatarAttribute()
    {
        return $this->profile_photo_url;
    }

    public function authorizedProjects()
    {
        return Project::where('owner_id', $this->id)->orWhereHas(
            'members', function ($query) {
                $query->where('user_id', $this->id);
            }
        );
    }
}
