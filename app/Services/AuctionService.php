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
            // 1. Kurangi saldo user (lock for update di controller atau di sini)
            $user->decrement('balance', $amount);

            // 2. Simpan bid dengan status approved
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'user_id'    => $user->id,
                'amount'     => $amount,
                'status'     => 'approved',
            ]);

            // 3. Update harga lelang jika lebih tinggi
            if ($amount > $auction->current_price) {
                $auction->update(['current_price' => $amount]);
            }

            // 4. Catat transaksi (pending sampai lelang selesai atau langsung completed?)
            // Mengikuti pola lama: catat sebagai pending agar admin bisa melihat aliran dana
            Transaction::create([
                'buyer_id'    => $user->id,
                'seller_id'   => $auction->user_id,
                'auction_id'  => $auction->id,
                'amount'      => $amount,
                'status'      => 'pending',
                'payment_ref' => 'BID-' . strtoupper(uniqid()),
            ]);

            // 5. Cek apakah bid mencapai buy_now_price
            if ($auction->buy_now_price && $amount >= $auction->buy_now_price) {
                $this->closeAuction($auction, $user);
                return ['status' => 'won', 'bid' => $bid];
            }

            return ['status' => 'success', 'bid' => $bid];
        });
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
            ?->update(['status' => 'completed']);

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
            ]);
        }
    }
}
