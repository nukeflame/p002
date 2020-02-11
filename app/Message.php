<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'chat_id',
        'read_at',
        'message',
    ];

    
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
    
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // protected $with = ['sender', 'receiver'];

    // public function scopeBySender($q, $sender)
    // {
    //     $q->where('sender_id', $sender);
    // }

    // public function scopeByReceiver($q, $sender)
    // {
    //     $q->where('receiver_id', $sender);
    // }

    // public function sender()
    // {
    //     return $this->belongsTo(User::class, 'sender_id')->select(['id', 'name']);
    // }

    // public function receiver()
    // {
    //     return $this->belongsTo(User::class, 'receiver_id')->select(['id', 'name']);
    // }
}
