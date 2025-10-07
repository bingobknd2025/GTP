<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $table = 'admin_activity_logs';

    protected $fillable = [
        'user_id',
        'type',
        'details',
        'ip_address',
        'device',
        'browser',
        'os',
    ];
}
