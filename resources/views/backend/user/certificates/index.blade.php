@extends('backend.user.master')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="pixel-text font-bold text-xl text-white uppercase tracking-tighter">
                    Chứng chỉ <span class="text-brand">_CERTIFICATES</span>
                </h3>
                <p class="text-xs text-text-secondary mt-1 font-pixel">
                    Các chứng chỉ bạn đã mở khóa sau khi hoàn thành khóa học.
                </p>
            </div>

            <a href="{{ route('user.my-courses') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-cyber-dark text-white border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover pixel-text uppercase">
                <i class="fas fa-book-open text-[10px] text-cyber-cyan"></i>
                Khóa học của tôi
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-cyber-surface border-2 border-black pixel-shadow p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[10px] text-text-secondary font-pixel uppercase">Đã nhận</p>
                        <p class="text-2xl font-black text-white mt-1">{{ $enrollments->total() }}</p>
                    </div>
                    <div class="w-11 h-11 bg-brand border-2 border-black flex items-center justify-center text-black">
                        <i class="fas fa-certificate"></i>
                    </div>
                </div>
            </div>

            <div class="bg-cyber-surface border-2 border-black pixel-shadow p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[10px] text-text-secondary font-pixel uppercase">Tiến độ yêu cầu</p>
                        <p class="text-2xl font-black text-brand mt-1">100%</p>
                    </div>
                    <div class="w-11 h-11 bg-cyber-cyan border-2 border-black flex items-center justify-center text-black">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>

            <div class="bg-cyber-surface border-2 border-black pixel-shadow p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[10px] text-text-secondary font-pixel uppercase">Mới nhất</p>
                        <p class="text-sm font-bold text-white mt-2 truncate">
                            {{ optional($enrollments->first()?->courseProgress?->completed_at ?? $enrollments->first()?->completed_at)->format('d/m/Y') ?? 'Chưa có' }}
                        </p>
                    </div>
                    <div class="w-11 h-11 bg-pink-500 border-2 border-black flex items-center justify-center text-white">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>

        @if ($enrollments->count())
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                @foreach ($enrollments as $enrollment)
                    @php
                        $course = $enrollment->course;
                        $progress = $enrollment->courseProgress;
                        $completedAt = $progress?->completed_at ?? $enrollment->completed_at;
                        $courseSlug = $course?->course_name_slug;
                        $thumbnail = $course?->course_image
                            ? asset($course->course_image)
                            : asset('frontend/assets/img/course/default.jpg');
                    @endphp

                    <div class="bg-cyber-surface border-2 border-black pixel-shadow overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr]">
                            <div class="relative min-h-44 md:min-h-full bg-black border-b-2 md:border-b-0 md:border-r-2 border-black overflow-hidden group">
                                <img src="{{ $thumbnail }}" alt="{{ $course->course_name ?? 'Course' }}"
                                    class="absolute inset-0 w-full h-full object-cover opacity-75 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                <div class="absolute bottom-3 left-3 px-2 py-1 bg-brand text-black border-2 border-black text-[10px] font-black pixel-text uppercase">
                                    Completed
                                </div>
                            </div>

                            <div class="p-5 flex flex-col min-h-64">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <div class="min-w-0">
                                        <p class="text-[10px] text-brand font-pixel uppercase mb-2">
                                            Certificate ID: SL-{{ str_pad($enrollment->id, 5, '0', STR_PAD_LEFT) }}
                                        </p>
                                        <h4 class="font-bold text-base text-white line-clamp-2" title="{{ $course->course_name ?? 'Không tìm thấy khóa học' }}">
                                            {{ $course->course_name ?? 'Không tìm thấy khóa học' }}
                                        </h4>
                                        <p class="text-xs text-text-secondary mt-2">
                                            Giảng viên:
                                            <span class="text-cyber-cyan font-bold uppercase">{{ $course->instructor->name ?? 'N/A' }}</span>
                                        </p>
                                    </div>

                                    <div class="shrink-0 w-12 h-12 bg-brand border-2 border-black flex items-center justify-center text-black pixel-shadow-sm">
                                        <i class="fas fa-award text-lg"></i>
                                    </div>
                                </div>

                                <div class="mt-auto space-y-4">
                                    <div>
                                        <div class="flex justify-between text-[10px] font-bold mb-1.5 pixel-text uppercase">
                                            <span class="text-text-secondary">Hoàn thành</span>
                                            <span class="text-brand">{{ $progress?->completion_percent ?? 100 }}%</span>
                                        </div>
                                        <div class="w-full h-2 bg-black border border-black overflow-hidden">
                                            <div class="h-full bg-brand" style="width: 100%"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <div class="bg-black/30 border border-black p-3">
                                            <p class="text-[9px] text-text-secondary font-pixel uppercase">Ngày cấp</p>
                                            <p class="text-white font-bold mt-1">{{ optional($completedAt)->format('d/m/Y') ?? 'Đang cập nhật' }}</p>
                                        </div>
                                        <div class="bg-black/30 border border-black p-3">
                                            <p class="text-[9px] text-text-secondary font-pixel uppercase">Bài học</p>
                                            <p class="text-white font-bold mt-1">
                                                {{ $progress?->completed_lectures ?? 0 }}/{{ $progress?->total_lectures ?? 0 }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <button type="button"
                                            class="px-4 py-2 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm uppercase pixel-text cursor-default">
                                            <i class="fas fa-certificate mr-1"></i>
                                            Đã mở khóa
                                        </button>

                                        @if ($courseSlug)
                                            <a href="{{ route('chi-tiet', $courseSlug) }}"
                                                class="px-4 py-2 bg-cyber-dark text-white border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                                Xem khóa học
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 border-t-2 border-black/30 pt-6 flex justify-center">
                {{ $enrollments->links('vendor.pagination.cyber-pixel') }}
            </div>
        @else
            <div class="bg-cyber-surface border-2 border-black pixel-shadow p-10 text-center">
                <div class="text-5xl text-brand mb-4">
                    <i class="fas fa-certificate"></i>
                </div>

                <h3 class="pixel-text font-bold text-xl text-white mb-2">
                    Chưa có chứng chỉ <span class="text-brand">_EMPTY</span>
                </h3>

                <p class="text-xs text-text-secondary mt-2 max-w-xl mx-auto">
                    Hoàn thành 100% khóa học có hỗ trợ chứng chỉ để chứng chỉ của bạn xuất hiện tại đây.
                </p>

                <a href="{{ route('user.my-courses') }}"
                    class="inline-flex items-center gap-2 mt-6 px-4 py-2 bg-brand text-black border-2 border-black font-bold text-sm pixel-shadow pixel-button-hover uppercase pixel-text">
                    <i class="fas fa-play-circle text-[11px]"></i>
                    Tiếp tục học
                </a>
            </div>
        @endif
    </div>
@endsection
