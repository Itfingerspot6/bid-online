<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProxyBid extends Model
{
    protected $fillable = ['auction_id', 'user_id', 'max_amount', 'is_active'];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
