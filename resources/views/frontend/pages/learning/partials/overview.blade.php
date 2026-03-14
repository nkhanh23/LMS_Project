<div id="tab-overview" class="tab-content py-8">
    <div class="flex flex-col space-y-8 text-slate-300 font-sans max-w-4xl">
        <div class="border-b-4 border-slate-700 pb-6">
            <h2 class="text-2xl font-black text-white uppercase tracking-wider mb-4 flex items-center gap-3">
                <i class="fa-solid fa-circle-info text-brand"></i> Về khóa học này
            </h2>
            <p class="text-white text-xl font-bold leading-relaxed mb-4">
                {{ $currentLecture->course->course_name ?? $course->course_name }}
            </p>
            <div class="flex flex-wrap gap-4 md:gap-6 text-sm font-bold uppercase tracking-widest text-slate-400">
                <span class="text-yellow-400 flex items-center gap-2"><i class="fa-solid fa-star"></i> 4.4/5
                    (223 XP)</span>
                <span class="text-cyber-cyan flex items-center gap-2"><i class="fa-solid fa-users"></i>
                    30.581 Học viên</span>
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
                    <i class="fa-solid fa-language text-slate-500 w-5 text-center"></i>
                    <span class="text-slate-400 w-32">Ngôn ngữ:</span>
                    <span class="text-white font-bold">Tiếng Anh</span>
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
                        <p class="text-sm text-slate-400">Nhận chứng nhận sau khi hoàn thành.</p>
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
                    <p class="text-sm text-slate-400">Có sẵn trên mọi nền tảng.</p>
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
                    <p class="text-sm text-cyber-cyan font-bold uppercase tracking-widest mb-6">Instructor
                    </p>
                    <div
                        class="space-y-4 text-slate-300 leading-relaxed text-base font-sans bg-black/30 p-5 border-l-4 border-slate-700 rounded-r-md">
                        <p>
                            {{ $currentLecture->course->instructor->bio ?? ($course->instructor->bio ?? '') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
