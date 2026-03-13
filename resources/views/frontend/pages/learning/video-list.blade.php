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
                                <span>0 / {{ $section->lecture->count() }}</span>
                                <span class="text-slate-700">|</span>
                                <span>{{ $section->lecture->sum('video_duration') ?: '0' }} phút</span>
                            </div>
                        </div>
                        <i
                            class="fa-solid fa-chevron-down {{ $currentLecture->section_id == $section->id ? 'text-brand' : 'text-slate-500' }} transition-transform group-open:rotate-180"></i>
                    </summary>

                    <div class="bg-black/20">
                        @foreach ($section->lecture as $lecture)
                            <div
                                class="p-4 flex items-start gap-4 border-b border-black/10 hover:bg-white/5 transition-all group/lecture {{ $currentLecture->id == $lecture->id ? 'bg-brand/5 border-l-4 border-l-brand' : '' }}">
                                <!-- Custom Checkbox -->
                                <label class="relative flex items-center cursor-pointer mt-1">
                                    <input type="checkbox" class="sr-only peer">
                                    <div
                                        class="w-6 h-6 border-2 border-slate-700 bg-cyber-dark group-hover/lecture:border-cyber-cyan peer-checked:bg-brand peer-checked:border-black transition-all flex items-center justify-center">
                                        <i
                                            class="fa-solid fa-check text-[10px] text-black opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                </label>

                                <div class="flex-1 min-w-0 flex flex-col gap-2">
                                    <a href="{{ route('course.lecture.watch', [$course->course_name_slug, $lecture->id]) }}"
                                        class="block">
                                        <h4
                                            class="text-sm font-bold {{ $currentLecture->id == $lecture->id ? 'text-brand' : 'text-slate-200' }} group-hover/lecture:text-cyber-cyan transition-colors truncate">
                                            {{ $loop->iteration }}. {{ $lecture->lecture_title }}
                                        </h4>
                                    </a>

                                    <div class="flex items-center justify-between">
                                        <div
                                            class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase">
                                            <i class="fa-solid fa-play-circle text-xs"></i>
                                            <span>{{ $lecture->video_duration ?: '0' }} phút</span>
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
