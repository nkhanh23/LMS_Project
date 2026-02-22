<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    @include('backend.section.link')
    <title>StackLearn Instructor Login</title>
    @include('backend.section.script')
    @include('backend.section.css')
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
        <!-- Main Login Card -->
        <div class="relative z-10 w-full max-w-[520px] p-2">
            <div class="border-4 border-black bg-[#151711] shadow-block">
                <!-- Header Section -->
                <div class="border-b-4 border-black bg-[#23261c] p-8 text-center">
                    <div class="mb-2 flex justify-center text-primary">
                        <span class="material-symbols-outlined text-6xl">terminal</span>
                    </div>
                    <h1
                        class="text-primary text-3xl sm:text-4xl font-black leading-tight tracking-tight uppercase glitch-text">
                        &gt; STACKLEARN &lt;<br />
                        <span class="text-xl sm:text-2xl">[ INSTRUCTOR CORE ]</span>
                    </h1>
                    <p class="mt-2 text-sm text-[#afb79e] font-medium tracking-widest uppercase animate-pulse">
                        // Enter credentials to initialize system //
                    </p>
                </div>
                <!-- Form Section -->
                <div class="p-8 flex flex-col gap-6">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Operator ID Input -->
                        <div class="group">
                            <label
                                class="mb-2 flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-cyber-blue text-lg">badge</span>
                                Email
                            </label>
                            <div class="relative">
                                <input name="email" value="{{ old('email') }}"
                                    class="w-full bg-black border-2 border-cyber-blue/50 text-primary p-4 font-bold text-lg focus:outline-none focus:border-cyber-blue focus:shadow-neon placeholder-[#4b523d] transition-all duration-150 rounded-none tracking-wider"
                                    placeholder="Email" spellcheck="false" type="email" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 h-2 w-2 bg-primary animate-pulse">
                                </div>
                            </div>
                        </div>
                        <!-- Security Key Input -->
                        <div class="group mt-6">
                            <label
                                class="mb-2 flex items-center gap-2 text-white text-sm font-bold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-cyber-blue text-lg">vpn_key</span>
                                Password
                            </label>
                            <div class="relative">
                                <input name="password"
                                    class="w-full bg-black border-2 border-cyber-blue/50 text-primary p-4 font-bold text-lg focus:outline-none focus:border-cyber-blue focus:shadow-neon placeholder-[#4b523d] transition-all duration-150 rounded-none tracking-widest"
                                    placeholder="••••••••" type="password" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit"
                            class="relative mt-4 w-full h-16 bg-primary border-b-4 border-r-4 border-black active:border-0 active:translate-y-1 active:translate-x-1 transition-all duration-75 group overflow-hidden hover:brightness-110">
                            <div
                                class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            </div>
                            <span
                                class="relative flex items-center justify-center gap-3 text-[#151711] text-lg font-black tracking-widest uppercase">
                                <span class="material-symbols-outlined font-bold">power_settings_new</span>
                                [ INITIALIZE ACCESS ]
                            </span>
                        </button>
                    </form>
                </div>
                <!-- Card Footer -->
                <div
                    class="bg-[#1a1d15] px-4 py-3 border-t-4 border-black flex justify-between items-center text-[10px] sm:text-xs text-[#5c634d] uppercase font-bold tracking-wider">
                    <span class="flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-red-500 animate-ping"></span>
                        Status: UNSECURED
                    </span>
                    <span>v1.0.8-bit</span>
                </div>
            </div>
            <!-- Page Footer -->
            <footer class="mt-8 text-center">
                <p class="text-[#5c634d] text-xs font-normal uppercase tracking-widest">
                    © 2024 StackLearn System. All rights reserved.
                </p>
                <div class="mt-2 flex justify-center gap-4 text-[#5c634d] text-[10px] uppercase">
                    <a class="hover:text-primary transition-colors hover:underline decoration-dashed"
                        href="#">Protocol</a>
                    <span>|</span>
                    <a class="hover:text-primary transition-colors hover:underline decoration-dashed"
                        href="#">Override</a>
                    <span>|</span>
                    <a class="hover:text-primary transition-colors hover:underline decoration-dashed"
                        href="#">Help</a>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>
