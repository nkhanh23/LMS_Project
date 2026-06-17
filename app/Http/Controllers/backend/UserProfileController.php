<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountDeletionRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserNotificationSettingsRequest;
use App\Services\PasswordUpdateService;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    protected $profileService, $passwordUpdateService;
    public function __construct(ProfileService $profileService, PasswordUpdateService $passwordUpdateService)
    {
        $this->profileService = $profileService;
        $this->passwordUpdateService = $passwordUpdateService;
    }
    public function index()
    {
        return view('backend.user.profile.index');
    }
    public function edit()
    {
        return view('backend.user.profile.edit');
    }

    public function setting()
    {
        $settings = Auth::user()->setting()->firstOrCreate([]);

        return view('backend.user.setting.index', compact('settings'));
    }

    public function store(ProfileRequest $request)
    {
        //Truyền dữ liệu và file tới Service
        $this->profileService->saveProfile($request->validated(), $request->file('photo'));
        return redirect()->back()->with('success', 'Ảnh đã được cập nhật');
    }
    public function passwordSetting(PasswordUpdateRequest $request)
    {
        //Truyền dữ liệu và file tới Service
        $this->passwordUpdateService->updatePassword($request->validated());
        return redirect()->back()->with('success', 'Mật khẩu đã được cập nhật');
    }
    public function emailSetting() {}

    public function updateNotificationSettings(UserNotificationSettingsRequest $request)
    {
        Auth::user()->setting()->updateOrCreate([], [
            'notify_new_courses' => $request->boolean('notify_new_courses'),
            'notify_learning_reminders' => $request->boolean('notify_learning_reminders'),
            'notify_quiz_discussion_messages' => $request->boolean('notify_quiz_discussion_messages'),
        ]);

        return redirect()->route('user.setting')->with('success', 'Cài đặt thông báo đã được cập nhật.');
    }

    public function requestAccountDeletion(AccountDeletionRequest $request)
    {
        Auth::user()->setting()->updateOrCreate([], [
            'account_deletion_requested_at' => now(),
            'account_deletion_reason' => $request->input('account_deletion_reason'),
        ]);

        return redirect()->route('user.setting')->with('warning', 'Yêu cầu xóa tài khoản đã được ghi nhận và đang chờ xử lý.');
    }
}
