<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    //
    // app/Models/Auction.php
protected $fillable = [
    'user_id', 'category_id', 'winner_id', 'title', 'slug',
    'description', 'images', 'start_price', 'current_price',
    'min_bid_increment', 'buy_now_price', 'start_time', 'end_time', 'status'
];

protected $casts = [
    'images' => 'array',
    'start_time' => 'datetime',
    'end_time' => 'datetime',
];

public function seller()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function category()
{
    return $this->belongsTo(Category::class);
}

public function winner()
{
    return $this->belongsTo(User::class, 'winner_id');
}

public function bids()
{
    return $this->hasMany(Bid::class);
}

public function approvedBids()
{
    return $this->hasMany(Bid::class)->where('status', 'approved');
}

public function transaction()
{
    return $this->hasOne(Transaction::class);
}

public function highestBid()
{
    return $this->hasOne(Bid::class)
        ->where('status', 'approved')
        ->ofMany('amount', 'max');
}

public function watchlists()
{
    return $this->hasMany(Watchlist::class);
}
}
