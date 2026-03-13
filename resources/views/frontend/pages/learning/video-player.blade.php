<div class="flex-1 flex flex-col">
    <!-- Video Player -->
    <div class="relative w-full aspect-video bg-black border-b-4 border-black overflow-hidden group" id="video-container">


        @if ($currentLecture)
            @if ($currentLecture->type === 'video' || $currentLecture->type === 'r2_video')
                <video id="actual-video-player" class="w-full h-full object-cover z-10 absolute inset-0" playsinline
                    preload="auto" src="{{ getVideoUrl($currentLecture->type, $currentLecture->url) }}" type="video/mp4"
                    controls
                    poster="{{ !empty($currentLecture->image) ? asset($currentLecture->image) : asset($course->course_image) }}">
                    Trình duyệt của bạn không hỗ trợ thẻ video.
                </video>
            @elseif ($currentLecture->type === 'text')
                <div
                    class="absolute inset-0 flex flex-col bg-cyber-dark z-10 p-6 md:p-10 overflow-y-auto custom-scrollbar">
                    <div class="max-w-4xl mx-auto w-full">
                        <h3
                            class="text-yellow-400 font-black uppercase tracking-widest mb-6 flex items-center gap-3 border-b-2 border-yellow-400/30 pb-4">
                            <i class="fa-solid fa-file-lines text-3xl"></i>
                            {{ $currentLecture->lecture_title }}
                        </h3>
                        <div
                            class="prose prose-invert prose-brand max-w-none text-slate-200 font-sans leading-relaxed
                            [&_ol]:list-decimal [&_ol]:pl-5 [&_ul]:list-disc [&_ul]:pl-5">
                            {!! $currentLecture->content !!}
                        </div>
                    </div>
                </div>
            @elseif ($currentLecture->type === 'document')
                <div class="absolute inset-0 flex flex-col items-center justify-center bg-cyber-dark z-10 p-6">
                    <div class="max-w-md w-full bg-cyber-surface border-4 border-black p-8 pixel-shadow text-center">
                        <div
                            class="size-20 bg-brand/20 border-2 border-brand flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-file-pdf text-brand text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-white uppercase tracking-wider mb-2">Tài liệu đính kèm</h3>
                        <p class="text-slate-400 text-sm mb-8 font-mono">
                            {{ $currentLecture->file_name ?? 'lecture-resource.pdf' }}
                            @if ($currentLecture->file_size)
                                <span class="block text-[10px] mt-1 text-slate-500">Kích thước:
                                    {{ number_format($currentLecture->file_size / 1024 / 1024, 2) }} MB</span>
                            @endif
                        </p>

                        <a href="{{ route('lecture.downloadDocument', $currentLecture->id) }}"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-brand text-black font-black uppercase tracking-widest border-4 border-black hover:translate-x-1 hover:-translate-y-1 transition-transform pixel-shadow-sm">
                            <i class="fa-solid fa-download text-xl"></i>
                            Tải xuống ngay
                        </a>
                    </div>
                </div>
            @else
                <div class="absolute inset-0 flex items-center justify-center bg-cyber-dark z-10">
                    <p class="text-slate-400 uppercase font-bold tracking-widest text-sm text-center">
                        <i class="fa-solid fa-circle-info mr-2"></i> Nội dung không khả dụng
                    </p>
                </div>
            @endif
        @else
            <div class="absolute inset-0 flex items-center justify-center bg-cyber-dark z-10">
                <p class="text-slate-400 uppercase font-bold tracking-widest text-sm">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> No Lecture Selected
                </p>
            </div>
        @endif

    </div>


    <!-- Learning Tabs & Info with padding -->
    <div class="p-4 md:p-8">
        <div class="flex items-end">
            <button
                class="px-8 py-3 bg-cyber-surface text-white font-bold uppercase border-2 border-black border-b-0 relative z-10 -mb-[2px] pixel-shadow-sm">
                Tổng quan
            </button>
            <button
                class="px-8 py-3 bg-cyber-dark text-slate-400 font-bold uppercase border-2 border-black border-b-0 hover:bg-cyber-surface hover:text-white transition-colors">
                Hỏi & Đáp
            </button>
            <button
                class="px-8 py-3 bg-cyber-dark text-slate-400 font-bold uppercase border-2 border-black border-b-0 border-l-0 hover:bg-cyber-surface hover:text-white transition-colors">
                Ghi chú
            </button>
            <button
                class="px-8 py-3 bg-cyber-dark text-slate-400 font-bold uppercase border-2 border-black border-b-0 border-l-0 hover:bg-cyber-surface hover:text-white transition-colors">
                Đánh giá
            </button>
        </div>
        <div class="space-y-10 text-slate-300 font-sans p-2">

            <div class="flex flex-col space-y-8 text-slate-300 font-sans p-2 max-w-4xl">

                <div class="border-b-4 border-slate-700 pb-6">
                    <h2 class="text-2xl font-black text-white uppercase tracking-wider mb-4 flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-brand"></i> Về khóa học này
                    </h2>
                    <p class="text-white text-xl font-bold leading-relaxed mb-4">
                        {{ $currentLecture->course->course_name ?? $course->course_name }}
                    </p>
                    <div
                        class="flex flex-wrap gap-4 md:gap-6 text-sm font-bold uppercase tracking-widest text-slate-400">
                        <span class="text-yellow-400 flex items-center gap-2"><i class="fa-solid fa-star"></i> 4.4/5
                            (223
                            XP)</span>
                        <span class="text-cyber-cyan flex items-center gap-2"><i class="fa-solid fa-users"></i> 30.581
                            Học
                            viên</span>
                        <span class="text-brand flex items-center gap-2"><i class="fa-solid fa-clock"></i> 8,5
                            Giờ</span>
                    </div>
                </div>

                <div class="bg-cyber-dark border-l-4 border-cyber-cyan p-6 relative">
                    <h3 class="text-cyber-cyan font-bold uppercase tracking-widest text-sm mb-4">
                        >_ Theo số liệu
                    </h3>
                    <ul class="space-y-4 text-sm font-mono flex flex-col">
                        <li class="flex items-center gap-4">
                            <i class="fa-solid fa-chart-simple text-slate-500 w-5 text-center"></i>
                            <span class="text-slate-400 w-32">Trình độ:</span>
                            <span
                                class="text-white font-bold uppercase">{{ $currentLecture->course->label ?? $course->label }}</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fa-solid fa-users text-slate-500 w-5 text-center"></i>
                            <span class="text-slate-400 w-32">Học viên:</span>
                            <span class="text-white font-bold">30.581</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fa-solid fa-language text-slate-500 w-5 text-center"></i>
                            <span class="text-slate-400 w-32">Ngôn ngữ:</span>
                            <span class="text-white font-bold">Tiếng Anh</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fa-regular fa-closed-captioning text-slate-500 w-5 text-center"></i>
                            <span class="text-slate-400 w-32">Phụ đề:</span>
                            <span class="text-white font-bold">Có</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fa-solid fa-file-video text-slate-500 w-5 text-center"></i>
                            <span class="text-slate-400 w-32">Bài giảng:</span>
                            <span class="text-white font-bold">50</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fa-solid fa-clock text-slate-500 w-5 text-center"></i>
                            <span class="text-slate-400 w-32">Video:</span>
                            <span class="text-white font-bold">Tổng số 8,5 giờ</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row gap-6">
                    <div
                        class="flex-1 bg-cyber-surface border-2 border-slate-700 p-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center group hover:border-yellow-400 transition-colors cursor-default">
                        <div
                            class="size-12 shrink-0 bg-black border-2 border-yellow-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-award text-yellow-400 text-2xl"></i>
                        </div>
                        @if (($currentLecture->course->certificate ?? $course->certificate) == 'yes')
                            <div>
                                <h4 class="text-white font-bold uppercase tracking-wider mb-1">Giấy chứng nhận</h4>
                                <p class="text-sm text-slate-400">Nhận chứng nhận Udemy khi hoàn thành khóa học.</p>
                            </div>
                        @endif
                    </div>
                    <div
                        class="flex-1 bg-cyber-surface border-2 border-slate-700 p-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center group hover:border-brand transition-colors cursor-default">
                        <div
                            class="size-12 shrink-0 bg-black border-2 border-brand flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-mobile-screen text-brand text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold uppercase tracking-wider mb-1">Đặc điểm</h4>
                            <p class="text-sm text-slate-400">Hiện có sẵn trên nền tảng <strong
                                    class="text-white">iOS</strong> và <strong class="text-white">Android</strong>.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 border-4 border-slate-700 p-6 md:p-8 relative mt-4 shadow-lg">
                    <div
                        class="absolute -top-4 left-6 bg-brand text-black px-3 py-1 font-bold text-xs uppercase tracking-widest border-2 border-black flex items-center gap-2">
                        <i class="fa-solid fa-terminal"></i> Mô tả chi tiết
                    </div>

                    <div class="space-y-5 text-base text-slate-200 leading-relaxed mt-4 font-sans">
                        <p>
                            {{ $currentLecture->course->description ?? $course->description }}
                        </p>
                    </div>
                </div>

                <div class="bg-cyber-surface border-4 border-black p-6 md:p-8 pixel-shadow relative mt-4">
                    <h3
                        class="text-2xl font-bold text-white uppercase mb-6 flex items-center gap-3 border-b-2 border-slate-700 pb-4">
                        <i class="fa-solid fa-user-astronaut text-brand"></i> Giảng viên
                    </h3>

                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        <div class="flex flex-col gap-4 shrink-0">
                            <div class="w-32 h-32 bg-black border-2 border-brand p-1 relative group cursor-pointer">
                                <div
                                    class="w-full h-full bg-slate-800 flex items-center justify-center border border-slate-700 overflow-hidden">
                                    <img src="{{ asset($currentLecture->course->instructor->photo ?? ($course->instructor->photo ?? '')) }}"
                                        alt="{{ $currentLecture->course->instructor->name ?? ($course->instructor->name ?? '') }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <div class="absolute -bottom-2 -right-2 size-4 bg-brand border border-black"></div>
                            </div>
                        </div>

                        <div class="flex-1">
                            <h4 class="text-3xl font-black text-brand hover:underline cursor-pointer mb-1">
                                {{ $currentLecture->course->instructor->name ?? ($course->instructor->name ?? '') }}
                            </h4>
                            <p class="text-sm text-cyber-cyan font-bold uppercase tracking-widest mb-6">Developer ||
                                Freelancer || Instructor</p>

                            <div
                                class="space-y-4 text-slate-300 leading-relaxed text-base font-sans bg-black/30 p-5 border-l-4 border-slate-700 rounded-r-md">
                                <p>
                                    <strong
                                        class="text-white">{{ $currentLecture->course->instructor->name }}</strong>
                                </p>
                                <p>
                                    {{ $currentLecture->course->instructor->bio ?? ($course->instructor->bio ?? '') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
