<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\CourseLecture;
use App\Models\User;
use App\Models\Conversation;

// Xác thực user có quyền join channel của lecture này không
Broadcast::channel('lecture.{lectureId}', function (User $user, $lectureId) {
    $lecture = CourseLecture::find($lectureId);

    if (!$lecture) return false;

    // Tái sử dụng logic kiểm tra quyền của User đối với Course
    if ($user->hasAccessToCourse($lecture->course)) {
        // Trả về thông tin user để hiển thị ai đang online (Presence Channel)
        return ['id' => $user->id, 'name' => $user->name, 'photo' => $user->photo];
    }
    return false;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    // Chỉ cho phép kết nối nếu User hiện tại là Student hoặc Instructor của cuộc hội thoại này
    return $user->id === $conversation->student_id || $user->id === $conversation->instructor_id;
});

// Kênh xác thực cho Notifications (Private Channel)
Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

