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
    // منع إضافة الحقول start_date, end_date, delivery_address إذا كان الطلب ليس "approved"
    protected static function booted()
{
    static::creating(function ($rent) {
        if (isset($rent->rental_status) && $rent->rental_status !== 'approved') {
            // إذا كانت الحالة ليست approved، نقوم بإخفاء الحقول التي لا يجب تعبئتها
            $rent->start_date = null;
            $rent->end_date = null;
            $rent->delivery_address = null;
        }
    });

    static::updating(function ($rent) {
        if (isset($rent->rental_status) && $rent->rental_status !== 'approved') {
            // إذا كانت الحالة ليست approved، لا نقبل تحديث الحقول
            $rent->start_date = $rent->getOriginal('start_date');
            $rent->end_date = $rent->getOriginal('end_date');
            $rent->delivery_address = $rent->getOriginal('delivery_address');
        }
    });
}


}
