@extends('backend.user.master')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h3 class="pixel-text font-bold text-xl text-white">
                Khóa học của tôi <span class="text-brand">_MY_COURSES</span>
            </h3>
            <p class="text-xs text-text-secondary mt-1 font-pixel">
                Theo dõi các khóa học bạn đã ghi danh và tiếp tục học nhanh.
            </p>
        </div>

        <a href="{{ route('frontend.home') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-brand text-black border-2 border-black font-bold text-sm pixel-shadow pixel-button-hover pixel-text">
            <i class="fas fa-search"></i>
            Tìm khóa học mới
        </a>
    </div>

    @if($enrollments->count())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
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

                <div class="bg-cyber-surface border-2 border-black pixel-shadow pixel-button-hover p-4 flex flex-col h-full">
                    <div class="relative h-44 bg-black border border-black mb-4 group overflow-hidden">
                        <img src="{{ $thumbnail }}"
                             alt="{{ $course->course_name ?? 'Course' }}"
                             class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">

                        <div class="absolute top-2 left-2 px-2 py-1 bg-black/80 border border-brand text-brand text-[10px] font-bold uppercase pixel-text">
                            {{ $percent }}% hoàn thành
                        </div>
                    </div>

                    <div class="flex flex-col flex-1">
                        <div>
                            <h4 class="font-bold text-sm text-white line-clamp-2 mb-1" title="{{ $course->course_name ?? 'Không tìm thấy khóa học' }}">
                                {{ $course->course_name ?? 'Không tìm thấy khóa học' }}
                            </h4>

                            <p class="text-xs text-text-secondary mb-4">
                                Giảng viên:
                                <span class="text-cyber-cyan font-bold">
                                    {{ $course->instructor->name ?? 'N/A' }}
                                </span>
                            </p>
                        </div>

                        <div class="mt-auto">
                            <div class="flex justify-between text-[10px] font-bold mb-2 pixel-text">
                                <span class="text-text-secondary">Tiến độ</span>
                                <span class="text-brand">{{ $percent }}%</span>
                            </div>

                            <div class="w-full h-2 bg-black border border-black mb-4">
                                <div class="h-full bg-brand"
                                     style="width: {{ min(100, max(0, $percent)) }}%">
                                </div>
                            </div>

                            <div class="text-[10px] text-text-secondary mb-4 truncate" title="{{ $lastLecture->lecture_title ?? $lastLecture->title ?? 'Bài học' }}">
                                @if($lastLecture)
                                    Bài gần nhất:
                                    <span class="text-white">
                                        {{ $lastLecture->lecture_title ?? $lastLecture->title ?? 'Bài học' }}
                                    </span>
                                @else
                                    Bạn chưa bắt đầu khóa học này.
                                @endif
                            </div>

                            <div class="flex gap-2">
                                @if($courseSlug)
                                    @if($lastLecture)
                                        <a href="{{ route('course.lecture.watch', [$courseSlug, $lastLecture->id]) }}"
                                           class="flex-1 text-center px-3 py-2 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                            Tiếp tục
                                        </a>
                                    @else
                                        <a href="{{ route('course.play', $courseSlug) }}"
                                           class="flex-1 text-center px-3 py-2 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                            Bắt đầu
                                        </a>
                                    @endif
                                @endif

                                @if($courseSlug)
                                    <a href="{{ route('chi-tiet', $courseSlug) }}"
                                       class="px-3 py-2 bg-cyber-dark text-white border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text flex items-center justify-center">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $enrollments->links() }}
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