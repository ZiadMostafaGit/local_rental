<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'item_id',
        'comment',
        'rating',
    ];

    public $timestamps = false;
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
