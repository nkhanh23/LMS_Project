<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\CourseLecture;
use App\Models\User;
use App\Models\Conversation;

// xác thực user có quyền join channel của lecture này không
Broadcast::channel('lecture.{lectureId}', function (User $user, $lectureId) {
    $lecture = CourseLecture::find($lectureId);

    if (!$lecture) return false;

    // tái sử dụng logic kiểm tra quyền của User đối với Course
    if ($user->hasAccessToCourse($lecture->course)) {
        // trả về thông tin user để hiển thị ai đang online
        return ['id' => $user->id, 'name' => $user->name, 'photo' => $user->photo];
    }
    return false;
});

// xác thực user có quyền join channel của cuộc hội thoại này không
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    // chỉ cho phép kết nối nếu user hiện tại là student hoặc instructor của cuộc hội thoại này
    return $user->id === $conversation->student_id || $user->id === $conversation->instructor_id;
});

// kênh xác thực cho Notifications
Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});
