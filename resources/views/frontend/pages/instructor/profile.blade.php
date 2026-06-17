@extends('frontend.master')
@section('content')
    <main class="min-h-screen pt-10 pb-20">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Breadcrumbs -->
            <nav class="mb-8 flex items-center gap-2 text-xs font-mono text-text-secondary uppercase tracking-widest fade-up">
                <a href="{{ route('frontend.home') }}" class="hover:text-brand transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-brand">Instructor Profile</span>
            </nav>

            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Left Column: Instructor Info -->
                <aside class="w-full lg:w-1/3 space-y-8 fade-up">
                    <div class="bg-cyber-surface border-2 border-black pixel-shadow p-8 text-center relative overflow-hidden group">
                        <!-- Decorative background element -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand/10 rotate-12 blur-2xl group-hover:bg-brand/20 transition-all"></div>
                        
                        <!-- Instructor Image -->
                        <div class="relative inline-block mb-6">
                            <div class="w-40 h-40 bg-slate-800 border-4 border-black overflow-hidden relative z-10 mx-auto">
                                <img src="{{ asset($instructor->photo) }}" alt="{{ $instructor->name }}" 
                                    class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500">
                            </div>
                            <!-- Pixel decorative corners -->
                            <div class="absolute -top-2 -left-2 w-4 h-4 border-t-2 border-l-2 border-brand z-20"></div>
                            <div class="absolute -bottom-2 -right-2 w-4 h-4 border-b-2 border-r-2 border-brand z-20"></div>
                        </div>

                        <h1 class="text-2xl font-pixel text-brand mb-2">{{ $instructor->name }}</h1>
                        <p class="text-sm font-mono text-slate-400 mb-6">{{ $instructor->experience }}</p>

                        <!-- Social Links (Placeholders) -->
                        <div class="flex justify-center gap-4 mb-8">
                            <a href="#" class="w-10 h-10 flex items-center justify-center bg-black/40 border border-slate-700 hover:border-brand hover:text-brand transition-all">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 flex items-center justify-center bg-black/40 border border-slate-700 hover:border-brand hover:text-brand transition-all">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="w-10 h-10 flex items-center justify-center bg-black/40 border border-slate-700 hover:border-brand hover:text-brand transition-all">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>

                        <!-- Main Stats -->
                        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-black/20">
                            <div class="text-center">
                                <div class="text-xl font-bold text-white">{{ $totalStudents > 1000 ? round($totalStudents/1000, 1) . 'M' : $totalStudents }}</div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold tracking-widest">Students</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-white">{{ $totalReviews > 1000 ? round($totalReviews/1000, 1) . 'K' : $totalReviews }}</div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold tracking-widest">Reviews</div>
                            </div>
                        </div>

                        <!-- Chat Button -->
                        @auth
                            @if(Auth::id() !== $instructor->id)
                                <div class="mt-8">
                                    <a href="{{ route(auth()->user()->role === 'instructor' ? 'instructor.chat.index' : 'user.chat.index', ['instructor_id' => $instructor->id]) }}" 
                                       class="block w-full bg-brand text-black font-black uppercase py-3 border-2 border-black hover:bg-white transition-all pixel-shadow-sm text-sm">
                                        <i class="far fa-comment-dots mr-2"></i> Chat với giảng viên
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="mt-8">
                                <a href="{{ route('login') }}" 
                                   class="block w-full bg-slate-800 text-white font-bold uppercase py-3 border-2 border-black hover:bg-brand hover:text-black transition-all pixel-shadow-sm text-sm">
                                    Đăng nhập để chat
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Contact/Bio Section -->
                    <div class="bg-cyber-surface border-2 border-black pixel-border p-6">
                        <h3 class="text-brand font-pixel text-sm uppercase mb-4 tracking-tighter">Instructor Bio</h3>
                        <div class="text-slate-300 text-sm leading-relaxed space-y-4 font-mono">
                            {!! nl2br(e($instructor->bio)) !!}
                        </div>
                    </div>
                </aside>

                <!-- Right Column: Courses -->
                <div class="w-full lg:w-2/3 fade-up">
                    <div class="mb-10 flex items-center justify-between border-b-2 border-black/30 pb-6">
                        <div>
                            <h2 class="text-2xl font-pixel text-white">Khóa học của tôi</h2>
                            <p class="text-slate-400 font-mono text-xs mt-1">Tổng cộng {{ $courses->count() }} khóa học được xuất bản</p>
                        </div>
                    </div>

                    <!-- Courses List (Using existing list style for consistency) -->
                    <div class="space-y-6">
                        @forelse($courses as $course)
                            <article class="group relative flex flex-col sm:flex-row gap-6 p-4 bg-cyber-surface/30 border-2 border-transparent hover:border-black hover:bg-cyber-surface/50 transition-all rounded pixel-border">
                                <!-- Image -->
                                <a href="{{ route('chi-tiet', $course->course_name_slug) }}" 
                                    class="flex-shrink-0 w-full sm:w-64 h-40 bg-cyber-dark border-2 border-black relative overflow-hidden block">
                                    <img loading="lazy" src="{{ asset($course->course_image) }}" 
                                        alt="{{ $course->course_name }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @if($course->bestseller === 'yes')
                                        <span class="absolute top-2 left-2 bg-yellow-400 text-black text-[9px] font-bold px-2 py-0.5 border border-black shadow-[2px_2px_0_0_#000]">Bestseller</span>
                                    @endif
                                </a>

                                <!-- Details -->
                                <div class="flex-grow flex flex-col justify-between py-1">
                                    <div>
                                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-2">
                                            <h3 class="font-bold text-xl leading-tight text-white group-hover:text-brand transition-colors">
                                                <a href="{{ route('chi-tiet', $course->course_name_slug) }}">
                                                    {{ $course->course_name }}
                                                </a>
                                            </h3>
                                            
                                            <div class="flex items-baseline gap-2 shrink-0">
                                                <span class="text-brand font-bold text-xl">{{ number_format($course->discount_price, 0, ',', '.') }} ₫</span>
                                                @if($course->selling_price > $course->discount_price)
                                                    <span class="text-slate-500 text-xs line-through">{{ number_format($course->selling_price, 0, ',', '.') }} ₫</span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="text-slate-400 text-sm mb-4 line-clamp-2 font-mono">
                                            {{ strip_tags($course->description) }}
                                        </p>

                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="flex items-center gap-1">
                                                <span class="text-yellow-400 font-bold text-sm">{{ number_format($course->reviews_avg_rating ?? 0, 1) }}</span>
                                                <div class="text-yellow-400 text-xs flex">
                                                    @php $rating = $course->reviews_avg_rating ?? 0; @endphp
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($rating))
                                                            <i class="fas fa-star"></i>
                                                        @elseif($i == ceil($rating) && ($rating - floor($rating)) >= 0.5)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-xs text-slate-500">({{ number_format($course->reviews_count ?? 0) }})</span>
                                            </div>
                                            <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                                            <span class="text-xs text-brand font-mono">{{ $course->category->name }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 text-[11px] text-slate-400 font-mono pt-4 border-t border-black/10">
                                        @php
                                            $total_minutes = $course->sections->flatMap->lecture->sum('video_duration');
                                            $hours = floor($total_minutes / 60);
                                            $minutes = floor($total_minutes % 60);
                                        @endphp
                                        <span class="flex items-center gap-1.5"><i class="far fa-clock"></i> {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes }}m</span>
                                        <span class="flex items-center gap-1.5"><i class="far fa-play-circle"></i> {{ $course->sections->flatMap->lecture->count() }} lectures</span>
                                        <span class="flex items-center gap-1.5"><i class="fas fa-signal"></i> All Levels</span>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="bg-cyber-surface border-2 border-black pixel-shadow p-12 text-center text-slate-500">
                                <i class="fas fa-code-branch text-4xl mb-4 text-slate-700"></i>
                                <p class="font-mono">Giảng viên chưa có khóa học nào được xuất bản.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
