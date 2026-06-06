<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
            // Chuyển hướng người dùng đến trang dashboard theo role
            return $this->redirectUserByRole($user);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Lỗi khi đăng nhập bằng Google');
        }
    }

    public function facebookLogin()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookAuthentication()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            // 1. Kiểm tra xem email có tồn tại không
            $email = $facebookUser->getEmail();

            // 2. Nếu Facebook không trả về email, hãy tạo một email giả định dựa trên ID 
            // để không bị lỗi NOT NULL của Database hiện tại.
            if (!$email) {
                $email = $facebookUser->getId() . '@facebook.com';
            }

            $user = User::where('email', $email)->first();

            if ($user) {
                Auth::login($user);
            } else {
                // Đảm bảo các trường NOT NULL trong schema của bạn luôn có giá trị
                $user = User::create([
                    'name'     => $facebookUser->getName() ?? 'User_' . $facebookUser->getId(),
                    'email'    => $email,
                    'password' => Hash::make(\Illuminate\Support\Str::random(16)), // Mật khẩu ngẫu nhiên
                    'role'     => 'user', // Gán role mặc định để qua được Middleware
                    'status'   => '1',
                ]);
                Auth::login($user);
            }

            // Chú ý: Kiểm tra hàm redirectUserByRole() của bạn có đang chặn user mới không
            return $this->redirectUserByRole(Auth::user());
        } catch (Exception $e) {
            // Ghi log để kiểm tra sau này
            Log::error('Social Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Không thể đăng nhập: ' . $e->getMessage());
        }
    }

    private function redirectUserByRole($user)
    {
        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'instructor') return redirect()->route('instructor.dashboard');
        return redirect()->route('user.dashboard');
    }
}
