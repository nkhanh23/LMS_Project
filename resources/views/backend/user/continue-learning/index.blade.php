@extends('backend.user.master')

@section('content')
    <div class="space-y-6">
        <div class="mb-6">
            <h3 class="pixel-text font-bold text-xl text-white">
                Tiếp tục học <span class="text-brand">_CONTINUE</span>
            </h3>
            <p class="text-xs text-text-secondary mt-1 font-pixel">
                Danh sách các khóa học bạn đã học gần đây.
            </p>
        </div>

        @if ($enrollments->count())
            <div class="space-y-4">
                @foreach ($enrollments as $enrollment)
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

                    <div
                        class="bg-cyber-surface border-2 border-black pixel-shadow pixel-button-hover p-4 flex flex-col lg:flex-row gap-5">
                        <div class="w-full lg:w-56 h-36 bg-black border border-black overflow-hidden group relative">
                            <img src="{{ $thumbnail }}" alt="{{ $course->course_name ?? 'Course' }}"
                                class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                            <div class="absolute top-2 left-2 px-2 py-1 bg-black/80 border border-brand text-brand text-[10px] font-bold uppercase pixel-text lg:hidden">
                                {{ $percent }}%
                            </div>
                        </div>

                        <div class="flex-1 flex flex-col">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-2">
                                <div>
                                    <h4 class="font-bold text-sm text-white line-clamp-2 mb-1" title="{{ $course->course_name ?? 'Không tìm thấy khóa học' }}">
                                        {{ $course->course_name ?? 'Không tìm thấy khóa học' }}
                                    </h4>

                                    <p class="text-xs text-text-secondary">
                                        Bài đang học:
                                        <span class="text-cyber-cyan font-bold">
                                            {{ $lastLecture->lecture_title ?? ($lastLecture->title ?? 'Chưa có bài gần nhất') }}
                                        </span>
                                    </p>
                                </div>

                                <div class="hidden lg:block px-2 py-1 bg-black border border-brand text-brand text-[10px] font-bold uppercase pixel-text whitespace-nowrap">
                                    {{ $percent }}% hoàn thành
                                </div>
                            </div>

                            <div class="mt-auto mb-4">
                                <div class="w-full h-2 bg-black border border-black">
                                    <div class="h-full bg-brand" style="width: {{ min(100, max(0, $percent)) }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @if ($courseSlug && $lastLecture)
                                    <a href="{{ route('course.lecture.watch', [$courseSlug, $lastLecture->id]) }}"
                                        class="px-4 py-2 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                        Tiếp tục bài học
                                    </a>
                                @elseif($courseSlug)
                                    <a href="{{ route('course.play', $courseSlug) }}"
                                        class="px-4 py-2 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                        Bắt đầu học
                                    </a>
                                @endif

                                @if ($courseSlug)
                                    <a href="{{ route('chi-tiet', $courseSlug) }}"
                                        class="px-4 py-2 bg-cyber-dark text-white border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                        Xem khóa học
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-cyber-surface border-2 border-black pixel-shadow p-10 text-center">
                <div class="text-5xl text-brand mb-4">
                    <i class="fas fa-play-circle"></i>
                </div>

                <h3 class="pixel-text font-bold text-xl text-white mb-2">
                    Chưa có hoạt động học gần đây <span class="text-brand">_EMPTY</span>
                </h3>

                <p class="text-xs text-text-secondary mt-2">
                    Khi bạn bắt đầu học một bài, khóa học sẽ xuất hiện tại đây.
                </p>
            </div>
        @endif
    </div>
@endsection
