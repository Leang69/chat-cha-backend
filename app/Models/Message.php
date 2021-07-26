<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    public function senderInfo(){
        return $this->hasOne(User::class,'id','from_user_id');
    }
    public function receiverInfo(){
        return $this->hasOne(User::class,'id','to_user_id');
    }

    protected $fillable = [
        'content',
        'from_user_id',
        'to_user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
