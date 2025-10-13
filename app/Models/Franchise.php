<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
<<<<<<< HEAD

class Franchise extends Authenticatable
=======
use Tymon\JWTAuth\Contracts\JWTSubject;

class Franchise extends Authenticatable implements JWTSubject
>>>>>>> master
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
        'created_by',
        'updated_by',
    ];

<<<<<<< HEAD
    protected $casts = [
        'status' => 'boolean',
    ];
=======
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
>>>>>>> master
}
