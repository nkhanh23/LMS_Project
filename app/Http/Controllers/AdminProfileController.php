<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Service\PasswordUpdateService;
use App\Service\ProfileService;

class AdminProfileController extends Controller
{
    protected $profileService, $passwordUpdateService;
    public function __construct(ProfileService $profileService, PasswordUpdateService $passwordUpdateService)
    {
        $this->profileService = $profileService;
        $this->passwordUpdateService = $passwordUpdateService;
    }
    public function index()
    {
        return view('backend.admin.profile.index');
    }

    public function store(ProfileRequest $request)
    {
        //Truyền dữ liệu và file tới Service
        $this->profileService->saveProfile($request->validated(), $request->file('photo'));
        return redirect()->back()->with('success', 'Ảnh đã được cập nhật');
    }

    public function setting()
    {
        return view('backend.admin.profile.setting');
    }

    public function passwordSetting(PasswordUpdateRequest $request)
    {
        //Truyền dữ liệu và file tới Service
        $this->passwordUpdateService->updatePassword($request->validated());
        return redirect()->back()->with('success', 'Mật khẩu đã được cập nhật');
    }
}
