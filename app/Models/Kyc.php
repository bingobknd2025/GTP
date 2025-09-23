<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'email',
        'country_code',
        'phone_number',
        'dob',
        'social_media',
        'address',
        'city',
        'state',
        'country',
        'resi_address_status',
        'address_proof_type',
        'address_proof_file',
        'address_veri_status',
        'document_type',
        'mobile_status',
        'frontimg',
        'backimg',
        'status',
        'identity_type',
        'identity_number',
        'identity_file',
        'identity_status',
        'kyc_type',
        'source',
    ];


    protected $casts = [
        'mobile_verified_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
