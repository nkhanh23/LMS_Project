<!DOCTYPE html>
<html class="dark" lang="vi">

<head>
    @include('frontend.section.link')
    <title>StackLearn - Xác Thực Email</title>
    @include('frontend.section.script')
    @include('frontend.section.style')
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

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
        <!-- Binary Code Rain -->
        <div class="absolute inset-0 flex justify-between px-10 opacity-20 pointer-events-none">
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4" style="animation-delay: 0s;">
                10101010</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4"
                style="animation-delay: 1.5s;">00110011</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4"
                style="animation-delay: 0.5s;">11100011</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4"
                style="animation-delay: 2s;">01010101</div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="relative z-10 w-full max-w-[520px] p-4">
        <div class="border-4 border-black bg-cyber-dark/95 pixel-shadow">
            <!-- Header Section -->
            <div class="border-b-4 border-black bg-cyber-surface/80 p-6 text-center">
                <!-- StackLearn Logo -->
                <div class="flex items-center gap-2 justify-center mb-4">
                    <div class="flex flex-col gap-0.5">
                        <div class="w-3.5 h-3.5 bg-brand pixel-border"></div>
                        <div class="w-3.5 h-3.5 bg-cyber-cyan pixel-border"></div>
                        <div class="w-3.5 h-3.5 bg-pink-500 pixel-border"></div>
                    </div>
                    <span
                        class="text-3xl font-black tracking-tighter uppercase italic text-text-primary">StackLearn</span>
                </div>
                <p class="mt-2 text-xs text-text-secondary font-mono uppercase tracking-widest animate-pulse">
                    // Awaiting Email Verification //
                </p>
            </div>

            <!-- Body Section -->
            <div class="p-6 sm:p-8 flex flex-col gap-4 bg-cyber-surface/30">
                <div class="text-text-primary text-base sm:text-lg font-bold tracking-wide leading-relaxed text-center">
                    <p class="text-brand">// XÁC THỰC ĐỊA CHỈ EMAIL //</p>
                    <p class="mt-4 text-text-primary text-sm sm:text-base">Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui
                        lòng xác nhận địa chỉ email bằng cách click vào liên kết chúng tôi vừa gửi cho bạn.</p>
                    <p class="mt-2 text-text-secondary text-sm">Nếu bạn không nhận được email, hãy click vào nút bên
                        dưới để nhận một email xác thực mới.</p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div
                        class="border-2 border-brand/50 bg-brand/5 p-4 text-brand font-mono text-xs sm:text-sm tracking-wider text-center">
                        // Một liên kết xác thực mới đã được gửi tới địa chỉ email bạn đã cung cấp khi đăng ký. //
                    </div>
                @endif

                <div class="mt-2 flex flex-col gap-4">
                    <!-- Resend Button Form -->
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="relative w-full h-14 sm:h-16 bg-brand border-2 border-black pixel-shadow text-black font-black tracking-widest uppercase hover:-translate-y-0.5 hover:translate-x-0.5 active:translate-y-0.5 active:translate-x-0.5 transition-all duration-75 block text-center flex items-center justify-center gap-3">
                            <i class="fas fa-paper-plane font-bold text-lg"></i>
                            [ GỬI LẠI EMAIL XÁC THỰC ]
                        </button>
                    </form>

                    <!-- Logout Form -->
                    <form method="POST" action="{{ route('logout') }}" class="w-full text-center mt-2">
                        @csrf
                        <button type="submit"
                            class="text-text-secondary text-sm font-medium uppercase tracking-wide hover:text-brand transition-colors duration-150 underline decoration-dashed">
                            // Đăng xuất / Log Out //
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card Footer -->
            <div
                class="bg-cyber-surface/50 px-4 py-3 border-t-4 border-black flex justify-between items-center text-[10px] sm:text-xs text-text-secondary/70 uppercase font-bold tracking-wider">
                <span class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-yellow-500 animate-ping"></span>
                    Status: AWAITING_VERIFICATION
                </span>
                <span>v1.0.8-bit</span>
            </div>
        </div>
    </div>
</body>

</html>
