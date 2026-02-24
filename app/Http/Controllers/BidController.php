<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
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

        // Refund bid sebelumnya jika ada
        $previousBid = Bid::where('auction_id', $auction->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        if ($previousBid) {
            $user->increment('balance', $previousBid->amount);

            Transaction::create([
                'buyer_id'    => $user->id,
                'seller_id'   => $auction->user_id,
                'auction_id'  => $auction->id,
                'amount'      => $previousBid->amount,
                'status'      => 'completed',
                'payment_ref' => 'REFUND-' . strtoupper(uniqid()),
            ]);
        }

        // Kurangi saldo
        $user->decrement('balance', $request->amount);

        // Simpan bid
        Bid::create([
            'auction_id' => $auction->id,
            'user_id'    => $user->id,
            'amount'     => $request->amount,
        ]);

        // Update harga lelang
        $auction->update(['current_price' => $request->amount]);

        // Catat transaksi
        Transaction::create([
            'buyer_id'    => $user->id,
            'seller_id'   => $auction->user_id,
            'auction_id'  => $auction->id,
            'amount'      => $request->amount,
            'status'      => 'pending',
            'payment_ref' => 'BID-' . strtoupper(uniqid()),
        ]);

        // Cek apakah bid mencapai buy_it_now price
        if ($auction->buy_now_price && $request->amount >= $auction->buy_now_price) {
            $this->closeAuction($auction, $user);
            return back()->with('success', '🎉 Selamat! Kamu memenangkan lelang ini dengan harga Buy Now!');
        }

        return back()->with('success', 'Bid berhasil dipasang!');
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