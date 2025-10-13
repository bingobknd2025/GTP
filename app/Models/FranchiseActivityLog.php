<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FranchiseActivityLog extends Model
{
    use HasFactory;
    protected $table = 'franchise_activity_logs';

    protected $fillable = [
        'franchise_id',
        'message',
        'type',
        'ip_address',
    ];
}
