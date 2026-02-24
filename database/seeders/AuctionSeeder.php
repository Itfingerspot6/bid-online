<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $categories = Category::all();

        $auctions = [
            ['title' => 'iPhone 14 Pro Max 256GB Space Black', 'price' => 8000000, 'increment' => 100000],
            ['title' => 'Samsung Galaxy S23 Ultra 512GB', 'price' => 7500000, 'increment' => 100000],
            ['title' => 'MacBook Pro M2 16 inch 512GB', 'price' => 15000000, 'increment' => 500000],
            ['title' => 'Sony PlayStation 5 Digital Edition', 'price' => 5000000, 'increment' => 100000],
            ['title' => 'Nike Air Jordan 1 Retro High OG', 'price' => 1500000, 'increment' => 50000],
            ['title' => 'Rolex Submariner Date Steel', 'price' => 80000000, 'increment' => 1000000],
            ['title' => 'Canon EOS R5 Body Only', 'price' => 25000000, 'increment' => 500000],
            ['title' => 'Sony WH-1000XM5 Wireless Headphone', 'price' => 2500000, 'increment' => 50000],
            ['title' => 'DJI Mini 3 Pro Drone', 'price' => 8000000, 'increment' => 200000],
            ['title' => 'iPad Pro 12.9 inch M2 256GB WiFi', 'price' => 12000000, 'increment' => 200000],
            ['title' => 'Adidas Yeezy Boost 350 V2', 'price' => 2000000, 'increment' => 50000],
            ['title' => 'Louis Vuitton Neverfull MM Monogram', 'price' => 15000000, 'increment' => 500000],
            ['title' => 'Herman Miller Aeron Chair Size B', 'price' => 10000000, 'increment' => 200000],
            ['title' => 'Nintendo Switch OLED Model', 'price' => 3500000, 'increment' => 100000],
            ['title' => 'Fender Stratocaster American Professional II', 'price' => 18000000, 'increment' => 500000],
            ['title' => 'Lego Technic Bugatti Chiron 42083', 'price' => 3000000, 'increment' => 100000],
            ['title' => 'Dyson V15 Detect Vacuum Cleaner', 'price' => 7000000, 'increment' => 200000],
            ['title' => 'GoPro Hero 11 Black', 'price' => 5500000, 'increment' => 100000],
            ['title' => 'Kindle Oasis 32GB Waterproof', 'price' => 2000000, 'increment' => 50000],
            ['title' => 'Bose SoundLink Revolve+ II', 'price' => 2500000, 'increment' => 50000],
        ];

        foreach ($auctions as $index => $item) {
            $category = $categories->random();

            $auction = Auction::create([
                'user_id'           => $admin->id,
                'category_id'       => $category->id,
                'title'             => $item['title'],
                'slug'              => Str::slug($item['title']) . '-' . Str::random(5),
                'description'       => 'Kondisi ' . ['sangat baik', 'baik', 'seperti baru', 'mulus'][rand(0, 3)] . '. Barang original dengan ' . ['garansi resmi', 'garansi toko', 'no garansi'][rand(0, 2)] . '. ' . ['Harga nego untuk penawaran serius.', 'Dijual karena tidak terpakai.', 'Barang langka, kesempatan terbatas.'][rand(0, 2)],
                'images'            => [],
                'start_price'       => $item['price'],
                'current_price'     => $item['price'],
                'min_bid_increment' => $item['increment'],
                'start_time'        => now()->subHours(rand(1, 24)),
                'end_time'          => now()->addDays(rand(1, 7)),
                'status'            => 'active',
            ]);

            // Buat beberapa user bidder
            $bidders = User::where('role', 'user')->get();

            if ($bidders->count() > 0) {
                $currentPrice = $item['price'];
                $numBids = rand(3, 8);

                for ($i = 0; $i < $numBids; $i++) {
                    $bidder = $bidders->random();
                    $currentPrice += $item['increment'] * rand(1, 3);

                    Bid::create([
                        'auction_id' => $auction->id,
                        'user_id'    => $bidder->id,
                        'amount'     => $currentPrice,
                    ]);
                }

                $auction->update(['current_price' => $currentPrice]);
            }
        }
    }
}