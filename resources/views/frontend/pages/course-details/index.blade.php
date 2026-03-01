@extends('frontend.master')
@section('content')
    <main class="mx-auto w-full max-w-7xl px-6 py-8">
        <!-- 3) Breadcrumb / Hero course info -->
        @include('frontend.pages.course-details.breadcrumb')
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <!-- 4) Main content area – cột trái -->
            <div class="lg:col-span-8 flex flex-col gap-8 flex-1 course-details-content-wrap">
                <!-- Hero Title -->
                @include('frontend.pages.course-details.hero-title')

                <!-- 4.1. What you'll learn -->
                @include('frontend.pages.course-details.learn-section')




                <!-- 4.6. Course content / curriculum -->
                @include('frontend.pages.course-details.course-content')


                <!-- 4.7. Students also bought -->
                @include('frontend.pages.course-details.students-bought')
                <section class="py-6">
                    <h3 class="mb-4 text-xl font-bold uppercase tracking-tighter text-brand">Students also bought</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 owl-carousel">
                        <div
                            class="bg-cyber-surface border border-slate-700 p-3 hover:border-brand transition-colors group relative">
                            <div class="h-32 bg-black flex items-center justify-center mb-3">
                                <i class="fab fa-python text-4xl text-yellow-400/50"></i>
                            </div>
                            <span
                                class="absolute top-5 left-5 bg-pink-500 text-white text-[10px] px-2 py-0.5 font-bold z-10">HOT</span>
                            <span
                                class="absolute top-5 right-5 bg-black/80 text-white text-[10px] px-2 py-0.5 z-10 border border-slate-600">Beginner</span>
                            <h4 class="font-bold text-sm text-slate-100 group-hover:text-brand line-clamp-2">Complete
                                Python Developer in 2026</h4>
                            <p class="text-[10px] text-slate-400 mt-1">Jose Portilla</p>
                            <div class="flex items-center gap-1 text-yellow-400 text-xs mt-1">
                                <span>4.8</span> <i class="fas fa-star"></i>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="font-bold text-brand">$14.99</span>
                                <button class="text-slate-500 hover:text-pink-400"><i class="far fa-heart"></i></button>
                            </div>
                        </div>
                        <div
                            class="bg-cyber-surface border border-slate-700 p-3 hover:border-brand transition-colors group relative">
                            <div class="h-32 bg-black flex items-center justify-center mb-3">
                                <i class="fab fa-js text-4xl text-yellow-300/50"></i>
                            </div>
                            <h4 class="font-bold text-sm text-slate-100 group-hover:text-brand line-clamp-2">JavaScript:
                                The Advanced Concepts</h4>
                            <p class="text-[10px] text-slate-400 mt-1">Andrei Neagoie</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="font-bold text-brand">$16.99</span>
                                <button class="text-slate-500 hover:text-pink-400"><i class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 4.8. About the instructor -->
                @include('frontend.pages.course-details.instructor-about')
                <section class="p-6 bg-cyber-surface border-2 border-black pixel-border">
                    <h3 class="mb-6 text-xl font-bold uppercase tracking-tighter text-brand">About the instructor</h3>
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="shrink-0">
                            <div class="w-32 h-32 bg-slate-800 border-2 border-slate-600 overflow-hidden">
                                <img src="https://via.placeholder.com/150" alt="Tim Buchalka"
                                    class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all">
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg text-cyber-cyan hover:underline cursor-pointer">Tim Buchalka</h4>
                            <p class="text-xs text-slate-400 mb-3">Java Python Android and C# Expert Developer</p>
                            <div class="flex items-center gap-4 text-sm text-slate-300 mb-4">
                                <span class="flex items-center gap-1"><i class="fas fa-star text-yellow-400"></i> 4.5
                                    Rating</span>
                                <span class="flex items-center gap-1"><i class="fas fa-user text-brand"></i> 1.2M
                                    Students</span>
                                <span class="flex items-center gap-1"><i class="fas fa-play-circle text-pink-400"></i> 12
                                    Courses</span>
                            </div>
                            <p class="text-sm text-slate-400">Tim's been a professional software developer for over 40
                                years...</p>
                        </div>
                    </div>
                </section>

                <!-- 4.9. Student feedback -->
                @include('frontend.pages.course-details.student-feedback')
                <section class="py-6">
                    <h3 class="mb-4 text-xl font-bold uppercase tracking-tighter text-brand">Student feedback</h3>
                    <div class="flex gap-8 items-center border border-slate-700 p-6 bg-black/30">
                        <div class="text-center shrink-0">
                            <div class="text-5xl font-black text-yellow-400">4.6</div>
                            <div class="text-yellow-400 text-sm mt-2"><i class="fas fa-star"></i><i
                                    class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                    class="fas fa-star-half-alt"></i></div>
                            <div class="text-xs text-slate-400 mt-1">Course Rating</div>
                        </div>
                        <div class="flex-1 space-y-2">
                            <!-- 5 star -->
                            <div class="flex items-center gap-3">
                                <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                                    <div class="bg-brand h-full" style="width: 70%"></div>
                                </div>
                                <div class="text-yellow-400 text-xs shrink-0 w-24 flex justify-end">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <span class="text-slate-400 ml-1">70%</span>
                                </div>
                            </div>
                            <!-- 4 star -->
                            <div class="flex items-center gap-3">
                                <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                                    <div class="bg-brand h-full" style="width: 20%"></div>
                                </div>
                                <div class="text-yellow-400 text-xs shrink-0 w-24 flex justify-end">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="fas fa-star"></i><i class="far fa-star text-slate-600"></i>
                                    <span class="text-slate-400 ml-1">20%</span>
                                </div>
                            </div>
                            <!-- Other star ratings... -->
                        </div>
                    </div>
                </section>

                {{-- 4.10 den 4.11 --}}
                <!-- 4.10. Reviews -->
                @include('frontend.pages.course-details.reviews')
                <section class="py-6 space-y-6">
                    <h3 class="text-xl font-bold uppercase tracking-tighter text-brand">Reviews</h3>
                    <div class="space-y-4">
                        <div class="border border-slate-700 bg-cyber-surface p-4 flex gap-4">
                            <div
                                class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center font-bold text-white shrink-0">
                                JD</div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-100">John Doe</h4>
                                <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                                    <div class="text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                            class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    </div>
                                    <span>2 months ago</span>
                                </div>
                                <p class="text-sm text-slate-300">Excellent course, very comprehensive.</p>
                                <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                                    <span>Helpful?</span>
                                    <button class="hover:text-white"><i class="far fa-thumbs-up"></i></button>
                                    <button class="hover:text-white"><i class="far fa-thumbs-down"></i></button>
                                    <button class="hover:text-white ml-2 underline">Report</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 4.11. Add a Review -->
                <section class="py-6 border-t border-slate-700">
                    <h3 class="mb-4 text-xl font-bold uppercase tracking-tighter text-brand">Add a Review</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-1">Your Rating</label>
                            <div class="text-yellow-400 text-lg cursor-pointer star-rating-input">
                                <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i
                                    class="far fa-star"></i><i class="far fa-star"></i>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" placeholder="Name"
                                class="w-full bg-black/50 border-2 border-slate-700 p-3 text-sm text-text-primary focus:border-brand">
                            <input type="email" placeholder="Email"
                                class="w-full bg-black/50 border-2 border-slate-700 p-3 text-sm text-text-primary focus:border-brand">
                        </div>
                        <textarea placeholder="Message" rows="4"
                            class="w-full bg-black/50 border-2 border-slate-700 p-3 text-sm text-text-primary focus:border-brand"></textarea>
                        <label class="flex items-center gap-2 text-sm text-slate-400">
                            <input type="checkbox" class="bg-black border-slate-600 text-brand focus:ring-brand">
                            Save my info for next time
                        </label>
                        <button type="button"
                            class="bg-brand text-black font-bold py-3 px-6 uppercase tracking-widest text-sm hover:bg-white transition-colors pixel-border pixel-button-hover mt-4">
                            Submit Review
                        </button>
                    </form>
                </section>
            </div>

            <!-- 5) Right sidebar – cột phải -->
            @include('frontend.pages.course-details.right-sidebar')
            <div class="lg:col-span-4 sidebar sidebar-negative mt-8 lg:mt-0">
                <div class="sticky top-24 flex flex-col gap-6">
                    <!-- 5.1 Preview / purchase card -->
                    <div class="bg-cyber-surface pixel-border border-4 border-black overflow-hidden relative">
                        <!-- Video Thumbnail -->
                        <div class="aspect-video bg-black relative flex items-center justify-center cursor-pointer group"
                            data-bs-toggle="modal" data-bs-target="#previewModal">
                            <img src="https://via.placeholder.com/600x400" alt="Video Preview"
                                class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity">
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                <button
                                    class="w-16 h-16 bg-white/20 text-white rounded-full flex items-center justify-center group-hover:bg-brand group-hover:text-black transition-colors backdrop-blur-sm">
                                    <i class="fas fa-play text-2xl ml-1"></i>
                                </button>
                            </div>
                            <span
                                class="absolute bottom-4 left-4 text-xs font-bold text-white uppercase tracking-widest bg-black/60 px-2 py-1">Preview
                                this course</span>
                        </div>
                        <!-- Meta & Buttons -->
                        <div class="p-6">
                            <div class="flex items-end gap-2 mb-6">
                                <span class="text-3xl font-black text-brand pixel-text">$19.99</span>
                                <span class="text-slate-500 line-through text-sm">$84.99</span>
                                <span class="bg-pink-500 text-white text-[10px] px-2 py-0.5 font-bold animate-pulse">86%
                                    off</span>
                            </div>
                            <div class="space-y-3 mb-4 text-center">
                                <button
                                    class="w-full bg-brand py-3 text-black font-black uppercase tracking-widest text-sm border-2 border-black pixel-shadow pixel-button-hover">Add
                                    to cart</button>
                                <button
                                    class="w-full bg-card-dark border-2 border-slate-500 py-3 text-white font-bold uppercase tracking-widest text-sm hover:border-white transition-colors">Buy
                                    this course</button>
                            </div>
                            <p class="text-center text-xs text-slate-400 mb-6 font-mono">30-Day Money-Back Guarantee</p>
                            <!-- This course includes list -->
                            <div class="border-t border-slate-700 pt-5">
                                <h4 class="font-bold text-sm text-slate-100 mb-3">This course includes:</h4>
                                <ul class="space-y-3 text-sm text-slate-300">
                                    <li class="flex items-center gap-3"><i
                                            class="fas fa-video text-secondary w-4 text-center"></i> 80 hours on-demand
                                        video</li>
                                    <li class="flex items-center gap-3"><i
                                            class="far fa-newspaper text-secondary w-4 text-center"></i> 3 articles</li>
                                    <li class="flex items-center gap-3"><i
                                            class="fas fa-file-download text-secondary w-4 text-center"></i> 2 downloadable
                                        resources</li>
                                    <li class="flex items-center gap-3"><i
                                            class="fas fa-code text-secondary w-4 text-center"></i> 1 coding exercise</li>
                                    <li class="flex items-center gap-3"><i
                                            class="fas fa-infinity text-secondary w-4 text-center"></i> Full lifetime
                                        access</li>
                                    <li class="flex items-center gap-3"><i
                                            class="fas fa-mobile-alt text-secondary w-4 text-center"></i> Access on mobile
                                        and TV</li>
                                    <li class="flex items-center gap-3"><i
                                            class="fas fa-certificate text-secondary w-4 text-center"></i> Certificate of
                                        completion</li>
                                </ul>
                            </div>
                            <!-- Buy for team -->
                            <div class="mt-6 bg-black/30 p-4 border border-slate-700 text-center rounded-sm">
                                <h4 class="font-bold text-sm text-slate-100 mb-2">Training 5 or more people?</h4>
                                <p class="text-xs text-slate-400 mb-3">Get your team access to 8,000+ top StackLearn
                                    courses anytime, anywhere.</p>
                                <button
                                    class="w-full border-2 border-cyber-cyan text-cyber-cyan font-bold py-2 text-xs uppercase hover:bg-cyber-cyan hover:text-black transition-colors">Try
                                    StackLearn for Business</button>
                            </div>
                        </div>
                    </div>

                    <!-- 5.2 Course Features -->
                    <div class="bg-black/30 border border-slate-700 p-6">
                        <h4 class="font-bold uppercase tracking-tighter text-brand mb-4 border-b border-slate-700 pb-2">
                            Course Features</h4>
                        <ul class="space-y-3 text-sm text-slate-300">
                            <li
                                class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
                                <span>Lectures</span> <span class="text-slate-500 font-mono">15</span>
                            </li>
                            <li
                                class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
                                <span>Quizzes</span> <span class="text-slate-500 font-mono">3</span>
                            </li>
                            <li
                                class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
                                <span>Skill Level</span> <span class="text-slate-500 font-mono">All Levels</span>
                            </li>
                            <li
                                class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
                                <span>Language</span> <span class="text-slate-500 font-mono">English</span>
                            </li>
                        </ul>
                    </div>

                    <!-- 5.3 Course Categories -->
                    <div class="bg-black/30 border border-slate-700 p-6">
                        <h4 class="font-bold uppercase tracking-tighter text-brand mb-4 border-b border-slate-700 pb-2">
                            Course Categories</h4>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <a href="#"
                                class="px-3 py-1 bg-cyber-dark border border-slate-600 hover:border-brand hover:text-brand transition-colors text-slate-300">Development</a>
                            <a href="#"
                                class="px-3 py-1 bg-cyber-dark border border-slate-600 hover:border-brand hover:text-brand transition-colors text-slate-300">IT
                                & Software</a>
                        </div>
                    </div>

                    <!-- 5.4 Related Courses -->
                    <div class="bg-black/30 border border-slate-700 p-6">
                        <h4 class="font-bold uppercase tracking-tighter text-brand mb-4 border-b border-slate-700 pb-2">
                            Related Courses</h4>
                        <div class="space-y-4">
                            <!-- item -->
                            <div class="flex items-center gap-3 p-2 hover:bg-slate-800 transition-colors group">
                                <div class="w-16 h-12 bg-black shrink-0 relative flex items-center justify-center">
                                    <i class="fab fa-java text-xl text-yellow-500/50"></i>
                                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-xs font-bold text-slate-200 truncate group-hover:text-brand">Spring
                                        Boot 3</h5>
                                    <span class="text-brand font-bold text-[10px]">$12.99</span>
                                </div>
                            </div>
                            <!-- item 2 -->
                            <div class="flex items-center gap-3 p-2 hover:bg-slate-800 transition-colors group">
                                <div class="w-16 h-12 bg-black shrink-0 relative flex items-center justify-center">
                                    <i class="fab fa-aws text-xl text-orange-500/50"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-xs font-bold text-slate-200 truncate group-hover:text-brand">AWS
                                        Certified Java</h5>
                                    <span class="text-brand font-bold text-[10px]">$19.99</span>
                                </div>
                            </div>
                            <!-- item 3 -->
                            <div class="flex items-center gap-3 p-2 hover:bg-slate-800 transition-colors group">
                                <div class="w-16 h-12 bg-black shrink-0 relative flex items-center justify-center">
                                    <i class="fas fa-database text-xl text-blue-400/50"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-xs font-bold text-slate-200 truncate group-hover:text-brand">Hibernate
                                        and JPA</h5>
                                    <span class="text-brand font-bold text-[10px]">$14.99</span>
                                </div>
                            </div>
                        </div>
                        <button
                            class="w-full mt-4 text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-white transition-colors border border-slate-700 p-2 text-center">View
                            All Courses</button>
                    </div>

                    <!-- 5.5 Course Tags -->
                    <div class="bg-black/30 border border-slate-700 p-6">
                        <h4 class="font-bold uppercase tracking-tighter text-brand mb-4 border-b border-slate-700 pb-2">
                            Tags</h4>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <a href="#"
                                class="px-2 py-1 bg-slate-800 hover:bg-brand hover:text-black transition-colors text-slate-300">Java</a>
                            <a href="#"
                                class="px-2 py-1 bg-slate-800 hover:bg-brand hover:text-black transition-colors text-slate-300">Programming</a>
                            <a href="#"
                                class="px-2 py-1 bg-slate-800 hover:bg-brand hover:text-black transition-colors text-slate-300">Backend</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6) Related course area riêng ở dưới -->
        {{-- Modal --}}
        @include('frontend.pages.course-details.related-courses')
        <section class="mt-16 border-t-2 border-slate-800 pt-16">
            <h2 class="text-2xl font-bold uppercase tracking-tighter text-brand mb-8 pixel-text">More Courses by Tim
                Buchalka</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div
                    class="bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-transform group p-4">
                    <div class="h-36 bg-cyber-dark flex items-center justify-center mb-3 relative">
                        <i class="fab fa-android text-5xl text-green-500/30"></i>
                        <span
                            class="absolute top-2 left-2 bg-pink-500 text-white text-[10px] px-2 py-0.5 font-bold">NEW</span>
                    </div>
                    <h4 class="font-bold text-sm text-slate-100 group-hover:text-brand mb-1">Android 14 Development</h4>
                    <p class="text-[10px] text-slate-400 mb-2">Tim Buchalka</p>
                    <div class="flex items-center gap-1 text-yellow-400 text-xs mb-2">
                        <span>4.7</span> <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-700 pt-2">
                        <span class="font-bold text-brand">$19.99</span>
                        <button class="text-slate-500 hover:text-pink-400"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                <!-- Extend with 3 more cards -->
            </div>
        </section>

        <!-- 7) CTA area -->
        @include('frontend.pages.course-details.become-teacher')
        <section
            class="mt-16 bg-gradient-to-r from-brand/20 via-cyber-cyan/10 to-brand/20 border-y-4 border-black p-12 text-center overflow-hidden relative">
            <div class="absolute -top-10 -left-10 w-32 h-32 border-4 border-brand/20 rounded-full animate-spin"
                style="animation-duration:20s"></div>
            <div class="absolute -bottom-10 -right-10 w-48 h-48 border-4 border-cyber-cyan/15 rounded-full animate-spin"
                style="animation-duration:30s"></div>
            <h2 class="font-pixel text-xl lg:text-3xl text-brand mb-6 relative z-10 pixel-text">Become an Instructor</h2>
            <p class="text-slate-300 max-w-2xl mx-auto mb-8 relative z-10">Top instructors from around the world teach
                millions of students on StackLearn. We provide the tools and skills to teach what you love.</p>
            <button
                class="relative z-10 bg-brand text-black font-bold py-4 px-10 text-lg uppercase border-4 border-black pixel-shadow pixel-button-hover">Start
                Teaching Today <i class="fas fa-arrow-right ml-2"></i></button>
        </section>
    </main>

    <!-- 10) Modal components -->
    <!-- Share Modal -->
    <div class="modal fade hidden" id="shareModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none max-w-lg mx-auto">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-cyber-surface outline-none focus:outline-none border-4 border-black pixel-shadow p-6">
                <!-- Modal header -->
                <div class="flex items-start justify-between border-b border-slate-700 pb-4 mb-4">
                    <h3 class="font-bold uppercase tracking-tighter text-brand text-xl">Share this course</h3>
                    <button class="bg-transparent text-slate-400 hover:text-white" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="relative p-flex-auto text-sm text-slate-300">
                    <div class="flex items-center border border-slate-600 bg-black p-2 gap-2 mb-6">
                        <input type="text" class="w-full bg-transparent border-none text-slate-300 outline-none"
                            value="https://stacklearn.com/course/java-masterclass" readonly>
                        <button
                            class="bg-brand text-black font-bold px-4 py-2 text-xs uppercase hover:bg-white pixel-border">Copy</button>
                    </div>
                    <div class="flex justify-center gap-4 text-2xl">
                        <a href="#"
                            class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#"
                            class="w-12 h-12 rounded-full bg-cyan-500 text-white flex items-center justify-center hover:bg-cyan-600"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#"
                            class="w-12 h-12 rounded-full bg-blue-800 text-white flex items-center justify-center hover:bg-blue-900"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    @include('frontend.pages.course-details.preview-modal')
    <div class="modal fade hidden" id="previewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered relative w-auto pointer-events-none max-w-4xl mx-auto">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-black outline-none focus:outline-none border-4 border-black pixel-shadow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b border-slate-800 bg-cyber-dark">
                    <div>
                        <span class="text-xs text-slate-400">Course Preview</span>
                        <h3 class="font-bold text-slate-100 text-lg">Introduction to Java</h3>
                    </div>
                    <button class="text-slate-400 hover:text-white" data-bs-dismiss="modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="relative aspect-video bg-black">
                    <!-- Embedded Video placeholder -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-play-circle text-6xl text-brand/30"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Abuse Modal -->
    <div class="modal fade hidden" id="reportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none max-w-lg mx-auto">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-cyber-surface outline-none focus:outline-none border-4 border-black pixel-shadow p-6">
                <!-- Modal header -->
                <div class="flex items-start justify-between border-b border-slate-700 pb-4 mb-4">
                    <h3 class="font-bold uppercase tracking-tighter text-red-500 text-xl">Report Abuse</h3>
                    <button class="bg-transparent text-slate-400 hover:text-white" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="relative text-sm text-slate-300">
                    <p class="mb-4 text-xs">Flagged content is reviewed by StackLearn staff to determine whether it
                        violates <a href="#" class="text-cyber-cyan underline">Terms of Service</a> or <a
                            href="#" class="text-cyber-cyan underline">Community Guidelines</a>.</p>
                    <form class="space-y-4">
                        <div>
                            <label class="block mb-2 font-bold text-slate-400 text-xs">Issue Type</label>
                            <select
                                class="w-full bg-black border border-slate-700 p-2 text-slate-300 outline-none focus:border-red-500">
                                <option>Inappropriate Course Content</option>
                                <option>Misleading Info</option>
                                <option>Spam or scam</option>
                                <option>Copyright violation</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 font-bold text-slate-400 text-xs">Issue Details</label>
                            <textarea rows="4"
                                class="w-full bg-black border border-slate-700 p-2 text-slate-300 outline-none focus:border-red-500"
                                placeholder="Please provide specific details..."></textarea>
                        </div>
                        <div class="text-right">
                            <button type="button"
                                class="text-slate-400 mr-4 font-bold text-xs uppercase hover:text-white"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="button"
                                class="bg-red-500 text-white font-bold px-6 py-2 text-xs uppercase hover:bg-red-600 pixel-border">Submit
                                Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
