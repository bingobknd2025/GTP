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

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user', 'id');
    }

    // Relationship with Plan (if exists)
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan', 'id');
    }
}
