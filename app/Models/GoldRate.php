<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldRate extends Model
{
    use HasFactory;
    protected $table = 'gold_rates';

    protected $fillable = [
        'live_price',
        'currency',
        'price_gram_24k',
        'price_gram_22k',
        'price_gram_21k',
        'price_gram_20k',
        'price_gram_18k',
        'price_gram_16k',
        'price_gram_14k',
        'price_gram_10k',
        'fetched_at'
    ];
}
