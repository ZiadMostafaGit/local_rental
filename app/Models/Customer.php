<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\CustomerPhonenum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'customers';
    protected $guard = 'customer';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'gender',
        'score',
        'state',
        'city',
        'street',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password'
    ];
    protected $casts = [
        'password' => 'hashed'
    ];
    public $timestamps = false;
    public function phoneNumbers()
    {
        return $this->hasMany(CustomerPhonenum::class);
    }
    // في موديل Customer
public function setGenderAttribute($value)
{
    if ($value === 'male') {
        $this->attributes['gender'] = 'M';
    } elseif ($value === 'female') {
        $this->attributes['gender'] = 'F';
    }
}

public function items()
{
    return $this->belongsToMany(Item::class, 'rents')
                ->withPivot('rented_time', 'start_date', 'end_date', 'delivery_address', 'payment_token')
                ->withTimestamps();
}
public function incrementScore($amount = 10)
{
    $this->increment('score', $amount);
}

public function review(){
    return $this->hasMany(Review::class);
}

public function rentals()
{
    return $this->hasMany(Rent::class); 
}

}
