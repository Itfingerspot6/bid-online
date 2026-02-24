<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Auction;

class DashboardController extends Controller
{
    public function index()
    {
        $myAuctions = Auction::where('user_id', auth()->id())
            ->latest()
            ->get();

        $myBids = Bid::with('auction')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('dashboard', compact('myAuctions', 'myBids'));
    }
}