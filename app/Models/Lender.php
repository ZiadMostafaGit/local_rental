<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Lender extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'lenders';
    protected $guard = 'lender';
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'gender', 'state', 'city', 'street', 'subscription'
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
        return $this->hasMany(LenderPhoneNumber::class);
    }

}
