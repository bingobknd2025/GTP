<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Order;

class Wallet extends Model
{
    use HasFactory;
    protected $table = 'wallet_transactions';
    protected $fillable = [
        'txn_no',
        'order_id',
        'customer_id',
        'franchise_id',
        'amount',
        'type',
        'note',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
