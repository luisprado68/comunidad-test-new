<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class StreamSupport extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'stream_support';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'user_id',
        'supported',
     
    ];


   

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
