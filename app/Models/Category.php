<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    // app/Models/Category.php
    protected $fillable = ['name', 'slug'];
    
    /**
     * Get the auctions for the category.
     */
    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }
}
