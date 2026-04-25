@extends('backend.user.master')

@section('content')
    <div class="space-y-6">
        @php
            $course = $session->course;
            $lecture = $session->lecture;
        @endphp

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h3 class="pixel-text font-bold text-xl text-white">
                    Chi tiết phiên AI Tutor <span class="text-cyber-cyan">_SESSION_{{ $session->id }}</span>
                </h3>
                <p class="text-xs text-text-secondary mt-1 font-pixel">
                    {{ $session->title ?: 'Cuộc trò chuyện #' . $session->id }}
                </p>
            </div>

            <a href="{{ route('user.ai-tutor.history') }}"
                class="px-4 py-2 bg-cyber-dark border-2 border-black text-white font-bold text-sm pixel-shadow-sm pixel-button-hover pixel-text uppercase">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>

        {{-- Session Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-cyber-surface border-2 border-black p-4 pixel-shadow flex flex-col justify-between group">
                <div class="text-text-secondary text-[10px] font-bold uppercase pixel-text mb-2">Khóa học</div>
                <div class="text-sm font-bold text-cyber-cyan truncate" title="{{ $course->course_name ?? 'N/A' }}">
                    {{ $course->course_name ?? 'Không rõ khóa học' }}
                </div>
            </div>

            <div class="bg-cyber-surface border-2 border-black p-4 pixel-shadow flex flex-col justify-between group">
                <div class="text-text-secondary text-[10px] font-bold uppercase pixel-text mb-2">Bài học</div>
                <div class="text-sm font-bold text-brand truncate" title="{{ $lecture->lecture_title ?? $lecture->title ?? 'N/A' }}">
                    {{ $lecture->lecture_title ?? ($lecture->title ?? 'Không rõ bài học') }}
                </div>
            </div>

            <div class="bg-cyber-surface border-2 border-black p-4 pixel-shadow flex flex-col justify-between group">
                <div class="text-text-secondary text-[10px] font-bold uppercase pixel-text mb-2">Tin nhắn</div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-white font-pixel">{{ $session->messages->count() }}</span>
                    <span class="text-[10px] text-text-secondary font-pixel">
                        · {{ optional($session->last_activity_at)->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Chat Messages --}}
        <div class="bg-cyber-surface border-2 border-black pixel-shadow">
            <div class="p-4 border-b-2 border-black bg-black/40 flex items-center justify-between">
                <h3 class="pixel-text font-bold text-lg text-white">
                    Nội dung trò chuyện
                </h3>

                @if ($course && $lecture)
                    <a href="{{ route('course.lecture.watch', [$course->course_name_slug, $lecture->id]) }}"
                        class="px-3 py-1.5 bg-brand text-black border border-black font-bold text-[10px] pixel-button-hover uppercase pixel-text">
                        <i class="fas fa-play mr-1"></i> Mở bài học
                    </a>
                @endif
            </div>

            <div class="p-6 space-y-4 max-h-[600px] overflow-y-auto">
                @forelse($session->messages as $message)
                    @if ($message->role === 'user')
                        {{-- User Message --}}
                        <div class="flex justify-end">
                            <div class="max-w-[75%]">
                                <div class="text-[10px] text-text-secondary mb-1 text-right font-pixel uppercase">
                                    Bạn · {{ optional($message->created_at)->format('H:i') }}
                                </div>
                                <div class="bg-brand/20 border border-brand/40 p-3 text-sm text-white">
                                    {{ $message->content }}
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- AI Message --}}
                        <div class="flex justify-start">
                            <div class="max-w-[75%]">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-5 h-5 bg-cyber-cyan/20 border border-cyber-cyan flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-robot text-cyber-cyan text-[8px]"></i>
                                    </div>
                                    <span class="text-[10px] text-text-secondary font-pixel uppercase">
                                        AI Tutor · {{ optional($message->created_at)->format('H:i') }}
                                    </span>
                                </div>
                                <div class="bg-cyber-dark border border-black p-3 text-sm text-text-primary">
                                    {!! nl2br(e($message->content)) !!}
                                </div>

                                {{-- Citations --}}
                                @if ($message->citations && $message->citations->count())
                                    <div class="mt-2 p-2 bg-black/40 border border-black/60 text-[10px] text-text-secondary">
                                        <div class="font-bold text-cyber-cyan mb-1 uppercase pixel-text">Nguồn tham khảo:</div>
                                        @foreach ($message->citations as $citation)
                                            <div class="flex items-start gap-1 mt-1">
                                                <i class="fas fa-file-alt text-cyber-cyan/60 mt-0.5"></i>
                                                <span>{{ $citation->document->title ?? ($citation->chunk->content ?? 'Tài liệu') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center p-8">
                        <p class="text-xs text-text-secondary font-pixel">
                            Chưa có tin nhắn trong phiên này.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
