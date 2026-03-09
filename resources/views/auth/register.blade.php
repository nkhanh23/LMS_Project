{{--
<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
--}}
<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    @include('backend.section.link')
    <title>StackLearn - Create Account</title>
    @include('backend.section.script')
    @include('backend.section.css')
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body
    class="font-display bg-background-light dark:bg-background-dark overflow-hidden selection:bg-primary selection:text-black">
    <!-- Scanline Overlay -->
    <div class="fixed inset-0 z-50 scanlines bg-scanlines pointer-events-none opacity-40"></div>
    <!-- Background Wrapper -->
    <div class="relative flex h-screen w-full flex-col overflow-hidden items-center justify-center">
        <!-- Retro Grid Background -->
        <div class="absolute inset-0 z-0 bg-[#1E1E2E] overflow-hidden">
            <div class="absolute inset-x-0 bottom-[-50%] h-[150%] w-full retro-grid opacity-30"></div>
            <!-- Binary Code Abstract Representation -->
            <div class="absolute inset-0 flex justify-between px-10 opacity-20 pointer-events-none">
                <div class="text-cyber-blue text-xs writing-vertical-rl binary-rain h-full w-4"
                    style="animation-delay: 0s;">10101010</div>
                <div class="text-cyber-blue text-xs writing-vertical-rl binary-rain h-full w-4"
                    style="animation-delay: 1.5s;">00110011</div>
                <div class="text-cyber-blue text-xs writing-vertical-rl binary-rain h-full w-4"
                    style="animation-delay: 0.5s;">11100011</div>
                <div class="text-cyber-blue text-xs writing-vertical-rl binary-rain h-full w-4"
                    style="animation-delay: 2s;">01010101</div>
                <div class="text-cyber-blue text-xs writing-vertical-rl binary-rain h-full w-4 hidden sm:block"
                    style="animation-delay: 1s;">10011001</div>
            </div>
        </div>
        <!-- Main Register Card -->
        <div class="relative z-10 w-full max-w-[520px] p-2 max-h-[95vh] overflow-y-auto scrollbar-hide">
            <div class="border-4 border-black bg-[#151711] shadow-block">
                <!-- Header Section -->
                <div class="border-b-4 border-black bg-[#23261c] p-6 text-center">
                    <div class="mb-2 flex justify-center text-primary">
                        <span class="material-symbols-outlined text-5xl">person_add</span>
                    </div>
                    <h1
                        class="text-primary text-2xl sm:text-3xl font-black leading-tight tracking-tight uppercase glitch-text">
                        &gt; STACKLEARN &lt;<br />
                        <span class="text-lg sm:text-xl">[ NEW OPERATOR ]</span>
                    </h1>
                    <p class="mt-2 text-sm text-[#afb79e] font-medium tracking-widest uppercase animate-pulse">
                        // Register to join the system //
                    </p>
                </div>
                <!-- Form Section -->
                <div class="p-6 sm:p-8 flex flex-col gap-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <!-- First Name & Last Name Row -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- First Name Input -->
                            <div class="group flex-1">
                                <label
                                    class="mb-2 flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wide">
                                    <span class="material-symbols-outlined text-cyber-blue text-lg">person</span>
                                    Tên
                                </label>
                                <div class="relative">
                                    <input name="name" value="{{ old('name') }}"
                                        class="w-full bg-black border-2 border-cyber-blue/50 text-primary p-3 sm:p-4 font-bold text-base sm:text-lg focus:outline-none focus:border-cyber-blue focus:shadow-neon placeholder-[#4b523d] transition-all duration-150 rounded-none tracking-wider"
                                        placeholder="Tên" spellcheck="false" type="text" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <!-- Email Input -->
                        <div class="group mt-4">
                            <label
                                class="mb-2 flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-cyber-blue text-lg">mail</span>
                                Email
                            </label>
                            <div class="relative">
                                <input name="email" value="{{ old('email') }}"
                                    class="w-full bg-black border-2 border-cyber-blue/50 text-primary p-3 sm:p-4 font-bold text-base sm:text-lg focus:outline-none focus:border-cyber-blue focus:shadow-neon placeholder-[#4b523d] transition-all duration-150 rounded-none tracking-wider"
                                    placeholder="Email" spellcheck="false" type="email" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 h-2 w-2 bg-primary animate-pulse">
                                </div>
                            </div>
                        </div>
                        <!-- Password Input -->
                        <div class="group mt-4">
                            <label
                                class="mb-2 flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-cyber-blue text-lg">vpn_key</span>
                                Mật khẩu
                            </label>
                            <div class="relative">
                                <input name="password"
                                    class="w-full bg-black border-2 border-cyber-blue/50 text-primary p-3 sm:p-4 font-bold text-base sm:text-lg focus:outline-none focus:border-cyber-blue focus:shadow-neon placeholder-[#4b523d] transition-all duration-150 rounded-none tracking-widest"
                                    placeholder="••••••••" type="password" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                        <!-- Confirm Password Input -->
                        <div class="group mt-4">
                            <label
                                class="mb-2 flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-cyber-blue text-lg">vpn_key</span>
                                Xác nhận mật khẩu
                            </label>
                            <div class="relative">
                                <input name="password_confirmation"
                                    class="w-full bg-black border-2 border-cyber-blue/50 text-primary p-3 sm:p-4 font-bold text-base sm:text-lg focus:outline-none focus:border-cyber-blue focus:shadow-neon placeholder-[#4b523d] transition-all duration-150 rounded-none tracking-widest"
                                    placeholder="••••••••" type="password" required />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                        <!-- Already have account link -->
                        <div class="mt-4 text-center">
                            <a class="text-[#afb79e] text-sm font-medium uppercase tracking-wide hover:text-primary transition-colors duration-150"
                                href="{{ route('login') }}">
                                // Already have an account? <span class="underline decoration-dashed">Login here</span>
                                //
                            </a>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit"
                            class="relative mt-4 w-full h-14 sm:h-16 bg-primary border-b-4 border-r-4 border-black active:border-0 active:translate-y-1 active:translate-x-1 transition-all duration-75 group overflow-hidden hover:brightness-110">
                            <div
                                class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            </div>
                            <span
                                class="relative flex items-center justify-center gap-3 text-[#151711] text-base sm:text-lg font-black tracking-widest uppercase">
                                <span class="material-symbols-outlined font-bold">how_to_reg</span>
                                [ CREATE ACCOUNT ]
                            </span>
                        </button>
                    </form>
                    <!-- Social Login Divider -->
                    <div class="flex items-center gap-3 mt-6">
                        <div class="flex-1 h-[2px] bg-cyber-blue/20"></div>
                        <span class="text-[#5c634d] text-[10px] font-bold uppercase tracking-widest">// Or Connect Via
                            //</span>
                        <div class="flex-1 h-[2px] bg-cyber-blue/20"></div>
                    </div>
                    <!-- Social Login Buttons -->
                    <div class="flex gap-3 mt-4">
                        <a href="#"
                            class="flex-1 flex items-center justify-center gap-2 h-12 bg-black border-2 border-cyber-blue/30 hover:border-cyber-blue hover:shadow-neon text-white font-bold text-sm uppercase tracking-wider transition-all duration-150">
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
                        <a href="#"
                            class="flex-1 flex items-center justify-center gap-2 h-12 bg-black border-2 border-cyber-blue/30 hover:border-cyber-blue hover:shadow-neon text-white font-bold text-sm uppercase tracking-wider transition-all duration-150">
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
                    class="bg-[#1a1d15] px-4 py-3 border-t-4 border-black flex justify-between items-center text-[10px] sm:text-xs text-[#5c634d] uppercase font-bold tracking-wider">
                    <span class="flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-cyan-500 animate-ping"></span>
                        Status: AWAITING_REGISTRATION
                    </span>
                    <span>v1.0.8-bit</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
