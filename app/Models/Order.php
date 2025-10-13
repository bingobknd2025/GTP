<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    // Fillable fields according to your table
    protected $fillable = [
        'order_no',
        'customer_id',
        'franchise_id',
        'purity',
        'before_melting_weight',
        'after_melting_weight',
        'unite_price',
        'total_price',
        'before_image',
        'after_image',
        'amount_paid',
        'invoice',
        'status',
        'order_note',
    ];

    // Casts
    protected $casts = [
        'before_melting_weight' => 'float',
        'after_melting_weight' => 'float',
        'unite_price' => 'float',
        'total_price' => 'float',
        'amount_paid' => 'float',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }
}
