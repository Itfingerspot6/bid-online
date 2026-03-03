<?php

namespace Tests\Feature;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Category;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BidApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected $seller;
    protected $bidder;
    protected $category;
    protected $auction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::create([
            'name'     => 'Seller',
            'email'    => 'seller@example.com',
            'password' => bcrypt('password'),
            'balance'  => 0,
            'role'     => 'user',
        ]);

        $this->bidder = User::create([
            'name'     => 'Bidder',
            'email'    => 'bidder@example.com',
            'password' => bcrypt('password'),
            'balance'  => 5000000,
            'role'     => 'user',
        ]);

        $this->category = Category::create([
            'name' => 'Electronic',
            'slug' => 'electronic',
        ]);

        $this->auction = Auction::create([
            'user_id'           => $this->seller->id,
            'category_id'       => $this->category->id,
            'title'             => 'Laptop Gaming',
            'slug'              => 'laptop-gaming',
            'description'       => 'Description',
            'start_price'       => 1000000,
            'current_price'     => 1000000,
            'min_bid_increment' => 100000,
            'buy_now_price'     => 4000000,
            'start_time'        => now(),
            'end_time'          => now()->addDays(1),
            'status'            => 'active',
        ]);
    }

    public function test_user_can_submit_bid_and_it_is_automatically_approved()
    {
        $response = $this->actingAs($this->bidder)
            ->post(route('bids.store', $this->auction), [
                'amount' => 1500000,
            ]);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('bids', [
            'auction_id' => $this->auction->id,
            'user_id'    => $this->bidder->id,
            'amount'     => 1500000,
            'status'     => 'approved',
        ]);

        // Balance should be deducted automatically
        $this->assertEquals(3500000, $this->bidder->fresh()->balance);
        
        // Auction current price should be updated
        $this->assertEquals(1500000, $this->auction->fresh()->current_price);
        
        // Transaction should be created as pending
        $this->assertDatabaseHas('transactions', [
            'buyer_id'   => $this->bidder->id,
            'auction_id' => $this->auction->id,
            'amount'     => 1500000,
            'status'     => 'pending',
        ]);
    }

    public function test_bid_automatically_closes_auction_on_buy_now()
    {
        $response = $this->actingAs($this->bidder)
            ->post(route('bids.store', $this->auction), [
                'amount' => 4000000, // Buy now price
            ]);

        $response->assertStatus(302);
        
        $auction = $this->auction->fresh();
        $this->assertEquals('ended', $auction->status);
        $this->assertEquals($this->bidder->id, $auction->winner_id);
        
        // Transaction should be completed
        $this->assertDatabaseHas('transactions', [
            'buyer_id'   => $this->bidder->id,
            'auction_id' => $this->auction->id,
            'amount'     => 4000000,
            'status'     => 'completed',
        ]);
    }

    public function test_automatic_refund_for_losing_bidders()
    {
        // 1. First bidder bids
        $this->actingAs($this->bidder)
            ->post(route('bids.store', $this->auction), [
                'amount' => 1500000,
            ]);

        $secondBidder = User::create([
            'name'     => 'Bidder 2',
            'email'    => 'bidder2@example.com',
            'password' => bcrypt('password'),
            'balance'  => 5000000,
            'role'     => 'user',
        ]);

        // 2. Second bidder hits buy now
        $this->actingAs($secondBidder)
            ->post(route('bids.store', $this->auction), [
                'amount' => 4000000,
            ]);

        // 3. First bidder (bidder 1) should be refunded
        // Initial 5,000,000 - 1,500,000 (bid) + 1,500,000 (refund) = 5,000,000
        $this->assertEquals(5000000, $this->bidder->fresh()->balance);
        
        $this->assertDatabaseHas('transactions', [
            'buyer_id' => $this->bidder->id,
            'status'   => 'completed',
            'amount'   => 1500000,
            // Refund reference should be there (partial match)
        ]);
    }
}
