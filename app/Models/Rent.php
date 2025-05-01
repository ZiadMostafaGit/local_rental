<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $table = 'rents';
    public $timestamps = false;
    protected $fillable = [
        'customer_id',
        'item_id',
        'rented_time',
        'start_date',
        'end_date',
        'delivery_address',
        'payment_token'
    ];

    protected $casts = [
        'rented_time' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
