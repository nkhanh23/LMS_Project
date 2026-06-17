<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    @include('frontend.section.link')
    <title>StackLearn - Instructor Login</title>
    @include('frontend.section.script')
    @include('frontend.section.style')
    <style>
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
            0% { background-position: 0 0; }
            100% { background-position: 0 1000px; }
        }

        .binary-rain {
            background: linear-gradient(180deg, rgba(102, 217, 239, 0) 0%, rgba(102, 217, 239, 0.15) 50%, rgba(102, 217, 239, 0) 100%);
            background-size: 100% 200%;
            animation: rain 3s linear infinite;
        }

        @keyframes rain {
            0% { background-position: 0% 0%; }
            100% { background-position: 0% 200%; }
        }

        .scanlines {
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%),
                linear-gradient(90deg, rgba(255, 0, 0, 0.04), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.04));
            background-size: 100% 2px, 3px 100%;
            pointer-events: none;
        }
    </style>
</head>

<body class="font-sans bg-cyber-dark text-text-primary selection:bg-brand selection:text-black overflow-x-hidden min-h-screen flex items-center justify-center py-10">
    <div class="fixed inset-0 z-50 scanlines pointer-events-none opacity-40"></div>

    <div class="absolute inset-0 z-0 bg-[#1E1E2E] overflow-hidden">
        <div class="absolute inset-x-0 bottom-[-50%] h-[150%] w-full retro-grid opacity-30"></div>
        <div class="absolute inset-0 flex justify-between px-10 opacity-20 pointer-events-none">
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4" style="animation-delay: 0s;">10101010</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4" style="animation-delay: 1.5s;">00110011</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4" style="animation-delay: 0.5s;">11100011</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4" style="animation-delay: 2s;">01010101</div>
            <div class="text-cyber-cyan text-xs writing-vertical-rl binary-rain h-full w-4 hidden sm:block" style="animation-delay: 1s;">10011001</div>
        </div>
    </div>

    <div class="relative z-10 w-full max-w-[520px] p-4">
        <div class="border-4 border-black bg-cyber-dark/95 pixel-shadow">
            <div class="border-b-4 border-black bg-cyber-surface/80 p-8 text-center">
                <div class="flex items-center gap-2 justify-center mb-4">
                    <div class="flex flex-col gap-0.5">
                        <div class="w-3.5 h-3.5 bg-brand pixel-border"></div>
                        <div class="w-3.5 h-3.5 bg-cyber-cyan pixel-border"></div>
                        <div class="w-3.5 h-3.5 bg-pink-500 pixel-border"></div>
                    </div>
                    <span class="text-3xl font-black tracking-tighter uppercase italic text-text-primary">StackLearn</span>
                </div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-black/50 border-2 border-black text-brand text-xs font-bold uppercase tracking-widest">
                    <i class="fas fa-chalkboard-teacher"></i>
                    INSTRUCTOR CORE
                </div>
                <p class="mt-3 text-xs text-text-secondary font-mono uppercase tracking-widest animate-pulse">
                    // INITIALIZING INSTRUCTOR SESSION //
                </p>
            </div>

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

                    <div class="group">
                        <label class="mb-2 flex items-center gap-2 text-text-primary text-sm font-bold uppercase tracking-wide">
                            <i class="fas fa-envelope text-cyber-cyan text-base"></i>
                            Email
                        </label>
                        <div class="relative">
                            <input name="email" value="{{ old('email') }}"
                                class="w-full bg-black/50 border-2 border-slate-700 text-text-primary p-4 font-bold text-lg focus:outline-none focus:border-brand focus:shadow-[0_0_10px_rgba(166,226,46,0.3)] placeholder-text-secondary/40 transition-all duration-150 rounded-none tracking-wider"
                                placeholder="instructor@example.com" spellcheck="false" type="email" required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 h-2 w-2 bg-brand animate-pulse"></div>
                        </div>
                    </div>

                    <div class="group mt-6">
                        <label class="mb-2 flex items-center gap-2 text-text-primary text-sm font-bold uppercase tracking-wide">
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

                    <button type="submit"
                        class="relative mt-6 w-full h-16 bg-brand border-2 border-black pixel-shadow text-black font-black tracking-widest uppercase hover:-translate-y-0.5 hover:translate-x-0.5 active:translate-y-0.5 active:translate-x-0.5 transition-all duration-75 flex items-center justify-center gap-3">
                        <i class="fas fa-power-off font-bold text-lg"></i>
                        [ ĐĂNG NHẬP GIẢNG VIÊN ]
                    </button>
                </form>
            </div>

            <div class="bg-cyber-surface/50 px-4 py-3 border-t-4 border-black flex justify-between items-center text-[10px] sm:text-xs text-text-secondary/70 uppercase font-bold tracking-wider">
                <span class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-brand animate-ping"></span>
                    STATUS: INSTRUCTOR_GATEWAY
                </span>
                <span>v1.0.8-bit</span>
            </div>
        </div>
    </div>
</body>

</html>
