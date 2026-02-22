<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>StackLearn - Build Your Knowledge Stack</title>
    <!-- Google Fonts: Space Grotesk for that technical, modern feel -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <!-- Tailwind CSS CDN with Plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: '#a6e22c', // Custom neon green from prompt
                        'cyber-dark': '#1E1E2E',
                        'cyber-grid': 'rgba(0, 255, 255, 0.05)',
                    },
                    fontFamily: {
                        sans: ['"Space Grotesk"', 'sans-serif'],
                    },
                    borderRadius: {
                        'none': '0',
                        'sm': '0.125rem',
                        'DEFAULT': '0.25rem', // ROUND_FOUR equivalent roughly
                        'md': '0.375rem',
                        'lg': '0.5rem',
                        'xl': '0.75rem',
                    }
                }
            }
        }
    </script>
    <style data-purpose="custom-pixel-styling">
        /* Grid background effect */
        body {
            background-color: #1E1E2E;
            background-image:
                linear-gradient(to right, rgba(0, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Pixel Art Specific Borders */
        .pixel-border {
            box-shadow:
                0 -4px 0 0 black,
                0 4px 0 0 black,
                -4px 0 0 0 black,
                4px 0 0 0 black;
        }

        .pixel-shadow {
            box-shadow: 4px 4px 0 0 rgba(0, 0, 0, 1);
        }

        .pixel-button-hover:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 0 rgba(0, 0, 0, 1);
        }

        .pixel-button-hover:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 0 rgba(0, 0, 0, 1);
        }

        /* Blinking Cursor for Terminal Search */
        .cursor-blink {
            animation: blink 1s step-end infinite;
        }

        @keyframes blink {

            from,
            to {
                border-color: transparent
            }

            50% {
                border-color: #a6e22c;
            }
        }

        /* Floating Animation for AI Tutor */
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

        /* Rendering for crisp pixels */
        img {
            image-rendering: pixelated;
        }
    </style>
</head>

<body class="text-white font-sans selection:bg-brand selection:text-black min-h-screen">
    <!-- BEGIN: MainHeader -->
    <header class="sticky top-0 z-50 bg-cyber-dark border-b-4 border-black px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3 cursor-pointer" data-purpose="site-logo">
                <div class="flex flex-col gap-1">
                    <div class="w-4 h-4 bg-brand pixel-border"></div>
                    <div class="w-4 h-4 bg-cyan-400 pixel-border"></div>
                    <div class="w-4 h-4 bg-pink-500 pixel-border"></div>
                </div>
                <span class="text-2xl font-bold tracking-tighter uppercase italic">StackLearn</span>
            </div>
            <!-- Search Bar -->
            <div class="hidden md:flex flex-1 max-w-xl mx-8 relative" data-purpose="search-container">
                <div class="w-full bg-black/50 border-2 border-slate-700 p-2 flex items-center gap-2">
                    <span class="text-brand font-mono">&gt;</span>
                    <input
                        class="bg-transparent border-none outline-none focus:ring-0 text-sm w-full placeholder:text-slate-500"
                        placeholder="Search for quests..." type="text" />
                    <div class="w-[2px] h-5 bg-brand cursor-blink border-r-2"></div>
                </div>
            </div>
            <!-- Navigation Actions -->
            <nav class="flex items-center gap-6" data-purpose="top-navigation">
                <button class="hover:text-brand transition-colors hidden sm:block">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewbox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </button>
                <div class="relative cursor-pointer" data-purpose="shopping-cart">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewbox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                    <span
                        class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 pixel-border">2</span>
                </div>
                <button
                    class="bg-brand text-black font-bold py-2 px-6 pixel-border pixel-shadow pixel-button-hover transition-all uppercase text-sm">
                    Sign In / Sign Up
                </button>
            </nav>
        </div>
    </header>
    <!-- END: MainHeader -->
    <!-- BEGIN: HeroSection -->
    <main>
        <section class="max-w-7xl mx-auto px-6 py-20 lg:py-32 flex flex-col lg:flex-row items-center gap-16">
            <!-- Hero Content -->
            <div class="lg:w-1/2 space-y-8" data-purpose="hero-text">
                <h1 class="text-6xl md:text-8xl font-black leading-tight tracking-tight uppercase">
                    Build Your <br />
                    <span class="text-brand">Knowledge</span> <br />
                    Stack
                </h1>
                <p class="text-xl text-slate-400 max-w-lg">
                    Master the terminal. Conquer the cloud. The ultimate 16-bit learning experience for modern
                    developers.
                </p>
                <div class="flex flex-wrap gap-6 pt-4">
                    <button
                        class="bg-brand text-black font-bold text-xl py-4 px-10 border-4 border-black pixel-shadow pixel-button-hover transition-all uppercase">
                        Start Quest
                    </button>
                    <button
                        class="bg-transparent text-white font-bold text-xl py-4 px-10 border-4 border-white hover:bg-white hover:text-black transition-all uppercase">
                        Watch Preview
                    </button>
                </div>
            </div>
            <!-- Hero Visual -->
            <div class="lg:w-1/2 flex justify-center relative" data-purpose="hero-artwork">
                <!-- Pixel Art Placeholder: Holographic Book & Server -->
                <div
                    class="relative w-full max-w-md aspect-square bg-slate-900/50 border-4 border-slate-800 flex items-center justify-center pixel-border">
                    <img alt="16-bit Server Rack Art" class="w-full h-full object-cover opacity-80"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuA0PGHCPiU5l359hRK_HK9mqEFn37ui_TO4xOLzgYwt4Q86z2iVlV_3Y-TX_83-DC-kGFKMn6y18kDGfbKlnsN11beJcAMJAEfaZ4NHkl2HjFOnabrIW7XOSCssSv127Bhp9IsIIAYYAIQbs8l_yDowMxNOtnlsoaaVASsA5ns5V_EtWwlYRcjiGjgYf-A1EOZkJtxB6dBwbdRn_LCVzpEL9G5SjcML3tXCEGpqX2mNe_3nflzbl4PrnHQfOq-z1CN17-t0fXOGABTE" />
                    <!-- Floating Glow Effect -->
                    <div class="absolute inset-0 bg-brand/10 mix-blend-screen animate-pulse"></div>
                </div>
            </div>
        </section>
        <!-- END: HeroSection -->
        <!-- BEGIN: CourseArea -->
        <section class="max-w-7xl mx-auto px-6 py-20">
            <div class="flex items-center justify-between mb-12">
                <h2 class="text-4xl font-bold uppercase tracking-widest border-l-8 border-brand pl-4">Hot Quests</h2>
                <a class="text-brand underline underline-offset-8 hover:text-white transition-colors uppercase font-bold"
                    href="#">View All</a>
            </div>
            <!-- Inventory Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Course Card 1 -->
                <article
                    class="bg-white text-black border-4 border-black pixel-shadow hover:-translate-y-2 transition-transform cursor-pointer"
                    data-purpose="course-card">
                    <div class="h-48 bg-slate-200 border-b-4 border-black relative">
                        <img alt="Laravel Course" class="w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCuCWjEGVB6FWSwlc1TZH-KdUprgQewQzKHWUmGhTrgBO1kpizj5goAJAie-RVE47HgBdNLhaCl5rUCtq_GIFlF-PSbiE87OP68AE-O-OYeYDc5wb_omlwNuuusin3XkEoYWCcoQkFJU3D_L962Nto2bCHWt6bNldvG81LE-G5G8M6ULbRxWVHYGC_lSs4sxi_YfpIDVguGETY5EeqAv0QtC0oPHEReNEkYhMXgP0erush9BJk1_HrCPchGxDhEC9Qyswu0SFxzUAU3" />
                        <span
                            class="absolute top-3 right-3 bg-yellow-400 border-2 border-black text-[10px] font-bold px-2 py-1">EXPERT</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-2xl font-bold leading-none">Laravel Masterclass</h3>
                            <span class="text-red-500 text-xl">♥</span>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-500">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                            <span class="text-black text-sm font-bold ml-2">(42)</span>
                        </div>
                        <p class="text-slate-600 font-medium">Build robust PHP applications with elegant syntax and
                            pixel-perfect logic.</p>
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-2xl font-black">$49.99</span>
                            <button class="bg-black text-white px-4 py-2 text-sm font-bold uppercase pixel-border">Add
                                to Kit</button>
                        </div>
                    </div>
                </article>
                <!-- Course Card 2 -->
                <article
                    class="bg-white text-black border-4 border-black pixel-shadow hover:-translate-y-2 transition-transform cursor-pointer"
                    data-purpose="course-card">
                    <div class="h-48 bg-slate-200 border-b-4 border-black relative">
                        <img alt="Java Course" class="w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCH1oeCbfCus8q_izkCQgAjuTJ9q2xXDjYuf1ZA-SoBxsq2nA8AvHNOFbHoDIUMqmvvVFckp86BuQL0izwW6Npa4_czqXvr__DUYGIRhwbuWxQqCTJuiTeUszYrJJCD-e1N0HEntgsj6jC3mXzr1CjRl3jnc9hlFkof-Dwsx-y9frx5XRDIwDUqpYibfyUaeiIssDHHPS66VmUiP8IS-n5TDX5Cv91UdG4p6dsqg35AfxW3_VtZXuQ1yqoPPDkaVpGe2n1tnu0tp0xK" />
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-2xl font-bold leading-none">Java Fundamentals</h3>
                            <span class="text-red-500 text-xl">♡</span>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-500">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span
                                class="text-slate-300">★</span>
                            <span class="text-black text-sm font-bold ml-2">(128)</span>
                        </div>
                        <p class="text-slate-600 font-medium">The bedrock of systems programming. Write once, run
                            anywhere in the multiverse.</p>
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-2xl font-black">$39.99</span>
                            <button class="bg-black text-white px-4 py-2 text-sm font-bold uppercase pixel-border">Add
                                to Kit</button>
                        </div>
                    </div>
                </article>
                <!-- Course Card 3 -->
                <article
                    class="bg-white text-black border-4 border-black pixel-shadow hover:-translate-y-2 transition-transform cursor-pointer"
                    data-purpose="course-card">
                    <div class="h-48 bg-slate-200 border-b-4 border-black relative">
                        <img alt="Design Patterns Course" class="w-full h-full object-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBfMVWC3_yHTZjL8SyB7fAJdmK_MhQpNg7DAZEUukJ5QWxpmsutQxNGPbceXotXOniY4X4MWvJesKr36_hgLTV0pLiMGd6l1TYIACCiHPlVl2GOpv01ywEO2Aj9KLuxfgQ-aaNyjVtrk90wyxNMoxCKpb36Ggp_OlVUKbFU1n8wlxL1XJnxqJmJFS2eZBoLKMSUPRBZYdMMMrcTkXEpUmURla2mLPWMqGvdHXCJ4KW4VIP_Qrv9SglpYadg-tFRA5WGoLzkre6UUDEZ" />
                        <span
                            class="absolute top-3 right-3 bg-yellow-400 border-2 border-black text-[10px] font-bold px-2 py-1 italic">POPULAR</span>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-2xl font-bold leading-none">Design Patterns</h3>
                            <span class="text-red-500 text-xl">♥</span>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-500">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                            <span class="text-black text-sm font-bold ml-2">(89)</span>
                        </div>
                        <p class="text-slate-600 font-medium">Architecture for the elite. Clean code is the ultimate
                            weapon in your arsenal.</p>
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-2xl font-black">$54.99</span>
                            <button class="bg-black text-white px-4 py-2 text-sm font-bold uppercase pixel-border">Add
                                to Kit</button>
                        </div>
                    </div>
                </article>
            </div>
        </section>
        <!-- END: CourseArea -->
    </main>
    <!-- BEGIN: FloatingAITutor -->
    <div class="fixed bottom-8 right-8 z-[100] group" data-purpose="ai-tutor-container">
        <div class="floating cursor-pointer relative">
            <!-- Glow -->
            <div class="absolute inset-0 bg-brand/30 blur-xl rounded-full scale-150 animate-pulse"></div>
            <!-- Pixel Robot Head -->
            <div
                class="w-20 h-20 bg-slate-800 border-4 border-black pixel-border flex flex-col items-center justify-center gap-2 relative z-10 overflow-hidden">
                <div class="flex gap-3">
                    <div class="w-3 h-3 bg-brand animate-pulse"></div>
                    <div class="w-3 h-3 bg-brand animate-pulse"></div>
                </div>
                <div class="w-10 h-1 bg-brand/50"></div>
                <!-- Scanning line -->
                <div class="absolute top-0 left-0 w-full h-1 bg-brand/20 animate-[scan_2s_linear_infinite]"></div>
            </div>
            <!-- Tooltip / Label -->
            <div
                class="absolute bottom-full right-0 mb-4 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                <div
                    class="bg-black border-2 border-brand text-brand px-3 py-1 text-xs font-bold uppercase whitespace-nowrap">
                    AI Tutor Online
                </div>
            </div>
        </div>
    </div>
    <!-- END: FloatingAITutor -->
    <style data-purpose="extra-animations">
        @keyframes scan {
            0% {
                top: 0;
            }

            100% {
                top: 100%;
            }
        }
    </style>
</body>

</html>
