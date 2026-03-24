<aside class="w-full flex flex-col h-full">
    <div class="bg-black text-cyber-cyan px-4 py-3 border-4 border-black border-b-0">
        <h2 class="text-xl font-bold uppercase tracking-widest flex items-center gap-2">
            <i class="fa-solid fa-clipboard-list"></i>
            Quest Log
        </h2>
    </div>

    <div class="bg-cyber-surface border-x-4 border-b-4 border-black flex-1 custom-scrollbar overflow-y-auto">
        <div class="flex flex-col">

            @foreach ($sections as $index => $section)
                <!-- Section -->
                <details class="border-b-2 border-black group"
                    {{ $currentLecture->section_id == $section->id ? 'open' : '' }}>
                    <summary
                        class="p-4 {{ $currentLecture->section_id == $section->id ? 'bg-white/5' : 'hover:bg-white/5' }} flex items-center justify-between cursor-pointer list-none transition-colors border-b border-black/10">
                        <div class="flex flex-col gap-1">
                            <h3
                                class="text-lg font-black {{ $currentLecture->section_id == $section->id ? 'text-brand' : 'text-white' }} uppercase tracking-tighter transition-colors">
                                {{ $section->section_title }}
                            </h3>
                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase">
                                @php
                                    $sectionCompleted = $section->lecture
                                        ->filter(function ($lecture) use ($lessonProgressMap) {
                                            return optional($lessonProgressMap->get($lecture->id))->status ===
                                                'completed';
                                        })
                                        ->count();
                                @endphp

                                <span>{{ $sectionCompleted }} / {{ $section->lecture->count() }}</span>
                                <span class="text-slate-700">|</span>
                                <span>{{ $section->lecture->sum('video_duration') ?: '0' }} phút</span>
                            </div>
                        </div>
                        <i
                            class="fa-solid fa-chevron-down {{ $currentLecture->section_id == $section->id ? 'text-brand' : 'text-slate-500' }} transition-transform group-open:rotate-180"></i>
                    </summary>

                    <div class="bg-black/20">
                        @foreach ($section->lecture as $lecture)
                            @php
                                $lectureProgress = $lessonProgressMap->get($lecture->id);
                                $isCompleted = optional($lectureProgress)->status === 'completed';
                                $isUnlocked = app(\App\Services\LearningProgressService::class)->isLectureUnlocked(
                                    $course,
                                    $enrollment,
                                    $lecture,
                                );
                            @endphp
                            <div
                                class="p-4 flex items-start gap-4 border-b border-black/10 hover:bg-white/5 transition-all group/lecture {{ $currentLecture->id == $lecture->id ? 'bg-brand/5 border-l-4 border-l-brand' : '' }} {{ !$isUnlocked ? 'opacity-50' : '' }}">

                                <!-- Status Icon -->
                                <div class="mt-1">
                                    @if ($isCompleted)
                                        <i class="fa-solid fa-circle-check text-green-400"></i>
                                    @elseif(!$isUnlocked)
                                        <i class="fa-solid fa-lock text-red-400"></i>
                                    @else
                                        <i class="fa-regular fa-circle text-slate-500"></i>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0 flex flex-col gap-2">
                                    @if ($isUnlocked)
                                        <a href="{{ route('course.lecture.watch', [$course->course_name_slug, $lecture->id]) }}"
                                            class="block lecture-item-link" data-lecture-id="{{ $lecture->id }}">
                                            <h4
                                                class="text-sm font-bold {{ $currentLecture->id == $lecture->id ? 'text-brand' : 'text-slate-200' }} group-hover/lecture:text-cyber-cyan transition-colors truncate">
                                                @if ($lecture->quiz)
                                                    <i class="fa-solid fa-file-circle-question mr-1 text-brand"></i>
                                                @endif
                                                {{ $loop->iteration }}. {{ $lecture->lecture_title }}
                                            </h4>
                                        </a>
                                    @else
                                        <div class="cursor-not-allowed">
                                            <h4 class="text-sm font-bold text-slate-500 truncate">
                                                @if ($lecture->quiz)
                                                    <i class="fa-solid fa-file-circle-question mr-1"></i>
                                                @endif
                                                {{ $loop->iteration }}. {{ $lecture->lecture_title }}
                                            </h4>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <div
                                            class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase">
                                            @if ($lecture->quiz)
                                                <i class="fa-solid fa-brain text-xs"></i>
                                                <span>{{ $lecture->quiz->questions->count() }} câu hỏi</span>
                                            @else
                                                <i class="fa-solid fa-play-circle text-xs"></i>
                                                <span>{{ $lecture->video_duration ?: '0' }} phút</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </details>
            @endforeach

            <div class="p-6">
                <a href="{{ route('chi-tiet', $course->course_name_slug) }}"
                    class="block w-full bg-cyber-dark text-brand font-bold uppercase py-4 border-2 border-black pixel-shadow-sm hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all text-center">
                    Return to Map
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Stats Panel removed for cleaner sidebar -->
</aside>
