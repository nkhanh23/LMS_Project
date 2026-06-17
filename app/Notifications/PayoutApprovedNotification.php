<?php

namespace App\Notifications;

use App\Models\PayoutRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected PayoutRequest $payout;

    public function __construct(PayoutRequest $payout)
    {
        $this->payout = $payout;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $amount = number_format($this->payout->amount, 0, ',', '.');

        return [
            'type' => 'payout_approved',
            'icon' => 'fas fa-wallet',
            'icon_color' => 'green-500',
            'title' => 'Rút tiền thành công!',
            'body' => "Yêu cầu rút tiền mã số #{$this->payout->id} trị giá {$amount} VNĐ của bạn đã được duyệt thành công.",
            'url' => route('instructor.revenue.dashboard'),
            'payout_id' => $this->payout->id,
            'amount' => $this->payout->amount,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format($this->payout->amount, 0, ',', '.');

        return (new MailMessage)
            ->subject("Yêu cầu rút tiền #{$this->payout->id} đã được duyệt")
            ->greeting("Xin chào {$notifiable->name}!")
            ->line("Yêu cầu rút tiền của bạn đã được Admin phê duyệt thành công.")
            ->line("**Số tiền:** {$amount} VNĐ")
            ->line("**Mã yêu cầu:** #{$this->payout->id}")
            ->line("**Mã giao dịch:** {$this->payout->transaction_reference}")
            ->action('Xem chi tiết doanh thu', route('instructor.revenue.dashboard'))
            ->line('Cảm ơn bạn đã đồng hành cùng StackLearn!');
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastType(): string
    {
        return 'notification.payout_approved';
    }
}
