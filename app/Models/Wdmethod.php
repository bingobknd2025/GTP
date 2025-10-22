<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Withdrawal;

class Wdmethod extends Model
{
    use HasFactory;

    protected $table = 'wdmethods';

    protected $fillable = [
        'name',
        'minimum',
        'maximum',
        'charges_amount',
        'charges_type',
        'duration',
        'img_url',
        'bankname',
        'account_name',
        'account_number',
        'swift_code',
        'iban_number',
        'ifsc_code',
        'wallet_address',
        'barcode',
        'network',
        'methodtype',
        'type',
        'status',
        'defaultpay',
        'custom_address',
    ];

    /**
     * Relationship with Withdrawals
     */
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'payment_mode', 'name');
    }
}
