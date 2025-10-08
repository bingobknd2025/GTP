<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerActivityLog extends Model
{
    use HasFactory;
    protected $table = 'customer_activity_logs';

    protected $fillable = [
        'customer_id',
        'message',
        'type',
        'ip_address',
    ];
}
