<div class="flex-1">
    <!-- Video Player -->
    <div class="relative w-full aspect-video bg-black border-4 border-black pixel-shadow mb-6 overflow-hidden group"
        id="video-container">

        <div id="video-cover"
            class="absolute inset-0 flex items-center justify-center bg-cover bg-center z-20 transition-opacity duration-300"
            data-alt="Video thumbnail"
            style="background-image: url('{{ !empty($currentLecture->image) ? asset($currentLecture->image) : asset($course->course_image) }}');">
            <div id="big-play-btn"
                class="size-24 bg-black/60 border-2 border-black flex items-center justify-center cursor-pointer hover:bg-brand group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-play text-brand group-hover:text-black text-6xl pl-2"></i>
            </div>
        </div>

        @if ($currentLecture->type === 'video' || $currentLecture->type === 'r2_video')
            <video id="actual-video-player" class="w-full h-full object-cover z-10 absolute inset-0">
                <source src="{{ getVideoUrl($currentLecture->type, $currentLecture->url) }}" type="video/mp4" />
                Trình duyệt của bạn không hỗ trợ thẻ video.
            </video>
        @else
            <div class="absolute inset-0 flex items-center justify-center bg-cyber-dark z-10">
                <p class="text-slate-400 uppercase font-bold tracking-widest text-sm">
                    <i class="fa-solid fa-file-lines mr-2"></i> Document Content
                </p>
            </div>
        @endif

        <div
            class="absolute bottom-0 left-0 right-0 bg-black/90 p-4 border-t-4 border-black flex flex-col gap-3 z-30 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="flex items-center gap-4">

                <button id="small-play-btn" class="text-cyber-cyan hover:scale-110 transition-transform w-10">
                    <i class="fa-solid fa-play text-4xl"></i>
                </button>

                <div class="flex-1 flex flex-col gap-1">
                    <div class="flex justify-between text-[10px] font-bold text-brand uppercase">
                        <span id="time-display">HP: 00:00 / 00:00</span>
                        <span id="exp-display">EXP: 0%</span>
                    </div>
                    <div id="progress-container"
                        class="w-full h-4 hp-bar-bg relative cursor-pointer bg-gray-800 border border-gray-600">
                        <div id="progress-bar" class="h-full bg-brand shadow-[0_0_10px_#A6E22E] pointer-events-none"
                            style="width: 0%"></div>
                        <div id="progress-thumb"
                            class="absolute top-0 size-4 bg-white border-2 border-black -mt-0 pointer-events-none"
                            style="left: 0%; transform: translateX(-50%);">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 px-2">
                    <button id="mute-btn">
                        <i
                            class="fa-solid fa-volume-high text-white text-2xl cursor-pointer hover:text-brand transition-colors"></i>
                    </button>
                    <button id="settings-btn">
                        <i
                            class="fa-solid fa-gear text-white text-2xl cursor-pointer hover:text-brand transition-colors"></i>
                    </button>
                    <button id="fullscreen-btn">
                        <i
                            class="fa-solid fa-expand text-white text-2xl cursor-pointer hover:text-brand transition-colors"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Learning Tabs -->
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
                    {{ $currentLecture->course->course_name }}
                </p>
                <div class="flex flex-wrap gap-4 md:gap-6 text-sm font-bold uppercase tracking-widest text-slate-400">
                    <span class="text-yellow-400 flex items-center gap-2"><i class="fa-solid fa-star"></i> 4.4/5 (223
                        XP)</span>
                    <span class="text-cyber-cyan flex items-center gap-2"><i class="fa-solid fa-users"></i> 30.581 Học
                        viên</span>
                    <span class="text-brand flex items-center gap-2"><i class="fa-solid fa-clock"></i> 8,5 Giờ</span>
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
                        <span class="text-white font-bold uppercase">{{ $currentLecture->course->label }}</span>
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
                    @if ($currentLecture->course->certificate == 'yes')
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
                        {{ $currentLecture->course->description }}
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
                                <img src="{{ asset($currentLecture->course->instructor->photo) }}"
                                    alt="{{ $currentLecture->course->instructor->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            </div>
                            <div class="absolute -bottom-2 -right-2 size-4 bg-brand border border-black"></div>
                        </div>
                    </div>

                    <div class="flex-1">
                        <h4 class="text-3xl font-black text-brand hover:underline cursor-pointer mb-1">
                            {{ $currentLecture->course->instructor->name }}</h4>
                        <p class="text-sm text-cyber-cyan font-bold uppercase tracking-widest mb-6">Developer ||
                            Freelancer || Instructor</p>

                        <div
                            class="space-y-4 text-slate-300 leading-relaxed text-base font-sans bg-black/30 p-5 border-l-4 border-slate-700 rounded-r-md">
                            <p>
                                <strong class="text-white">{{ $currentLecture->course->instructor->name }}</strong>
                            </p>
                            <p>
                                {{ $currentLecture->course->instructor->bio }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('actual-video-player');
            const cover = document.getElementById('video-cover');
            const bigPlayBtn = document.getElementById('big-play-btn');
            const smallPlayBtn = document.getElementById('small-play-btn');
            const playIcon = smallPlayBtn.querySelector('i');

            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');
            const progressThumb = document.getElementById('progress-thumb');

            const timeDisplay = document.getElementById('time-display');
            const expDisplay = document.getElementById('exp-display');

            const muteBtn = document.getElementById('mute-btn');
            const muteIcon = muteBtn.querySelector('i');
            const fullscreenBtn = document.getElementById('fullscreen-btn');
            const videoContainer = document.getElementById('video-container');

            // Hàm format thời gian (giây -> mm:ss)
            function formatTime(seconds) {
                if (isNaN(seconds)) return "00:00";
                const min = Math.floor(seconds / 60);
                const sec = Math.floor(seconds % 60);
                return `${min < 10 ? '0' : ''}${min}:${sec < 10 ? '0' : ''}${sec}`;
            }

            // Xử lý Play / Pause
            function togglePlay(e) {
                // 1. Ngăn chặn sự kiện click lan truyền (chống gọi 2 lần)
                if (e) {
                    e.stopPropagation();
                    e.preventDefault();
                }

                if (video.paused) {
                    // 2. Play video trả về một Promise (bất đồng bộ)
                    const playPromise = video.play();

                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            // Play thành công -> Ẩn cover và đổi icon
                            cover.style.display = 'none';
                            playIcon.classList.remove('fa-play');
                            playIcon.classList.add('fa-pause');
                        }).catch(error => {
                            // Bắt lỗi nếu video không thể play (sai link, lỗi mạng...)
                            console.error("Không thể chạy video: ", error);
                            alert("Lỗi tải video. Vui lòng kiểm tra lại đường dẫn video!");
                        });
                    }
                } else {
                    // 3. Pause video bình thường
                    video.pause();
                    playIcon.classList.remove('fa-pause');
                    playIcon.classList.add('fa-play');
                }
            }

            // Gắn event listener (không thay đổi)
            bigPlayBtn.addEventListener('click', togglePlay);
            smallPlayBtn.addEventListener('click', togglePlay);
            video.addEventListener('click', togglePlay);

            // Xử lý thanh tiến trình (HP/EXP)
            video.addEventListener('timeupdate', () => {
                const percent = (video.currentTime / video.duration) * 100;
                progressBar.style.width = `${percent}%`;
                progressThumb.style.left = `${percent}%`;

                timeDisplay.innerText =
                    `HP: ${formatTime(video.currentTime)} / ${formatTime(video.duration)}`;
                expDisplay.innerText = `EXP: ${Math.floor(percent)}%`;
            });

            // Cập nhật tổng thời gian khi video load xong data
            video.addEventListener('loadedmetadata', () => {
                timeDisplay.innerText = `HP: 00:00 / ${formatTime(video.duration)}`;
            });

            // Tua video khi click vào thanh Progress
            progressContainer.addEventListener('click', (e) => {
                const rect = progressContainer.getBoundingClientRect();
                const pos = (e.clientX - rect.left) / rect.width;
                video.currentTime = pos * video.duration;
            });

            // Xử lý Mute
            muteBtn.addEventListener('click', () => {
                video.muted = !video.muted;
                if (video.muted) {
                    muteIcon.classList.remove('fa-volume-high');
                    muteIcon.classList.add('fa-volume-xmark');
                } else {
                    muteIcon.classList.remove('fa-volume-xmark');
                    muteIcon.classList.add('fa-volume-high');
                }
            });

            // Xử lý Fullscreen
            fullscreenBtn.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    videoContainer.requestFullscreen().catch(err => {
                        console.log(`Error attempting to enable fullscreen: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen();
                }
            });
        });
    </script>
@endpush
