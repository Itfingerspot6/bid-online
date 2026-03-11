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

        $potentialEarnings = Auction::where('user_id', auth()->id())
            ->where('status', 'active')
            ->with(['highestBid'])
            ->get()
            ->sum(fn($auction) => $auction->highestBid?->amount ?? 0);

        $pendingReviews = \App\Models\Transaction::where('buyer_id', auth()->id())
            ->where('status', 'completed')
            ->where('type', 'bid')
            ->whereDoesntHave('review')
            ->with('auction.seller')
            ->get();

        return view('dashboard', compact('myAuctions', 'myBids', 'watchedAuctions', 'potentialEarnings', 'pendingReviews'));
    }
}