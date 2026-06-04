<?php

namespace App\Notifications;

use App\Models\InstructorRiskScore;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FraudRiskAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $instructor;
    protected int $riskScore;

    public function __construct(User $instructor, int $riskScore)
    {
        $this->instructor = $instructor;
        $this->riskScore = $riskScore;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'fraud_risk_alert',
            'icon' => 'fas fa-exclamation-triangle',
            'icon_color' => 'red-500',
            'title' => '⚠️ Cảnh báo rủi ro!',
            'body' => "CẢNH BÁO: Giảng viên {$this->instructor->name} có chỉ số rủi ro tăng vọt lên {$this->riskScore} do phát hiện hành vi nghi vấn.",
            'url' => route('admin.instructor-requests.index'),
            'instructor_id' => $this->instructor->id,
            'risk_score' => $this->riskScore,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🚨 [CẢNH BÁO] Giảng viên {$this->instructor->name} - Rủi ro cao ({$this->riskScore} điểm)")
            ->greeting("Cảnh báo Admin!")
            ->line("Hệ thống phát hiện giảng viên có chỉ số rủi ro bất thường:")
            ->line("👤 **Giảng viên:** {$this->instructor->name} ({$this->instructor->email})")
            ->line("📊 **Chỉ số rủi ro:** {$this->riskScore} điểm")
            ->line("Vui lòng kiểm tra ngay để đảm bảo an toàn nền tảng.")
            ->action('Xem chi tiết giảng viên', route('admin.instructor-requests.index'))
            ->line('Thông báo này được gửi tự động từ hệ thống StackLearn.');
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastType(): string
    {
        return 'notification.fraud_risk_alert';
    }
}
