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
