<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Models\Transaction;
use Illuminate\Console\Command;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close-expired';
    protected $description = 'Close auctions that have passed their end time';

    public function handle()
    {
        $expiredAuctions = Auction::where('status', 'active')
            ->where('end_time', '<=', now())
            ->get();

        foreach ($expiredAuctions as $auction) {
            $highestBid = $auction->bids()
                ->where('status', 'approved')
                ->orderBy('amount', 'desc')
                ->first();

            if ($highestBid) {
                // Ada pemenang
                $auction->update([
                    'status'    => 'ended',
                    'winner_id' => $highestBid->user_id,
                ]);

                // Update transaksi bid pemenang jadi completed
                Transaction::where('auction_id', $auction->id)
                    ->where('buyer_id', $highestBid->user_id)
                    ->where('status', 'pending')
                    ->latest()
                    ->first()
                    ?->update(['status' => 'completed']);

                // Refund semua bidder yang kalah (yang approved)
                $losingBids = $auction->bids()
                    ->where('status', 'approved')
                    ->where('user_id', '!=', $highestBid->user_id)
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

                $this->info("Auction [{$auction->title}] ended. Winner: {$highestBid->user->name}");
            } else {
                // Tidak ada bid yang approved
                $auction->update(['status' => 'ended']);
                $this->info("Auction [{$auction->title}] ended with no approved bids.");
            }
        }

        $this->info('Done! ' . $expiredAuctions->count() . ' auction(s) processed.');
    }
}