<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LenderPhoneNumber extends Model
{
    use HasFactory;
    protected $table = 'lender_phone_num';
    protected $fillable = [
        'lender_id', 'phone_num',
    ];
    public $timestamps = false;
    public function lender()
    {
        return $this->belongsTo(Lender::class, 'lender_id');
    }
}
