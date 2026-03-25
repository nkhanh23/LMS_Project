@extends('backend.user.master')

@section('content')
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="space-y-1">
            <div class="inline-block bg-brand px-2 py-0.5 text-black font-pixel text-[8px] font-black uppercase mb-1">
                SYSTEM_ACTIVE
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-brand font-pixel tracking-tighter uppercase leading-none">
                ACTIVE QUESTS <span class="text-cyber-cyan">[</span>MY STACKS<span class="text-cyber-cyan">]</span>
            </h1>
            <p class="text-text-secondary text-xs md:text-sm">
                Danh sách tất cả khóa học bạn đã đăng ký
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('user.courses', ['status' => 'all']) }}"
                class="px-4 py-2 border-2 border-black font-pixel text-[10px] font-bold uppercase pixel-shadow transition-all hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-none
                {{ $status === 'all' ? 'bg-brand text-black' : 'bg-cyber-surface text-white hover:bg-black/20' }}">
                [ TẤT CẢ ]
            </a>

            <a href="{{ route('user.courses', ['status' => 'learning']) }}"
                class="px-4 py-2 border-2 border-black font-pixel text-[10px] font-bold uppercase pixel-shadow transition-all hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-none
                {{ $status === 'learning' ? 'bg-brand text-black' : 'bg-cyber-surface text-white hover:bg-black/20' }}">
                [ ĐANG HỌC ]
            </a>

            <a href="{{ route('user.courses', ['status' => 'completed']) }}"
                class="px-4 py-2 border-2 border-black font-pixel text-[10px] font-bold uppercase pixel-shadow transition-all hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-none
                {{ $status === 'completed' ? 'bg-brand text-black' : 'bg-cyber-surface text-white hover:bg-black/20' }}">
                [ HOÀN THÀNH ]
            </a>
        </div>
    </div>

    @if ($courses->isEmpty())
        <div class="bg-cyber-surface border-2 md:border-4 border-black pixel-shadow p-6 md:p-8 text-center">
            <div class="text-brand font-pixel text-sm uppercase mb-3">NO DATA FOUND</div>
            <h2 class="text-xl md:text-2xl font-black text-white font-pixel uppercase mb-2">
                Chưa có khóa học nào
            </h2>
            <p class="text-text-secondary text-sm">
                Bạn chưa đăng ký khóa học nào hoặc bộ lọc hiện tại không có dữ liệu phù hợp.
            </p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($courses as $course)
                @php
                    $percent = max(0, min(100, (int) $course['completion_percent']));
                    $filledBars = (int) floor($percent / 10);
                    $isCompleted = $course['display_status'] === 'completed';
                @endphp

                <div
                    class="group bg-cyber-surface border-2 md:border-4 border-black pixel-shadow flex flex-col lg:flex-row overflow-hidden transition-all hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none {{ $isCompleted ? 'opacity-90' : '' }}">

                    <div
                        class="w-full lg:w-40 bg-black/40 flex items-center justify-center p-4 border-b-2 lg:border-b-0 lg:border-r-2 border-black relative overflow-hidden shrink-0">
                        <div class="relative z-10 text-center">
                            @if ($isCompleted)
                                <i class="fas fa-shield-alt text-4xl text-brand"></i>
                            @elseif($percent > 0)
                                <i class="fas fa-play-circle text-4xl text-cyber-cyan"></i>
                            @else
                                <i class="fas fa-book-open text-4xl text-cyber-cyan"></i>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 p-4 flex flex-col justify-center">
                        <div class="flex justify-between items-start gap-4 mb-1">
                            <div>
                                <span class="text-cyber-cyan font-pixel text-[8px] uppercase font-bold tracking-tighter">
                                    INSTRUCTOR: {{ strtoupper($course['instructor_name']) }}
                                </span>

                                <h3 class="text-lg md:text-xl font-black text-white font-pixel uppercase leading-none mt-1">
                                    {{ $course['title'] }}
                                </h3>
                            </div>

                            <span class="text-text-secondary font-pixel text-[8px] uppercase font-bold whitespace-nowrap">
                                MODULE {{ str_pad($course['completed_lectures'], 2, '0', STR_PAD_LEFT) }}
                                /
                                {{ str_pad($course['total_lectures'], 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <div class="mt-2 flex flex-col gap-1">
                            <div class="flex justify-between text-[8px] font-bold font-pixel uppercase">
                                <span class="{{ $isCompleted ? 'text-brand' : 'text-brand' }}">
                                    {{ $isCompleted ? 'HP [COMPLETE]' : 'HP [PROGRESS]' }}
                                </span>
                                <span class="{{ $isCompleted ? 'text-brand' : 'text-white' }}">
                                    {{ $percent }}%
                                </span>
                            </div>

                            <div class="h-4 w-full border-2 border-black bg-black flex gap-0.5 p-0.5">
                                @for ($i = 0; $i < 10; $i++)
                                    <div class="flex-1 h-full {{ $i < $filledBars ? 'bg-brand' : 'bg-white/10' }}"></div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-4 bg-black/20 border-t-2 lg:border-t-0 lg:border-l-2 border-black flex items-center justify-center shrink-0">
                        @if (!empty($course['slug']))
                            <a href="{{ route('course.play', $course['slug']) }}"
                                class="w-full lg:w-auto px-6 py-2 {{ $isCompleted ? 'bg-brand' : 'bg-cyber-cyan' }} border-2 border-black text-black font-pixel text-[10px] font-black uppercase flex items-center justify-center gap-2 pixel-button-hover transition-all">
                                {{ $isCompleted ? 'REVIEW LOGS' : 'CONTINUE QUEST' }}
                                <i class="fas {{ $isCompleted ? 'fa-history' : 'fa-play' }} text-[10px]"></i>
                            </a>
                        @else
                            <button type="button"
                                class="w-full lg:w-auto px-6 py-2 bg-gray-500 border-2 border-black text-black font-pixel text-[10px] font-black uppercase flex items-center justify-center gap-2 cursor-not-allowed">
                                INVALID COURSE
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
