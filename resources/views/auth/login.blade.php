{{-- 
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
--}}
<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    @include('frontend.section.link')
    <title>StackLearn - Đăng Nhập</title>
    @include('frontend.section.script')
    @include('frontend.section.style')
    <style>
        /* Retro Grid Animation */
        .retro-grid {
            background-image:
                linear-gradient(to right, rgba(102, 217, 239, 0.08) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(102, 217, 239, 0.08) 1px, transparent 1px);
            background-size: 40px 40px;
            transform: perspective(500px) rotateX(60deg);
            transform-origin: center top;
            animation: grid-move 20s linear infinite;
        }

        @keyframes grid-move {
            0% {
                background-position: 0 0;
            }
            100% {
                background-position: 0 1000px;
            }
        }

        /* Floating Binary Effect */
        .binary-rain {
            background: linear-gradient(180deg,
                    rgba(102, 217, 239, 0) 0%,
                    rgba(102, 217, 239, 0.15) 50%,
                    rgba(102, 217, 239, 0) 100%);
            background-size: 100% 200%;
            animation: rain 3s linear infinite;
        }

        @keyframes rain {
            0% {
                background-position: 0% 0%;
            }
            100% {
                background-position: 0% 200%;
            }
        }

        /* Scanline Overlay */
        .scanlines {
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), 
                        linear-gradient(90deg, rgba(255, 0, 0, 0.04), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.04));
            background-size: 100% 2px, 3px 100%;
            pointer-events: none;
        }
    </style>
</head>

