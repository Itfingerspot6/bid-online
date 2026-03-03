<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionOutbid extends Notification
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
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'outbid',
            'title' => 'Penawaran Terlampaui!',
            'message' => "Penawaran Anda di lelang \"{$this->auction->title}\" telah dilampaui orang lain.",
            'auction_slug' => $this->auction->slug,
            'current_price' => $this->auction->current_price,
            'icon' => 'trending-up',
        ];
    }
}
