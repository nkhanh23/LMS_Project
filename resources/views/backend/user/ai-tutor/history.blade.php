@extends('backend.user.master')

@section('content')
    @php
        $isWebsiteMode = $activeMode === 'website';
    @endphp

    <div class="space-y-6">
        <div class="mb-6">
            <h3 class="pixel-text font-bold text-xl text-white">
                Lich su chatbot <span class="text-cyber-cyan">_CHAT_HISTORY</span>
            </h3>
            <p class="text-xs text-text-secondary mt-1 font-pixel">
                Xem lai cac cuoc tro chuyen theo tung mode va bat dau phien moi dung ngu canh.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="inline-flex border-2 border-black pixel-shadow overflow-hidden w-full lg:w-auto">
                <a href="{{ route('user.ai-tutor.history', ['mode' => 'lesson']) }}"
                    class="px-4 py-2 text-xs font-bold uppercase pixel-text border-r-2 border-black {{ $isWebsiteMode ? 'bg-cyber-surface text-white hover:bg-white/10' : 'bg-brand text-black' }}">
                    Hoi ve bai hoc
                </a>
                <a href="{{ route('user.ai-tutor.history', ['mode' => 'website']) }}"
                    class="px-4 py-2 text-xs font-bold uppercase pixel-text {{ $isWebsiteMode ? 'bg-brand text-black' : 'bg-cyber-surface text-white hover:bg-white/10' }}">
                    Hoi ve trang
                </a>
            </div>

            <div class="flex flex-wrap gap-2">
                @if ($isWebsiteMode)
                    <a href="{{ route('frontend.home', ['assistant' => 'website', 'assistant_action' => 'new']) }}"
                        class="px-3 py-2 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                        Tao phien chat moi
                    </a>
                @else
                    <a href="{{ route('user.my-courses') }}"
                        class="px-3 py-2 bg-cyber-dark text-white border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                        Chon bai de chat moi
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-cyber-surface border-2 border-black pixel-shadow overflow-hidden">
            @if ($sessions->count())
                <div class="divide-y-2 divide-black">
                    @foreach ($sessions as $session)
                        @php
                            $isSessionWebsite = $session->isWebsiteMode();
                            $latestUserMessage = $session->messages->firstWhere('role', 'user');
                            $sessionTitle = $session->title;

                            if (! $sessionTitle && $latestUserMessage) {
                                $sessionTitle = \Illuminate\Support\Str::limit($latestUserMessage->content, 80);
                            }

                            if (! $sessionTitle) {
                                $sessionTitle = $isSessionWebsite ? 'Phien hoi ve trang #' . $session->id : 'Phien hoi ve bai hoc #' . $session->id;
                            }
                        @endphp

                        <div class="p-4 hover:bg-white/5 transition-colors flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 {{ $isSessionWebsite ? 'bg-brand/20 border-brand text-brand' : 'bg-cyber-cyan/20 border-cyber-cyan text-cyber-cyan' }} border flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-robot text-xs"></i>
                                    </div>
                                    <h4 class="font-bold text-sm text-white truncate">
                                        {{ $sessionTitle }}
                                    </h4>
                                    <span
                                        class="px-2 py-1 text-[10px] uppercase font-bold border border-black {{ $isSessionWebsite ? 'bg-brand text-black' : 'bg-cyber-cyan text-black' }}">
                                        {{ $isSessionWebsite ? 'Hoi ve trang' : 'Hoi ve bai hoc' }}
                                    </span>
                                </div>

                                <div class="text-[10px] text-text-secondary space-y-1 ml-11">
                                    @if ($isSessionWebsite)
                                        <div>
                                            Pham vi:
                                            <span class="text-brand font-bold">
                                                Tro ly he thong toan site
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            Khoa hoc:
                                            <span class="text-cyber-cyan font-bold">
                                                {{ $session->course->course_name ?? 'Khong ro khoa hoc' }}
                                            </span>
                                        </div>

                                        <div>
                                            Bai hoc:
                                            <span class="text-brand font-bold">
                                                {{ $session->lecture->lecture_title ?? ($session->lecture->title ?? 'Khong ro bai hoc') }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="font-pixel">
                                        <span class="text-white font-bold">{{ $session->messages->count() }}</span> tin nhan
                                        · {{ optional($session->last_activity_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 flex-shrink-0 ml-11 lg:ml-0">
                                @if ($session->course && $session->lecture && ! $isSessionWebsite)
                                    <a href="{{ route('course.lecture.watch', [$session->course->course_name_slug, $session->lecture->id]) }}"
                                        class="px-3 py-2 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                                        Mo bai hoc
                                    </a>
                                @endif

                                <a href="{{ route('user.ai-tutor.show', ['session' => $session->id, 'mode' => $activeMode]) }}"
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
                        Chua co lich su chatbot <span class="text-cyber-cyan">_EMPTY</span>
                    </h3>

                    <p class="text-xs text-text-secondary mt-2">
                        {{ $isWebsiteMode ? 'Khi ban hoi tro ly he thong, lich su se hien thi tai day.' : 'Khi ban hoi AI trong trang hoc, lich su se hien thi tai day.' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
