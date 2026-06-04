<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    @include('backend.section.link')
    <title>StackLearn - Xác Thực Email</title>
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
<body class="font-display bg-background-light dark:bg-background-dark overflow-hidden selection:bg-primary selection:text-black">
    <!-- Scanline Overlay -->
    <div class="fixed inset-0 z-50 scanlines bg-scanlines pointer-events-none opacity-40"></div>
    
    <!-- Background Wrapper -->
    <div class="relative flex h-screen w-full flex-col overflow-hidden items-center justify-center">
        <!-- Retro Grid Background -->
        <div class="absolute inset-0 z-0 bg-[#1E1E2E] overflow-hidden">
            <div class="absolute inset-x-0 bottom-[-50%] h-[150%] w-full retro-grid opacity-30"></div>
            <!-- Binary Code Rain -->
            <div class="absolute inset-0 flex justify-between px-10 opacity-20 pointer-events-none">
                <div class="text-cyber-blue text-xs writing-vertical-rl binary-rain h-full w-4" style="animation-delay: 0s;">10101010</div>
                <div class="text-cyber-blue text-xs writing-vertical-rl binary-rain h-full w-4" style="animation-delay: 1.5s;">00110011</div>
                <div class="text-cyber-blue text-xs writing-vertical-rl binary-rain h-full w-4" style="animation-delay: 0.5s;">11100011</div>
                <div class="text-cyber-blue text-xs writing-vertical-rl binary-rain h-full w-4" style="animation-delay: 2s;">01010101</div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="relative z-10 w-full max-w-[520px] p-2">
            <div class="border-4 border-black bg-[#151711] shadow-block">
                <!-- Header Section -->
                <div class="border-b-4 border-black bg-[#23261c] p-6 text-center">
                    <div class="mb-2 flex justify-center text-primary">
                        <span class="material-symbols-outlined text-5xl">mail</span>
                    </div>
                    <h1 class="text-primary text-2xl sm:text-3xl font-black leading-tight tracking-tight uppercase glitch-text">
                        &gt; STACKLEARN &lt;<br />
                        <span class="text-lg sm:text-xl">[ VERIFY EMAIL ]</span>
                    </h1>
                    <p class="mt-2 text-sm text-[#afb79e] font-medium tracking-widest uppercase animate-pulse">
                        // Awaiting Account Activation //
                    </p>
                </div>

                <!-- Body Section -->
                <div class="p-6 sm:p-8 flex flex-col gap-4 text-center">
                    <div class="text-white text-base sm:text-lg font-bold tracking-wide leading-relaxed">
                        <p class="text-primary">// ĐĂNG KÝ BƯỚC ĐẦU THÀNH CÔNG //</p>
                        <p class="mt-4 text-gray-300">Chúng tôi đã gửi một liên kết xác nhận đăng ký tài khoản đến địa chỉ email của bạn.</p>
                        <p class="mt-2 text-[#afb79e]">Vui lòng kiểm tra hộp thư (bao gồm cả thư rác/spam) và click vào liên kết để kích hoạt tài khoản của bạn.</p>
                    </div>

                    <!-- Back to Login Button -->
                    <a href="{{ route('login') }}"
                        class="relative mt-6 w-full h-14 sm:h-16 bg-[#23261c] border-2 border-primary text-primary flex items-center justify-center gap-3 active:translate-y-1 active:translate-x-1 transition-all duration-75 group overflow-hidden hover:bg-primary hover:text-black">
                        <span class="relative flex items-center justify-center gap-3 font-black tracking-widest uppercase">
                            <span class="material-symbols-outlined font-bold">arrow_back</span>
                            [ QUAY VỀ ĐĂNG NHẬP ]
                        </span>
                    </a>
                </div>

                <!-- Card Footer -->
                <div class="bg-[#1a1d15] px-4 py-3 border-t-4 border-black flex justify-between items-center text-[10px] sm:text-xs text-[#5c634d] uppercase font-bold tracking-wider">
                    <span class="flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-yellow-500 animate-ping"></span>
                        Status: AWAITING_ACTIVATION
                    </span>
                    <span>v1.0.8-bit</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
