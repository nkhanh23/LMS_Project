@extends('backend.user.master')

@section('content')
    <div class="space-y-6">
        <div class="mb-6">
            <h3 class="pixel-text font-bold text-xl text-white">
                Lịch sử AI Tutor <span class="text-cyber-cyan">_AI_SESSIONS</span>
            </h3>
            <p class="text-xs text-text-secondary mt-1 font-pixel">
                Xem lại các cuộc trò chuyện với gia sư ảo theo từng khóa học và bài học.
            </p>
        </div>

        <div class="bg-cyber-surface border-2 border-black pixel-shadow overflow-hidden">
            @if ($sessions->count())
                <div class="divide-y-2 divide-black">
                    @foreach ($sessions as $session)
                        <div class="p-4 hover:bg-white/5 transition-colors flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-cyber-cyan/20 border border-cyber-cyan flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-robot text-cyber-cyan text-xs"></i>
                                    </div>
                                    <h4 class="font-bold text-sm text-white truncate">
                                        {{ $session->lecture ? 'Chat bài học: ' . ($session->lecture->lecture_title ?? $session->lecture->title) : ($session->title ?: 'Cuộc trò chuyện #' . $session->id) }}
                                    </h4>
                                </div>

                                <div class="text-[10px] text-text-secondary space-y-1 ml-11">
                                    <div>
                                        Khóa học:
                                        <span class="text-cyber-cyan font-bold">
                                            {{ $session->course->course_name ?? 'Không rõ khóa học' }}
                                        </span>
                                    </div>

                                    <div>
                                        Bài học:
                                        <span class="text-brand font-bold">
                                            {{ $session->lecture->lecture_title ?? ($session->lecture->title ?? 'Không rõ bài học') }}
                                        </span>
                                    </div>

                                    <div class="font-pixel">
                                        <span class="text-white font-bold">{{ $session->messages->count() }}</span> tin nhắn
                                        · {{ optional($session->last_activity_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 flex-shrink-0 ml-11 lg:ml-0">
                                @if ($session->course && $session->lecture)
                                    <a href="{{ route('course.lecture.watch', [$session->course->course_name_slug, $session->lecture->id]) }}"
                                        class="px-3 py-2 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                        Mở bài học
                                    </a>
                                @endif

                                <a href="{{ route('user.ai-tutor.show', $session->id) }}"
                                    class="px-3 py-2 bg-cyber-dark text-white border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                    Xem chat
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t-2 border-black bg-black/20">
                    {{ $sessions->links() }}
                </div>
            @else
                <div class="p-10 text-center">
                    <div class="text-5xl text-cyber-cyan mb-4">
                        <i class="fas fa-robot"></i>
                    </div>

                    <h3 class="pixel-text font-bold text-xl text-white mb-2">
                        Chưa có lịch sử AI Tutor <span class="text-cyber-cyan">_EMPTY</span>
                    </h3>

                    <p class="text-xs text-text-secondary mt-2">
                        Khi bạn hỏi AI Tutor trong trang học, lịch sử sẽ hiển thị tại đây.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
