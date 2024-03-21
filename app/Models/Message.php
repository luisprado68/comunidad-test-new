<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receive_id',
        'read',
        'body',
        'type'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
    public function messages()
    {
        return $this->belongsTo(Message::class,'sender_id');
    }
}
