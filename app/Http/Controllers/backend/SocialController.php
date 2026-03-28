<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function googleLogin()
    {
        //chuyển hướng người dùng tới trang đăng nhập google
        return Socialite::driver('google')->redirect();
    }

    public function googleAuthentication()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->first();
            if (!$user) {
                // Tạo hoặc đăng nhập người dùng
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'photo' => $googleUser->avatar,
                    'password' => Hash::make($googleUser->id),
                    'role' => 'user',
                ]);
            }
            // Đăng nhập người dùng
            Auth::login($user);
            // Chuyển hướng người dùng đến trang dashboard
            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi đăng nhập bằng Google');
        }
    }
}
