<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = ['lender_id', 'customer_id', 'item_id'];

    public $timestamps = false;


    public function messages()
    {
        return $this->hasMany(Message::class,'conversation_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function lender()
    {
        return $this->belongsTo(Lender::class,'lender_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }
}
