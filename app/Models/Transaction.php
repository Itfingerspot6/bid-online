<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    // app/Models/Transaction.php
protected $fillable = [
    'auction_id', 'buyer_id', 'seller_id',
    'amount', 'status', 'payment_ref',
    'type', 'description'
];

public function auction()
{
    return $this->belongsTo(Auction::class);
}

public function buyer()
{
    return $this->belongsTo(User::class, 'buyer_id');
}

public function seller()
{
    return $this->belongsTo(User::class, 'seller_id');
}
}
