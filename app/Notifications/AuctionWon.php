<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionWon extends Notification
{
    use Queueable;

    public $auction;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'won',
            'title' => 'Selamat! Kamu Menang!',
            'message' => "Anda telah memenangkan lelang \"{$this->auction->title}\".",
            'auction_slug' => $this->auction->slug,
            'final_price' => $this->auction->current_price,
            'icon' => 'trophy',
        ];
    }
}
