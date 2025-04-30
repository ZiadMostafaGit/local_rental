<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPhonenum extends Model
{
    use HasFactory;
    protected $table = 'customer_phone_num';
    protected $fillable = [
        'customer_id', 'phone_num',
    ];
    public $timestamps = false;
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
