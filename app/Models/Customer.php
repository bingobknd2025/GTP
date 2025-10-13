<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Foundation\Auth\User as Authenticatable; // 👈 Change to Authenticatable
=======
use Illuminate\Foundation\Auth\User as Authenticatable;
>>>>>>> master
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
<<<<<<< HEAD
        'status',
        'customer_otp',
=======
        'ref_by',
        'status',
>>>>>>> master
        'email_verfied',
        'mobile_verfied',
    ];

<<<<<<< HEAD
    protected $hidden = ['password'];

    protected $casts = [
        'status' => 'boolean',
=======
    protected $hidden = [
        'password',
    ];

    protected $casts = [
>>>>>>> master
        'email_verfied' => 'boolean',
        'mobile_verfied' => 'boolean',
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

<<<<<<< HEAD
    // JWT methods
=======
    // ✅ JWT Required Methods
>>>>>>> master
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
<<<<<<< HEAD
        return [];
=======
        return ['role' => 'customer'];
>>>>>>> master
    }
}
