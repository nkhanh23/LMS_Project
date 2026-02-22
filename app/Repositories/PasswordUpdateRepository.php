<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Hash;

class PasswordUpdateRepository
{
    use FileUploadTrait;
    public function updatePassword($data)
    {
        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id)->first();

        //Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors((['current_password' => 'Mật khẩu hiện tại không chính xác']));
        }

        //Cập nhật mật khẩu mơis
        $user->password = Hash::make($data['new_password']);
        $user->save();

        return $user;
    }
}
