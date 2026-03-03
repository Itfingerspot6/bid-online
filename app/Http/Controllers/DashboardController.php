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

        $watchedAuctions = auth()->user()->watchAuctions()->with(['category', 'seller'])->latest()->get();

        return view('dashboard', compact('myAuctions', 'myBids', 'watchedAuctions'));
    }
}