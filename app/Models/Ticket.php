<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'subject',
        'message',
        'reply_message',
        'replied_by',
        'replied_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
