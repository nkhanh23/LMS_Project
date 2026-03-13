<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <title>{{ $course->course_title }} - StackLearn</title>
    @include('frontend.section.link')
    @include('frontend.section.style')
    <style>
        body {
            overflow: hidden;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hp-bar-bg {
            background: #1a2e18;
            border: 2px solid #000;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1E1E2E;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #A6E22E;
            border: 2px solid #000;
        }

        .learning-header {
            height: 60px;
            background: #2A2A3C;
            border-bottom: 4px solid #000;
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 50;
        }

        .learning-container {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .video-content {
            flex: 1;
            overflow-y: auto;
            background: #1E1E2E;
        }

        .curriculum-sidebar {
            width: 400px;
            border-left: 4px solid #000;
            background: #2A2A3C;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 1024px) {
            body {
                overflow: auto;
                height: auto;
            }
            .learning-container {
                flex-direction: column;
                height: auto;
            }
            .curriculum-sidebar {
                width: 100%;
                border-left: 0;
                border-top: 4px solid #000;
                height: auto;
            }
        }
    </style>
</head>

<body class="text-text-primary font-sans selection:bg-brand selection:text-black">
    <!-- ========== PRELOADER ========== -->
    @include('frontend.section.loader')
    <!-- ========== PRELOADER ========== -->

    <!-- Custom Learning Header -->
    <header class="learning-header bg-cyber-surface border-b-4 border-black flex justify-between items-center px-4 shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('chi-tiet', $course->course_name_slug) }}" class="text-white hover:text-brand transition-colors">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand flex items-center justify-center border-2 border-black">
                    <span class="text-black font-black text-xs">SL</span>
                </div>
                <h1 class="text-sm md:text-base font-bold text-white uppercase tracking-tight truncate max-w-[200px] md:max-w-md">
                    {{ $course->course_title }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="hidden md:flex items-center gap-2">
                <div class="w-32 h-2 bg-black border border-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-brand" style="width: 65%"></div>
                </div>
                <span class="text-[10px] font-bold text-brand uppercase">65% Hoàn thành</span>
            </div>
            
            <div class="flex items-center gap-4 text-slate-400 text-sm font-bold uppercase">
                <button class="hover:text-white transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-note-sticky"></i>
                    <span class="hidden sm:inline">Ghi chú</span>
                </button>
                <button class="hover:text-white transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-circle-question"></i>
                    <span class="hidden sm:inline">Hướng dẫn</span>
                </button>
            </div>
        </div>
    </header>

    <main class="learning-container">
        <!-- Left Side: Video Player & Tabs -->
        <div class="video-content custom-scrollbar">
            <div class="w-full">
                @include('frontend.pages.learning.video-player')
            </div>
        </div>

        <!-- Right Side: Curriculum Sidebar -->
        <div class="curriculum-sidebar custom-scrollbar shrink-0">
            @include('frontend.pages.learning.video-list')
        </div>
    </main>

    @include('frontend.section.script')
    @stack('scripts')
</body>

</html>
