@extends('frontend.master')
@section('content')
    <main>
        <!-- ===== HERO AREA (Owl Carousel Slider) ===== -->
        @include('frontend.section.hero')

        <!-- ===== FEATURE AREA ===== -->
        @include('frontend.section.feature')

        <!-- ===== CATEGORY AREA ===== -->
        @include('frontend.section.category')

        <!-- ===== COURSE AREA (TABBED) ===== -->
        @include('frontend.section.course-area-first')

        <!-- ===== COURSE CAROUSEL (Trending) ===== -->
        <section class="max-w-7xl mx-auto px-6 py-8 md:py-16">
            <h2 class="font-pixel text-xl lg:text-2xl text-cyber-cyan mb-10 fade-up">TRENDING STACKS</h2>
            <div class="owl-carousel course-carousel">
                <div class="bg-cyber-surface border-2 border-black pixel-shadow p-4">
                    <div class="h-36 bg-cyber-dark border border-black mb-3 flex items-center justify-center"><i
                            class="fab fa-python text-5xl text-yellow-400/30"></i></div>
                    <h4 class="font-bold text-sm mb-1">Python for AI</h4>
                    <p class="text-xs text-text-secondary mb-2">★★★★★ · $44.99</p>
                    <div class="w-full bg-black h-2 border border-black">
                        <div class="h-full bg-brand" style="width:72%"></div>
                    </div>
                    <p class="text-[10px] text-text-secondary mt-1">72% enrolled</p>
                </div>
                <div class="bg-cyber-surface border-2 border-black pixel-shadow p-4">
                    <div class="h-36 bg-cyber-dark border border-black mb-3 flex items-center justify-center"><i
                            class="fab fa-docker text-5xl text-cyber-cyan/30"></i></div>
                    <h4 class="font-bold text-sm mb-1">Docker & K8s</h4>
                    <p class="text-xs text-text-secondary mb-2">★★★★★ · $54.99</p>
                    <div class="w-full bg-black h-2 border border-black">
                        <div class="h-full bg-cyber-cyan" style="width:85%"></div>
                    </div>
                    <p class="text-[10px] text-text-secondary mt-1">85% enrolled</p>
                </div>
                <div class="bg-cyber-surface border-2 border-black pixel-shadow p-4">
                    <div class="h-36 bg-cyber-dark border border-black mb-3 flex items-center justify-center"><i
                            class="fab fa-js text-5xl text-yellow-300/30"></i></div>
                    <h4 class="font-bold text-sm mb-1">TypeScript Pro</h4>
                    <p class="text-xs text-text-secondary mb-2">★★★★☆ · $34.99</p>
                    <div class="w-full bg-black h-2 border border-black">
                        <div class="h-full bg-yellow-400" style="width:60%"></div>
                    </div>
                    <p class="text-[10px] text-text-secondary mt-1">60% enrolled</p>
                </div>
                <div class="bg-cyber-surface border-2 border-black pixel-shadow p-4">
                    <div class="h-36 bg-cyber-dark border border-black mb-3 flex items-center justify-center"><i
                            class="fab fa-aws text-5xl text-orange-400/30"></i></div>
                    <h4 class="font-bold text-sm mb-1">AWS Cloud</h4>
                    <p class="text-xs text-text-secondary mb-2">★★★★★ · $69.99</p>
                    <div class="w-full bg-black h-2 border border-black">
                        <div class="h-full bg-orange-400" style="width:90%"></div>
                    </div>
                    <p class="text-[10px] text-text-secondary mt-1">90% enrolled</p>
                </div>
            </div>
        </section>

        <!-- ===== FUNFACT AREA ===== -->
        <section class="border-y-4 border-black bg-cyber-surface py-16" id="funfact">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="fade-up">
                    <div class="w-16 h-16 bg-brand/20 border-2 border-brand mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-brand text-2xl"></i>
                    </div><span class="counter text-4xl font-bold font-pixel text-brand" data-count="120">0</span>
                    <p class="text-text-secondary text-sm mt-2 uppercase font-bold">Instructors</p>
                </div>
                <div class="fade-up">
                    <div
                        class="w-16 h-16 bg-cyber-cyan/20 border-2 border-cyber-cyan mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-users text-cyber-cyan text-2xl"></i>
                    </div><span class="counter text-4xl font-bold font-pixel text-cyber-cyan" data-count="8500">0</span>
                    <p class="text-text-secondary text-sm mt-2 uppercase font-bold">Players</p>
                </div>
                <div class="fade-up">
                    <div
                        class="w-16 h-16 bg-pink-500/20 border-2 border-pink-500 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-layer-group text-pink-400 text-2xl"></i>
                    </div><span class="counter text-4xl font-bold font-pixel text-pink-400" data-count="450">0</span>
                    <p class="text-text-secondary text-sm mt-2 uppercase font-bold">Stacks</p>
                </div>
                <div class="fade-up">
                    <div
                        class="w-16 h-16 bg-yellow-400/20 border-2 border-yellow-400 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-star text-yellow-400 text-2xl"></i>
                    </div><span class="counter text-4xl font-bold font-pixel text-yellow-400" data-count="8">0</span>
                    <p class="text-text-secondary text-sm mt-2 uppercase font-bold">Years of XP</p>
                </div>
            </div>
        </section>

        <!-- ===== CTA AREA ===== -->
        <section class="relative py-20 overflow-hidden">
            <div class="absolute top-10 left-10 w-32 h-32 border-4 border-brand/20 rounded-full animate-spin"
                style="animation-duration:20s"></div>
            <div class="absolute bottom-10 right-16 w-48 h-48 border-4 border-cyber-cyan/15 rounded-full animate-spin"
                style="animation-duration:30s"></div>
            <div class="absolute top-1/2 left-1/3 w-20 h-20 border-2 border-pink-500/20 rounded-full animate-pulse">
            </div>
            <div class="max-w-3xl mx-auto px-6 text-center relative z-10 fade-up">
                <h2 class="font-pixel text-xl lg:text-3xl text-brand mb-6">READY TO START YOUR QUEST?</h2>
                <p class="text-text-secondary text-lg mb-8 font-mono">Join thousands of devs leveling up every day.
                    Your adventure begins with a single click.</p>
                <a href="#"
                    class="inline-block bg-brand text-black font-bold py-4 px-10 text-lg border-4 border-black pixel-shadow pixel-button-hover uppercase">Begin
                    Adventure <i class="fas fa-arrow-right ml-2"></i></a>
            </div>
        </section>

        <!-- ===== TESTIMONIAL AREA ===== -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            <h2 class="font-pixel text-xl lg:text-2xl text-brand mb-10 text-center fade-up">PLAYER FEEDBACK</h2>
            <div class="owl-carousel testimonial-carousel">
                <div class="bg-cyber-surface border-2 border-black pixel-shadow p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 bg-brand/20 border-2 border-brand flex items-center justify-center font-pixel text-brand text-sm">
                            TN</div>
                        <div>
                            <p class="font-bold">Thanh Nguyen</p>
                            <p class="text-xs text-text-secondary">Web Developer</p>
                        </div>
                    </div>
                    <div class="text-yellow-400 text-sm mb-3">★★★★★</div>
                    <p class="text-text-secondary text-sm italic">"StackLearn made learning feel like a game. I earned
                        15 badges in 3 months!"</p>
                </div>
                <div class="bg-cyber-surface border-2 border-black pixel-shadow p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 bg-cyber-cyan/20 border-2 border-cyber-cyan flex items-center justify-center font-pixel text-cyber-cyan text-sm">
                            AK</div>
                        <div>
                            <p class="font-bold">Akira Kato</p>
                            <p class="text-xs text-text-secondary">Data Scientist</p>
                        </div>
                    </div>
                    <div class="text-yellow-400 text-sm mb-3">★★★★★</div>
                    <p class="text-text-secondary text-sm italic">"The quest system keeps me motivated. Each node feels
                        like a milestone."</p>
                </div>
                <div class="bg-cyber-surface border-2 border-black pixel-shadow p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 bg-pink-500/20 border-2 border-pink-500 flex items-center justify-center font-pixel text-pink-400 text-sm">
                            ML</div>
                        <div>
                            <p class="font-bold">Maria Lopez</p>
                            <p class="text-xs text-text-secondary">UX Designer</p>
                        </div>
                    </div>
                    <div class="text-yellow-400 text-sm mb-3">★★★★<span class="text-slate-600">★</span></div>
                    <p class="text-text-secondary text-sm italic">"The retro pixel aesthetic is so unique! Studying
                        here is addictive."</p>
                </div>
                <div class="bg-cyber-surface border-2 border-black pixel-shadow p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 bg-yellow-400/20 border-2 border-yellow-400 flex items-center justify-center font-pixel text-yellow-400 text-sm">
                            DT</div>
                        <div>
                            <p class="font-bold">David Tran</p>
                            <p class="text-xs text-text-secondary">DevOps Engineer</p>
                        </div>
                    </div>
                    <div class="text-yellow-400 text-sm mb-3">★★★★★</div>
                    <p class="text-text-secondary text-sm italic">"From Docker basics to K8s mastery — the learning
                        paths are structured."</p>
                </div>
            </div>
        </section>

        <!-- ===== ABOUT AREA ===== -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="fade-up">
                    <h2 class="font-pixel text-xl lg:text-2xl text-brand mb-6">WHY STACKLEARN?</h2>
                    <p class="text-text-secondary mb-8 font-mono">We combine gamification with real-world expertise to
                        create an immersive learning platform that keeps you coming back for more XP.</p>
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-brand/20 border-2 border-brand flex items-center justify-center shrink-0">
                                <i class="fas fa-gamepad text-brand"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Gamified Learning</h4>
                                <p class="text-text-secondary text-sm">Earn XP, unlock badges, and track your progress
                                    through interactive quests.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-cyber-cyan/20 border-2 border-cyber-cyan flex items-center justify-center shrink-0">
                                <i class="fas fa-robot text-cyber-cyan"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">AI-Powered Tutoring</h4>
                                <p class="text-text-secondary text-sm">Our pixel bot assistant helps you whenever
                                    you're stuck on a node.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-pink-500/20 border-2 border-pink-500 flex items-center justify-center shrink-0">
                                <i class="fas fa-users text-pink-400"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Community Driven</h4>
                                <p class="text-text-secondary text-sm">Join a thriving community of 8500+ players
                                    worldwide.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-yellow-400/20 border-2 border-yellow-400 flex items-center justify-center shrink-0">
                                <i class="fas fa-infinity text-yellow-400"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1">Lifetime Access</h4>
                                <p class="text-text-secondary text-sm">Buy once, learn forever. All updates included at
                                    no extra cost.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fade-up flex justify-center">
                    <div
                        class="relative w-full max-w-md aspect-square bg-cyber-surface border-4 border-black pixel-shadow flex items-center justify-center">
                        <i class="fas fa-shield-alt text-9xl text-brand/10"></i>
                        <div class="absolute top-4 left-4 w-8 h-8 bg-brand animate-pulse"></div>
                        <div class="absolute bottom-4 right-4 w-8 h-8 bg-cyber-cyan animate-pulse"
                            style="animation-delay:0.5s"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== REGISTER AREA (Free Course) ===== -->
        <section class="border-y-4 border-black bg-gradient-to-r from-cyber-dark via-cyber-surface to-cyber-dark py-16">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="fade-up">
                    <h2 class="font-pixel text-xl lg:text-2xl text-brand mb-4">FREE STARTER PACK</h2>
                    <p class="text-text-secondary mb-6 font-mono">Sign up now and get access to 5 free stacks. No
                        credit card required. Start your learning path today!</p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 text-sm"><i class="fas fa-check-circle text-brand"></i><span>5
                                Free Beginner Stacks</span></div>
                        <div class="flex items-center gap-2 text-sm"><i
                                class="fas fa-check-circle text-brand"></i><span>Access to Community Forums</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm"><i
                                class="fas fa-check-circle text-brand"></i><span>Personalized Learning Path</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm"><i
                                class="fas fa-check-circle text-brand"></i><span>AI Tutor Support (Limited)</span>
                        </div>
                    </div>
                </div>
                <div class="fade-up">
                    <form class="bg-cyber-dark border-2 border-black pixel-shadow p-6 space-y-4">
                        <h3 class="font-pixel text-sm text-cyber-cyan mb-2">REGISTER NOW</h3>
                        <input type="text" placeholder="Your Name"
                            class="w-full bg-black/50 border-2 border-slate-700 p-3 text-sm text-text-primary placeholder:text-slate-500 focus:border-brand focus:ring-0" />
                        <input type="email" placeholder="Email Address"
                            class="w-full bg-black/50 border-2 border-slate-700 p-3 text-sm text-text-primary placeholder:text-slate-500 focus:border-brand focus:ring-0" />
                        <input type="tel" placeholder="Phone Number"
                            class="w-full bg-black/50 border-2 border-slate-700 p-3 text-sm text-text-primary placeholder:text-slate-500 focus:border-brand focus:ring-0" />
                        <button type="submit"
                            class="w-full bg-brand text-black font-bold py-3 text-sm uppercase border-2 border-black pixel-shadow pixel-button-hover">Get
                            Free Access <i class="fas fa-unlock ml-2"></i></button>
                    </form>
                </div>
            </div>
        </section>

        <!-- ===== PARTNER-LOGO AREA ===== -->
        @include('frontend.section.partner-logo-area')


        <!-- ===== BLOG AREA ===== -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            <div class="flex items-center justify-between mb-10 fade-up">
                <h2 class="font-pixel text-xl lg:text-2xl text-brand">LATEST NEWS</h2>
                <a href="#" class="text-brand text-sm font-bold uppercase hover:text-white transition-colors">View
                    All <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article
                    class="bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-transform fade-up">
                    <div class="h-44 bg-cyber-dark border-b-2 border-black flex items-center justify-center"><i
                            class="fas fa-newspaper text-5xl text-brand/20"></i></div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-3 text-xs text-text-secondary"><span><i
                                    class="fas fa-calendar mr-1"></i>Feb 20, 2026</span><span><i
                                    class="fas fa-user mr-1"></i>Admin</span></div>
                        <h3 class="font-bold text-lg leading-tight">Top 10 Programming Languages to Learn in 2026</h3>
                        <p class="text-text-secondary text-sm">Discover which languages are dominating the dev
                            landscape this year...</p>
                        <a href="#"
                            class="text-brand text-sm font-bold uppercase hover:text-white transition-colors">Read More
                            →</a>
                    </div>
                </article>
                <article
                    class="bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-transform fade-up">
                    <div class="h-44 bg-cyber-dark border-b-2 border-black flex items-center justify-center"><i
                            class="fas fa-rocket text-5xl text-cyber-cyan/20"></i></div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-3 text-xs text-text-secondary"><span><i
                                    class="fas fa-calendar mr-1"></i>Feb 18, 2026</span><span><i
                                    class="fas fa-user mr-1"></i>DevTeam</span></div>
                        <h3 class="font-bold text-lg leading-tight">StackLearn v2.0: New Features & Quest System</h3>
                        <p class="text-text-secondary text-sm">We've revamped the entire quest system with new
                            achievements...</p>
                        <a href="#"
                            class="text-brand text-sm font-bold uppercase hover:text-white transition-colors">Read More
                            →</a>
                    </div>
                </article>
                <article
                    class="bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-transform fade-up">
                    <div class="h-44 bg-cyber-dark border-b-2 border-black flex items-center justify-center"><i
                            class="fas fa-brain text-5xl text-pink-400/20"></i></div>
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-3 text-xs text-text-secondary"><span><i
                                    class="fas fa-calendar mr-1"></i>Feb 15, 2026</span><span><i
                                    class="fas fa-user mr-1"></i>AI Team</span></div>
                        <h3 class="font-bold text-lg leading-tight">How AI is Revolutionizing Online Education</h3>
                        <p class="text-text-secondary text-sm">Our AI tutor has helped over 5000 students this month
                            alone...</p>
                        <a href="#"
                            class="text-brand text-sm font-bold uppercase hover:text-white transition-colors">Read More
                            →</a>
                    </div>
                </article>
            </div>
        </section>

        <!-- ===== GET STARTED AREA ===== -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            <h2 class="font-pixel text-xl lg:text-2xl text-brand mb-10 text-center fade-up">CHOOSE YOUR ROLE</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-cyber-surface border-2 border-black pixel-shadow p-8 text-center pixel-button-hover fade-up">
                    <div class="w-20 h-20 bg-brand/20 border-2 border-brand mx-auto mb-6 flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-brand text-3xl"></i>
                    </div>
                    <h3 class="font-pixel text-sm text-brand mb-3">INSTRUCTOR</h3>
                    <p class="text-text-secondary text-sm mb-6">Share your expertise. Create stacks, mentor devs, and
                        earn as you teach.</p>
                    @auth
                        @if (auth()->user()->role === 'user')
                            <a href="{{ route('user.become-instructor.create') }}"
                                class="inline-block bg-brand text-black px-6 py-2 text-xs font-bold uppercase border border-black">Start
                                Teaching</a>
                        @endif
                    @else
                        <a href="{{ route('user.become-instructor.create') }}"
                            class="inline-block bg-brand text-black px-6 py-2 text-xs font-bold uppercase border border-black">Start
                            Teaching</a>
                    @endauth
                </div>
                <div
                    class="bg-cyber-surface border-2 border-black pixel-shadow p-8 text-center pixel-button-hover fade-up">
                    <div
                        class="w-20 h-20 bg-cyber-cyan/20 border-2 border-cyber-cyan mx-auto mb-6 flex items-center justify-center">
                        <i class="fas fa-user-graduate text-cyber-cyan text-3xl"></i>
                    </div>
                    <h3 class="font-pixel text-sm text-cyber-cyan mb-3">STUDENT</h3>
                    <p class="text-text-secondary text-sm mb-6">Learn at your own pace. Complete quests, earn badges,
                        level up your career.</p>
                    <a href="#"
                        class="inline-block bg-cyber-cyan text-black px-6 py-2 text-xs font-bold uppercase border border-black">Start
                        Learning</a>
                </div>
                <div
                    class="bg-cyber-surface border-2 border-black pixel-shadow p-8 text-center pixel-button-hover fade-up">
                    <div
                        class="w-20 h-20 bg-pink-500/20 border-2 border-pink-500 mx-auto mb-6 flex items-center justify-center">
                        <i class="fas fa-building text-pink-400 text-3xl"></i>
                    </div>
                    <h3 class="font-pixel text-sm text-pink-400 mb-3">ENTERPRISE</h3>
                    <p class="text-text-secondary text-sm mb-6">Train your team at scale. Custom learning paths and
                        analytics dashboard.</p>
                    <a href="#"
                        class="inline-block bg-pink-500 text-white px-6 py-2 text-xs font-bold uppercase border border-black">Contact
                        Sales</a>
                </div>
            </div>
        </section>

        <!-- ===== SUBSCRIBER AREA (Newsletter) ===== -->
        <section class="border-y-4 border-black bg-cyber-surface py-16">
            <div class="max-w-2xl mx-auto px-6 text-center fade-up">
                <h2 class="font-pixel text-xl text-brand mb-4">JOIN THE NEWSLETTER</h2>
                <p class="text-text-secondary mb-8 font-mono">Get weekly dev tips, new stack releases, and exclusive
                    quest codes delivered to your inbox.</p>
                <form class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 bg-black/50 border-2 border-slate-700 p-3 flex items-center gap-2">
                        <span class="text-brand font-mono">&gt;</span>
                        <input type="email" placeholder="your@email.dev"
                            class="bg-transparent border-none outline-none focus:ring-0 text-sm w-full placeholder:text-slate-500 text-text-primary" />
                    </div>
                    <button type="submit"
                        class="bg-brand text-black font-bold px-8 py-3 text-sm uppercase border-2 border-black pixel-shadow pixel-button-hover shrink-0">Subscribe
                        <i class="fas fa-paper-plane ml-1"></i></button>
                </form>
            </div>
        </section>

    </main>
@endsection
