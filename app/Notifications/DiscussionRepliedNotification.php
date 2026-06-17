<?php

namespace App\Notifications;

use App\Models\LectureDiscussion;
use App\Models\CourseLecture;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DiscussionRepliedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected LectureDiscussion $reply;
    protected User $replier;
    protected CourseLecture $lecture;

    public function __construct(LectureDiscussion $reply, User $replier, CourseLecture $lecture)
    {
        $this->reply = $reply;
        $this->replier = $replier;
        $this->lecture = $lecture;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $course = $this->lecture->course;
        $url = '#';

        if ($course) {
            $url = route('course.lecture.watch', [
                'slug' => $course->course_name_slug,
                'lecture_id' => $this->lecture->id,
            ]);
        }

        return [
            'type' => 'discussion_replied',
            'icon' => 'fas fa-comment',
            'icon_color' => 'cyber-cyan',
            'title' => 'Có phản hồi mới!',
            'body' => "{$this->replier->name} đã phản hồi thảo luận của bạn trong bài học \"{$this->lecture->lecture_title}\".",
            'url' => $url,
            'lecture_id' => $this->lecture->id,
            'discussion_id' => $this->reply->id,
            'replier_id' => $this->replier->id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $course = $this->lecture->course;
        $url = '#';

        if ($course) {
            $url = route('course.lecture.watch', [
                'slug' => $course->course_name_slug,
                'lecture_id' => $this->lecture->id,
            ]);
        }

        return (new MailMessage)
            ->subject("💬 Có phản hồi mới trong thảo luận của bạn")
            ->greeting("Xin chào {$notifiable->name}!")
            ->line("**{$this->replier->name}** đã phản hồi thảo luận của bạn trong bài học:")
            ->line("**{$this->lecture->lecture_title}**")
            ->line("Nội dung: \"{$this->truncate($this->reply->content, 100)}\"")
            ->action('Xem phản hồi', $url)
            ->line('Hãy tiếp tục trao đổi để hiểu sâu hơn!');
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastType(): string
    {
        return 'notification.discussion_replied';
    }

    private function truncate(string $text, int $length): string
    {
        return mb_strlen($text) > $length
            ? mb_substr($text, 0, $length) . '...'
            : $text;
    }
}
