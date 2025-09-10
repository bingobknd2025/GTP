<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Franchise extends Authenticatable
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

    protected $casts = [
        'status' => 'boolean',
    ];
}
