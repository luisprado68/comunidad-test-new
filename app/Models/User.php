<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'twich_id',
        'channel',
        'status',
        'active',
        'description',
        'email',
        'area',
        'phone',
        'points_support',
        'time_zone',
        'status',
        'user_action',
        'hours_buyed',
        'img_profile',
        'deleted',
        'password',
        'token',
        'refresh_token',
        'calendar_enabled',
        'instagram',
        'facebook',
        'youtube',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class,'current_team_id','id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function range()
    {
        return $this->belongsTo(Range::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function score()
    {
        return $this->hasOne(Score::class);
    }
    public function supportScores()
    {
        return $this->hasMany(SupportScore::class);
    }
    public function streamSupport()
    {
        return $this->hasMany(StreamSupport::class);
    }
}
