<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // علاقة مع الصور
    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    // علاقة مع الـ Rent
    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    // علاقة مع الـ Customer من خلال Rent
    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'rents')
                    ->withPivot('rented_time', 'start_date', 'end_date', 'delivery_address', 'payment_token')
                    ->withTimestamps();
    }

    // علاقة مع الـ Lender
    public function lender()
    {
        return $this->belongsTo(Lender::class, 'lender_id');
    }
}
