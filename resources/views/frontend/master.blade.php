<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <title>StackLearn - Build Your Knowledge Stack</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('frontend.section.link')

    @include('frontend.section.style')
</head>

<body class="text-text-primary font-sans selection:bg-brand selection:text-black min-h-screen">

    <!-- ========== PRELOADER ========== -->
    @include('frontend.section.loader')
    <!-- ========== PRELOADER ========== -->


    <!-- ========== HEADER AREA ========== -->
    @include('frontend.section.header')

    <!-- Mobile Search (below header) -->
    @include('frontend.section.mobileSearch')

    <!-- Off-canvas Mobile Menu -->
    @include('frontend.section.offCanvas')

    <!-- ========== MAIN CONTENT ========== -->
    @yield('content')

    <!-- ========== FOOTER AREA ========== -->
    @include('frontend.section.footer')

    <!-- ========== SCROLL TOP ========== -->
    <button id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed bottom-8 left-8 z-50 w-12 h-12 bg-brand text-black border-2 border-black pixel-shadow flex items-center justify-center opacity-0 pointer-events-none transition-all hover:-translate-y-1">
        <i class="fas fa-chevron-up font-bold"></i>
    </button>

    <!-- ========== FLOATING AI TUTOR ========== -->
    @include('frontend.section.chatBot')

    <!-- ========== SCRIPTS ========== -->
    @include('frontend.section.script')
    @stack('script')
</body>

</html>
