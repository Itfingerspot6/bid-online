<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction, \App\Services\AuctionService $auctionService)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        if ($auction->status !== 'active') {
            return back()->withErrors(['amount' => 'Lelang ini tidak sedang aktif.']);
        }

        if (now()->gt($auction->end_time)) {
            return back()->withErrors(['amount' => 'Lelang sudah berakhir.']);
        }

        $minAmount = $auction->current_price + $auction->min_bid_increment;

        if ($request->amount < $minAmount) {
            return back()->withErrors(['amount' => 'Bid minimal Rp ' . number_format($minAmount, 0, ',', '.')]);
        }

        if ($auction->user_id === auth()->id()) {
            return back()->withErrors(['amount' => 'Kamu tidak bisa bid lelang milikmu sendiri.']);
        }

        $user = auth()->user();

        if ($user->balance < $request->amount) {
            return back()->withErrors(['amount' => 'Saldo kamu tidak cukup. Silakan deposit terlebih dahulu.']);
        }

        try {
            $result = $auctionService->placeBid($auction, $user, $request->amount);

            if ($result['status'] === 'won') {
                return back()->with('success', '🎉 Selamat! Bid Buy Now berhasil dan Anda memenangkan lelang ini!');
            }

            return back()->with('success', 'Bid berhasil diajukan dan saldo Anda telah terpotong secara otomatis.');
        } catch (\Exception $e) {
            return back()->withErrors(['amount' => 'Terjadi kesalahan saat memproses bid: ' . $e->getMessage()]);
        }
    }

    public function setProxyBid(Request $request, Auction $auction)
    {
        $request->validate([
            'max_amount' => 'required|numeric|min:' . ($auction->current_price + $auction->min_bid_increment),
        ]);

        if ($auction->status !== 'active') {
            return back()->withErrors(['max_amount' => 'Lelang ini tidak sedang aktif.']);
        }

        if ($auction->user_id === auth()->id()) {
            return back()->withErrors(['max_amount' => 'Kamu tidak bisa bid lelang milikmu sendiri.']);
        }

        \App\Models\ProxyBid::updateOrCreate(
            ['auction_id' => $auction->id, 'user_id' => auth()->id()],
            ['max_amount' => $request->max_amount, 'is_active' => true]
        );

        // Langsung picu auto-bid jika proxy bid baru ini lebih tinggi dari harga saat ini 
        // tapi user bukan penawar tertinggi sekarang
        $highestBid = $auction->highestBid;
        if (!$highestBid || $highestBid->user_id !== auth()->id()) {
            app(\App\Services\AuctionService::class)->placeBid($auction, auth()->user(), $auction->current_price + $auction->min_bid_increment);
        }

        return back()->with('success', 'Proxy Bid berhasil diatur! Sistem akan otomatis nge-bid untukmu.');
    }

    private function closeAuction(Auction $auction, $winner)
    {
        $auction->update([
            'status'    => 'ended',
            'winner_id' => $winner->id,
        ]);

        // Update transaksi jadi completed
        Transaction::where('auction_id', $auction->id)
            ->where('buyer_id', $winner->id)
            ->where('status', 'pending')
            ->latest()
            ->first()
            ?->update(['status' => 'completed']);

        // Refund semua bidder yang kalah
        $losingBids = Bid::where('auction_id', $auction->id)
            ->where('user_id', '!=', $winner->id)
            ->get()
            ->groupBy('user_id');

        foreach ($losingBids as $userId => $bids) {
            $latestBid = $bids->sortByDesc('amount')->first();
            $latestBid->user->increment('balance', $latestBid->amount);

            Transaction::create([
                'buyer_id'    => $userId,
                'seller_id'   => $auction->user_id,
                'auction_id'  => $auction->id,
                'amount'      => $latestBid->amount,
                'status'      => 'completed',
                'payment_ref' => 'REFUND-' . strtoupper(uniqid()),
            ]);
        }
    }
}