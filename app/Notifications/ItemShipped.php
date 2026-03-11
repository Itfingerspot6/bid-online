<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Transaction;

class ItemShipped extends Notification
{
    use Queueable;

    public $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
            'type' => 'shipping',
            'title' => 'Barang Dikirim!',
            'message' => "Barang dari lelang \"{$this->transaction->auction->title}\" telah dikirim. Resi: {$this->transaction->tracking_number}",
            'auction_slug' => $this->transaction->auction->slug,
            'tracking_number' => $this->transaction->tracking_number,
            'icon' => 'truck',
        ];
    }
}
