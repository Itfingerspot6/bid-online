<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'balance', 'role', 'avatar', 'bio', 'location', 'seller_status'];

    public function isSeller()
    {
        return $this->role === 'seller';
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'seller_id');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function canCreateAuction()
    {
        return $this->role === 'admin' || ($this->role === 'seller' && $this->seller_status === 'approved');
    }

    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function wonAuctions()
    {
        return $this->hasMany(Auction::class, 'winner_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function watchAuctions()
    {
        return $this->belongsToMany(Auction::class, 'watchlists');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}