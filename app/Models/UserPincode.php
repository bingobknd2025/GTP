<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPincode extends Model
{
    protected $table = 'users_pincode';

    protected $fillable = [
        'user_id',
        'pincode'
    ];
}
