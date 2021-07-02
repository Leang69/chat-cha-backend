<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatList extends Model
{
    use HasFactory;

    public function lassMassage(){
        return $this->hasOne(Message::class,'id','message_id');
    }

    protected $fillable = [
        'last_message',
        'user_id',
        'with_user_id'
    ];
}
