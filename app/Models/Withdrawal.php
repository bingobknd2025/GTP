<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Withdrawal extends Model
{
    use HasFactory;
    protected $fillable = [
        'txn_id',
        'user',
        'amount',
        'charges',
        'columns',
        'to_deduct',
        'payment_mode',
        'paydetails',
        'comment',
        'reference_number',
        'source',
        'status',
    ];
    protected $casts = [
        'columns' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user');
    }
}
