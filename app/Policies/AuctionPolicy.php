<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\User;

class AuctionPolicy
{
    public function update(User $user, Auction $auction): bool
    {
        // Admin bisa edit semua auction
        if ($user->role === 'admin') {
            return true;
        }
        
        // User biasa hanya bisa edit auction miliknya
        return $user->id === $auction->user_id;
    }

    public function delete(User $user, Auction $auction): bool
    {
        // Admin bisa delete semua auction
        if ($user->role === 'admin') {
            return true;
        }
        
        // User biasa hanya bisa delete auction miliknya
        return $user->id === $auction->user_id;
    }
}