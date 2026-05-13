@extends('backend.user.master')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h3 class="pixel-text font-bold text-xl text-white uppercase tracking-tighter">
                Khóa học của tôi <span class="text-brand">_MY_COURSES</span>
            </h3>
            <p class="text-[10px] sm:text-xs text-text-secondary mt-1 font-mono uppercase">
                Theo dõi tiến độ học tập của bạn
            </p>
        </div>

        <a href="{{ route('frontend.courses.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover pixel-text uppercase">
            <i class="fas fa-search text-[10px]"></i>
            Tìm khóa học mới
        </a>
    </div>

    @if($enrollments->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
            @foreach($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                    $progress = $enrollment->courseProgress;
                    $percent = $progress?->completion_percent ?? 0;
                    $lastLecture = $progress?->lastLecture ?? $enrollment->lastLecture;
                    $courseSlug = $course?->course_name_slug;
                    $thumbnail = $course?->course_image
                        ? asset($course->course_image)
                        : asset('frontend/assets/img/course/default.jpg');
                @endphp

                <div class="bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-transform p-3 sm:p-4 flex flex-col h-full group">
                    <div class="relative aspect-video sm:h-44 bg-black border border-black mb-4 overflow-hidden">
                        <img src="{{ $thumbnail }}"
                             alt="{{ $course->course_name ?? 'Course' }}"
                             class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-all duration-500 group-hover:scale-105">

                        <div class="absolute top-2 left-2 px-2 py-1 bg-black/80 border border-brand text-brand text-[9px] font-bold uppercase pixel-text">
                            {{ $percent }}% COMPLETED
                        </div>
                    </div>

                    <div class="flex flex-col flex-1">
                        <div class="mb-4">
                            <h4 class="font-bold text-sm text-white line-clamp-2 mb-1 group-hover:text-brand transition-colors" title="{{ $course->course_name ?? 'Không tìm thấy khóa học' }}">
                                {{ $course->course_name ?? 'Không tìm thấy khóa học' }}
                            </h4>

                            <p class="text-[10px] text-text-secondary">
                                By <span class="text-cyber-cyan font-bold uppercase">{{ $course->instructor->name ?? 'N/A' }}</span>
                            </p>
                        </div>

                        <div class="mt-auto">
                            <div class="flex justify-between text-[9px] font-bold mb-1.5 pixel-text uppercase">
                                <span class="text-text-secondary tracking-tighter">Progress</span>
                                <span class="text-brand">{{ $percent }}%</span>
                            </div>

                            <div class="w-full h-1.5 bg-black border border-black/30 mb-4 overflow-hidden">
                                <div class="h-full bg-brand transition-all duration-500"
                                     style="width: {{ min(100, max(0, $percent)) }}%">
                                </div>
                            </div>

                            <div class="text-[9px] text-text-secondary mb-4 truncate italic font-mono" title="{{ $lastLecture->lecture_title ?? $lastLecture->title ?? 'Bài học' }}">
                                @if($lastLecture)
                                    LAST: <span class="text-white">{{ $lastLecture->lecture_title ?? $lastLecture->title ?? 'Bài học' }}</span>
                                @else
                                    <span class="text-pink-400">NOT STARTED YET</span>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                @if($courseSlug)
                                    @if($lastLecture)
                                        <a href="{{ route('course.lecture.watch', [$courseSlug, $lastLecture->id]) }}"
                                           class="flex-1 text-center px-3 py-2 bg-brand text-black border-2 border-black font-bold text-[10px] pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                            CONTINUE
                                        </a>
                                    @else
                                        <a href="{{ route('course.play', $courseSlug) }}"
                                           class="flex-1 text-center px-3 py-2 bg-brand text-black border-2 border-black font-bold text-[10px] pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                            START
                                        </a>
                                    @endif
                                @endif

                                @if($courseSlug)
                                    <a href="{{ route('chi-tiet', $courseSlug) }}"
                                       class="px-3 py-2 bg-cyber-dark text-white border-2 border-black font-bold text-[10px] pixel-shadow-sm pixel-button-hover uppercase pixel-text flex items-center justify-center">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 border-t-2 border-black/30 pt-8 flex justify-center">
            {{ $enrollments->links('vendor.pagination.cyber-pixel') }}
        </div>
    @else
        <div class="bg-cyber-surface border-2 border-black pixel-shadow p-10 text-center">
            <div class="text-5xl text-brand mb-4">
                <i class="fas fa-book-open"></i>
            </div>

            <h3 class="pixel-text font-bold text-xl text-white mb-2">
                Bạn chưa có khóa học nào <span class="text-brand">_EMPTY</span>
            </h3>

            <p class="text-xs text-text-secondary mt-2">
                Hãy khám phá các khóa học và bắt đầu hành trình học tập của bạn.
            </p>

            <a href="{{ route('frontend.home') }}"
               class="inline-flex mt-6 px-4 py-2 bg-brand text-black border-2 border-black font-bold text-sm pixel-shadow pixel-button-hover uppercase pixel-text">
                Khám phá khóa học
            </a>
        </div>
    @endif
</div>
@endsection