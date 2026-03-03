<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositReceived extends Notification
{
    use Queueable;

    public $amount;

    /**
     * Create a new notification instance.
     */
    public function __construct(float $amount)
    {
        $this->amount = $amount;
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
            'type' => 'deposit',
            'title' => 'Deposit Berhasil',
            'message' => "Saldo sebesar Rp " . number_format($this->amount, 0, ',', '.') . " telah ditambahkan ke akun Anda.",
            'amount' => $this->amount,
            'icon' => 'credit-card',
        ];
    }
}
