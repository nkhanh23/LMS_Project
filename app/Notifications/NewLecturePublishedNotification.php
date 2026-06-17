<?php

namespace App\Notifications;

use App\Models\CourseLecture;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLecturePublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected CourseLecture $lecture;
    protected Course $course;

    public function __construct(CourseLecture $lecture, Course $course)
    {
        $this->lecture = $lecture;
        $this->course = $course;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_lecture',
            'icon' => 'fas fa-play-circle',
            'icon_color' => 'brand',
            'title' => 'Bài học mới!',
            'body' => "Khóa học \"{$this->course->course_name}\" vừa cập nhật bài học mới: \"{$this->lecture->lecture_title}\". Vào học ngay!",
            'url' => route('course.lecture.watch', [
                'slug' => $this->course->course_name_slug,
                'lecture_id' => $this->lecture->id,
            ]),
            'course_id' => $this->course->id,
            'lecture_id' => $this->lecture->id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('course.lecture.watch', [
            'slug' => $this->course->course_name_slug,
            'lecture_id' => $this->lecture->id,
        ]);

        return (new MailMessage)
            ->subject("Bài học mới: {$this->lecture->lecture_title}")
            ->greeting("Xin chào {$notifiable->name}!")
            ->line("Khóa học **{$this->course->course_name}** vừa được cập nhật bài học mới:")
            ->line("**{$this->lecture->lecture_title}**")
            ->action('Vào học ngay', $url)
            ->line('Chúc bạn học tập hiệu quả!');
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastType(): string
    {
        return 'notification.new_lecture';
    }
}
