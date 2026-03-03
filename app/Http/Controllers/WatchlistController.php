<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function toggle(Auction $auction)
    {
        $user = auth()->user();
        
        $exists = Watchlist::where('user_id', $user->id)
            ->where('auction_id', $auction->id)
            ->first();

        if ($exists) {
            $exists->delete();
            return back()->with('success', 'Lelang dihapus dari daftar simpan.');
        }

        Watchlist::create([
            'user_id' => $user->id,
            'auction_id' => $auction->id
        ]);

        return back()->with('success', 'Lelang berhasil disimpan.');
    }
}
