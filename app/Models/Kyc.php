<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'first_name', 'last_name', 'email', 'country_code', 'phone_number', 'dob',
        'social_media', 'address', 'city', 'state', 'country', 'document_type', 'frontimg',
        'backimg', 'status', 'kyc_type', 'source',
    ];

    protected $casts = [
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
