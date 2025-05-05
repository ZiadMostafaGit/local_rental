<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['conversation_id', 'message_content', 'sender','created_at'];

    public $timestamps = false;


    public function conversation()
{
    return $this->belongsTo(Conversation::class);
}

}
