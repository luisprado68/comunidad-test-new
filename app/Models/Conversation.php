<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receive_id',
        'last_time_message'
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class,'user_id');
    }
}