<body
    class="font-sans bg-cyber-dark text-text-primary selection:bg-brand selection:text-black overflow-x-hidden min-h-screen flex items-center justify-center py-10">
    <!-- Scanline Overlay -->
    <div class="fixed inset-0 z-50 scanlines pointer-events-none opacity-40"></div>
    
    <!-- Background Wrapper -->
    <div class="absolute inset-0 z-0 bg-[#1E1E2E] overflow-hidden">
        <div class="absolute inset-x-0 bottom-[-50%] h-[150%] w-full retro-grid opacity-30"></div>
        <!-- Binary Code Abstract Representation -->
        <div class="absolute inset-0 flex justify-between px-10 opacity-20 pointer-events-none">
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4"
                style="animation-delay: 0s;">10101010</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4"
                style="animation-delay: 1.5s;">00110011</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4"
                style="animation-delay: 0.5s;">11100011</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4"
                style="animation-delay: 2s;">01010101</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4 hidden sm:block"
                style="animation-delay: 1s;">10011001</div>
        </div>
    </div>

    <!-- Main Login Card -->
    <div class="relative z-10 w-full max-w-[520px] p-4">
        <div class="border-4 border-black bg-cyber-dark/95 pixel-shadow">
            <!-- Header Section -->
            <div class="border-b-4 border-black bg-cyber-surface/80 p-8 text-center">
                <!-- StackLearn Logo -->
                <div class="flex items-center gap-2 justify-center mb-4">
                    <div class="flex flex-col gap-0.5">
                        <div class="w-3.5 h-3.5 bg-brand pixel-border"></div>
                        <div class="w-3.5 h-3.5 bg-cyber-cyan pixel-border"></div>
                        <div class="w-3.5 h-3.5 bg-pink-500 pixel-border"></div>
                    </div>
                    <span class="text-3xl font-black tracking-tighter uppercase italic text-text-primary">StackLearn</span>
                </div>
                <p class="mt-2 text-xs text-text-secondary font-mono uppercase tracking-widest animate-pulse">
                    // INITIALIZING SESSION //
                </p>
            </div>
            
            <!-- Form Section -->
            <div class="p-8 flex flex-col gap-6 bg-cyber-surface/30">
                @if (session('status'))
                    <div class="bg-black/50 border-2 border-brand text-brand p-4 font-mono text-sm tracking-wider uppercase">
                        // STATUS: {{ session('status') }} //
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-black/50 border-2 border-pink-500 text-pink-500 p-4 font-mono text-sm tracking-wider uppercase">
                        // ERROR: {{ session('error') }} //
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Email Input -->
                    <div class="group">
                        <label
                            class="mb-2 flex items-center gap-2 text-text-primary text-sm font-bold uppercase tracking-wide">
                            <i class="fas fa-envelope text-cyber-cyan text-base"></i>
                            Email
                        </label>
                        <div class="relative">
                            <input name="email" value="{{ old('email') }}"
                                class="w-full bg-black/50 border-2 border-slate-700 text-text-primary p-4 font-bold text-lg focus:outline-none focus:border-brand focus:shadow-[0_0_10px_rgba(166,226,46,0.3)] placeholder-text-secondary/40 transition-all duration-150 rounded-none tracking-wider"
                                placeholder="name@example.com" spellcheck="false" type="email" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 h-2 w-2 bg-brand animate-pulse">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Input -->
                    <div class="group mt-6">
                        <label
                            class="mb-2 flex items-center gap-2 text-text-primary text-sm font-bold uppercase tracking-wide">
                            <i class="fas fa-key text-cyber-cyan text-base"></i>
                            Mật khẩu
                        </label>
                        <div class="relative">
                            <input name="password"
                                class="w-full bg-black/50 border-2 border-slate-700 text-text-primary p-4 font-bold text-lg focus:outline-none focus:border-brand focus:shadow-[0_0_10px_rgba(166,226,46,0.3)] placeholder-text-secondary/40 transition-all duration-150 rounded-none tracking-widest"
                                placeholder="••••••••" type="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>
                    
                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <a class="text-text-secondary text-sm font-medium uppercase tracking-wide hover:text-brand transition-colors duration-150"
                            href="{{ route('register') }}">
                            // Bạn chưa có tài khoản? <span class="underline decoration-dashed">Đăng ký tại đây</span> //
                        </a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit"
                        class="relative mt-6 w-full h-16 bg-brand border-2 border-black pixel-shadow text-black font-black tracking-widest uppercase hover:-translate-y-0.5 hover:translate-x-0.5 active:translate-y-0.5 active:translate-x-0.5 transition-all duration-75 block text-center flex items-center justify-center gap-3">
                        <i class="fas fa-power-off font-bold text-lg"></i>
                        [ ĐĂNG NHẬP HỆ THỐNG ]
                    </button>
                </form>
                
                <!-- Social Login Divider -->
                <div class="flex items-center gap-3 mt-6">
                    <div class="flex-1 h-[2px] bg-cyber-cyan/20"></div>
                    <span class="text-text-secondary/60 text-[10px] font-bold uppercase tracking-widest">// Kết nối qua //</span>
                    <div class="flex-1 h-[2px] bg-cyber-cyan/20"></div>
                </div>
                
                <!-- Social Login Buttons -->
                <div class="flex gap-4 mt-4">
                    <a href="{{ route('auth.google') }}"
                        class="flex-1 flex items-center justify-center gap-2 h-12 bg-black/50 border-2 border-slate-700 hover:border-brand hover:shadow-[0_0_10px_rgba(166,226,46,0.2)] text-white font-bold text-sm uppercase tracking-wider transition-all duration-150">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"
                                fill="#4285F4" />
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853" />
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18A10.96 10.96 0 0 0 1 12c0 1.77.42 3.45 1.18 4.93l3.66-2.84z"
                                fill="#FBBC05" />
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335" />
                        </svg>
                        Google
                    </a>

                    <a href="{{ route('auth.facebook') }}"
                        class="flex-1 flex items-center justify-center gap-2 h-12 bg-black/50 border-2 border-slate-700 hover:border-brand hover:shadow-[0_0_10px_rgba(166,226,46,0.2)] text-white font-bold text-sm uppercase tracking-wider transition-all duration-150">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#1877F2">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                        Facebook
                    </a>
                </div>
            </div>
            
            <!-- Card Footer -->
            <div
                class="bg-cyber-surface/50 px-4 py-3 border-t-4 border-black flex justify-between items-center text-[10px] sm:text-xs text-text-secondary/70 uppercase font-bold tracking-wider">
                <span class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-brand animate-ping"></span>
                    STATUS: ACTIVE_GATEWAY
                </span>
                <span>v1.0.8-bit</span>
            </div>
        </div>
    </div>
</body>

</html>
