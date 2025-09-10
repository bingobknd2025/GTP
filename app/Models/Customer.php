<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // 👈 Change to Authenticatable
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'kyc_id',
        'franchise_id',
        'fname',
        'lname',
        'email',
        'mobile_no',
        'password',
        'account_balance',
        'account_name',
        'account_type',
        'account_number',
        'account_bank',
        'status',
        'customer_otp',
        'email_verfied',
        'mobile_verfied',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'status' => 'boolean',
        'email_verfied' => 'boolean',
        'mobile_verfied' => 'boolean',
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
