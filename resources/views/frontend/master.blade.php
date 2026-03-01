<!DOCTYPE html>
<html class="dark" lang="en">

<head>

    <title>StackLearn - Build Your Knowledge Stack</title>

    @include('frontend.section.link')
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: '#A6E22E',
                        'cyber-dark': '#1E1E2E',
                        'cyber-surface': '#2A2A3C',
                        'cyber-cyan': '#66D9EF',
                        'cyber-grid': 'rgba(0, 255, 255, 0.05)',
                        'text-primary': '#F8F8F2',
                        'text-secondary': '#A0A0B0',
                    },
                    fontFamily: {
                        sans: ['"Space Grotesk"', 'sans-serif'],
                        pixel: ['"Press Start 2P"', 'cursive'],
                        mono: ['"VT323"', 'monospace'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #1E1E2E;
            background-image: linear-gradient(to right, rgba(0, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(0, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .pixel-border {
            box-shadow: 0 -3px 0 0 black, 0 3px 0 0 black, -3px 0 0 0 black, 3px 0 0 0 black;
        }

        .pixel-shadow {
            box-shadow: 4px 4px 0 0 rgba(0, 0, 0, 1);
        }

        .pixel-shadow-sm {
            box-shadow: 3px 3px 0 0 rgba(0, 0, 0, 1);
        }

        .pixel-button-hover {
            transition: all 0.1s ease;
        }

        .pixel-button-hover:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 0 rgba(0, 0, 0, 1);
        }

        .pixel-button-hover:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 0 rgba(0, 0, 0, 1);
        }

        .cursor-blink {
            animation: blink 1s step-end infinite;
        }

        @keyframes blink {

            from,
            to {
                border-color: transparent
            }

            50% {
                border-color: #A6E22E;
            }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        img {
            image-rendering: pixelated;
        }

        /* Preloader */
        .preloader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #1E1E2E;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s;
        }

        .preloader.hide {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #2A2A3C;
            border-top-color: #A6E22E;
            animation: spin 0.8s steps(8) infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Mobile menu */
        .offcanvas {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .offcanvas.active {
            transform: translateX(0);
        }

        .offcanvas-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .offcanvas-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        /* Cart dropdown */
        .cart-dropdown {
            display: none;
        }

        .cart-trigger:hover .cart-dropdown {
            display: block;
        }

        /* Counter animation */
        @keyframes scan {
            0% {
                top: 0;
            }

            100% {
                top: 100%;
            }
        }

        /* Scroll animations */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Category dropdown */
        .cat-dropdown {
            display: none;
        }

        .cat-trigger:hover .cat-dropdown {
            display: block;
        }

        /* Tab active */
        .tab-btn.active {
            background-color: #A6E22E;
            color: #000;
        }

        /* Tooltip on course hover */
        .course-tooltip {
            display: none;
            position: absolute;
            z-index: 50;
        }

        .course-card-wrap:hover .course-tooltip {
            display: block;
        }
    </style>
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

    @yield('content')
    <!-- ========== MAIN CONTENT ========== -->


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
</body>

</html>
