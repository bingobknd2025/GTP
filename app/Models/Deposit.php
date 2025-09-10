<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Deposit extends Model
{
    use HasFactory;

    protected $table = 'deposits';

    protected $fillable = [
        'txn_id',
        'user',
        'amount',
        'payment_mode',
        'plan',
        'reference_number',
        'source',
        'status',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function userDetail()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
