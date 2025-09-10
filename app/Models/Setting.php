<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $fillable = [
        'website_name',
        'website_email',
        'website_contact',
        'fav_icon',
        'logo',
        'website_under_maintenance',
        'enable_offline_kyc',
        'enable_online_kyc',
        'min_deposit_amount',
        'min_withdraw_amount',
        'mobile_under_maintenance',
        'display_franchise_user',
        'display_franchise_kyc',
        'allow_franchise_kyc_approve',
        'allow_franchise_kyc_add',
    ];
}
