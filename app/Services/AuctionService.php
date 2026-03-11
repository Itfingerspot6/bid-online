<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuctionService
{
    /**
     * Memproses bid secara otomatis.
     */
    public function placeBid(Auction $auction, User $user, float $amount)
    {
        return DB::transaction(function () use ($auction, $user, $amount) {
            $result = $this->executeBid($auction, $user, $amount);
            
            // Simpan bid ID untuk pengecekan proxy
            $currentBid = $result['bid'];

            // Picu pengecekan proxy bid
            $this->handleProxyBidding($auction, $user);

            return $result;
        });
    }

    /**
     * Logika internal untuk menaruh bid
     */
    private function executeBid(Auction $auction, User $user, float $amount)
    {
        // 1. Ambil penawar tertinggi sebelumnya untuk notifikasi outbid
        $previousBidder = Bid::where('auction_id', $auction->id)
            ->where('status', 'approved')
            ->latest()
            ->first()?->user;

        // 2. Kurangi saldo user
        $user->decrement('balance', $amount);

        // 3. Simpan bid dengan status approved
        $bid = Bid::create([
            'auction_id' => $auction->id,
            'user_id'    => $user->id,
            'amount'     => $amount,
            'status'     => 'approved',
        ]);

        // 4. Update harga lelang jika lebih tinggi
        if ($amount > $auction->current_price) {
            $auction->update(['current_price' => $amount]);
        }

        // 5. Kirim notifikasi outbid ke penawar sebelumnya (jika bukan user yang sama)
        if ($previousBidder && $previousBidder->id !== $user->id) {
            $previousBidder->notify(new \App\Notifications\AuctionOutbid($auction));
        }

        // 6. Catat transaksi
        Transaction::create([
            'buyer_id'    => $user->id,
            'seller_id'   => $auction->user_id,
            'auction_id'  => $auction->id,
            'amount'      => $amount,
            'status'      => 'pending',
            'payment_ref' => 'BID-' . strtoupper(uniqid()),
            'type'        => 'bid',
            'description' => 'Penawaran (Bid) pada: ' . $auction->title,
        ]);

        // 7. Cek apakah bid mencapai buy_now_price
        if ($auction->buy_now_price && $amount >= $auction->buy_now_price) {
            $this->closeAuction($auction, $user);
            broadcast(new \App\Events\BidPlaced($bid))->toOthers();
            return ['status' => 'won', 'bid' => $bid];
        }

        broadcast(new \App\Events\BidPlaced($bid))->toOthers();

        return ['status' => 'success', 'bid' => $bid];
    }

    /**
     * Menangani Proxy Bidding secara otomatis
     */
    private function handleProxyBidding(Auction $auction, User $lastBidder)
    {
        // Cari proxy bid tertinggi yang bukan milik last bidder
        $proxyBid = \App\Models\ProxyBid::where('auction_id', $auction->id)
            ->where('user_id', '!=', $lastBidder->id)
            ->where('is_active', true)
            ->where('max_amount', '>', $auction->current_price)
            ->orderBy('max_amount', 'desc')
            ->first();

        if (!$proxyBid) {
            return;
        }

        $user = $proxyBid->user;
        $increment = $auction->min_bid_increment;
        $nextBidAmount = min($auction->current_price + $increment, $proxyBid->max_amount);

        // Pastikan user punya saldo cukup
        if ($user->balance < $nextBidAmount) {
            $proxyBid->update(['is_active' => false]);
            return;
        }

        // Taruh bid otomatis
        $this->executeBid($auction, $user, $nextBidAmount);

        // Rekursif jika masih ada proxy lain yang mengalahkan (misal ada dua proxy bid bertarung)
        $this->handleProxyBidding($auction, $user);
    }

    /**
     * Menutup lelang dan memproses pemenang.
     */
    public function closeAuction(Auction $auction, User $winner)
    {
        $auction->update([
            'status'    => 'ended',
            'winner_id' => $winner->id,
        ]);

        // Update transaksi pemenang jadi completed
        Transaction::where('auction_id', $auction->id)
            ->where('buyer_id', $winner->id)
            ->where('status', 'pending')
            ->latest()
            ->first()
            ?->update(['status' => 'paid']);

        // Kirim notifikasi pemenang
        $winner->notify(new \App\Notifications\AuctionWon($auction));

        // Refund bidder lain yang memiliki bid 'approved' namun kalah
        // Catatan: Hanya me-refund bid terbaru dari setiap user yang kalah
        $losingBids = Bid::where('auction_id', $auction->id)
            ->where('status', 'approved')
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
                'type'        => 'refund',
                'description' => 'Pengembalian Dana (Refund) Lelang: ' . $auction->title,
            ]);
        }
    }
}
