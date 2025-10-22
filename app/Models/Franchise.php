<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Franchise extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'pincode',
        'contact_no',
        'email',
        'password',
        'contact_person_name',
        'contact_person_number',
        'store_lat',
        'store_long',
        'status',
        'image',
        'bank_name',
        'account_name',
        'account_type',
        'ifsc_code',
        'account_number',
        'account_bank',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => 'franchise'];
    }
}
