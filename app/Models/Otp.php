<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['customer_id', 'type', 'otp', 'expires_at'];
}
