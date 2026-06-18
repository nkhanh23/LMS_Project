@php
    $assistantHasLessonMode = isset($course, $currentLecture);
@endphp

<div id="stacklearn-assistant"
    class="fixed bottom-5 right-5 z-[999]"
    data-default-mode="{{ $assistantHasLessonMode ? 'lesson' : 'website' }}"
    data-has-lesson-mode="{{ $assistantHasLessonMode ? '1' : '0' }}">
    <button id="assistantToggle"
        type="button"
        class="flex items-center gap-2 bg-brand text-black font-black uppercase px-4 py-3 border-4 border-black pixel-shadow hover:-translate-y-0.5 transition-all">
        <i class="fa-solid fa-robot"></i>
        <span>AI Tutor</span>
    </button>

    <div id="assistantPanel"
        class="hidden mt-3 w-[420px] max-w-[calc(100vw-2rem)] h-[75vh] max-h-[750px] bg-[#1E1E2E] border-4 border-black pixel-shadow flex flex-col overflow-hidden"
        data-csrf="{{ csrf_token() }}"
        data-website-history-url="{{ route('website-assistant.history') }}"
        data-website-ask-url="{{ route('website-assistant.ask') }}"
        data-website-new-session-url="{{ route('website-assistant.new-session') }}"
        @if ($assistantHasLessonMode)
            data-lesson-course-id="{{ $course->id }}"
            data-lesson-course-title="{{ $course->course_name ?? $course->course_title }}"
            data-lesson-lecture-id="{{ $currentLecture->id }}"
            data-lesson-lecture-title="{{ $currentLecture->lecture_title ?? $currentLecture->title }}"
            data-lesson-history-url="{{ route('chatbot.history') }}"
            data-lesson-ask-url="{{ route('chatbot.ask') }}"
            data-lesson-new-session-url="{{ route('chatbot.new-session') }}"
        @endif>

        <div class="bg-[#2A2A3C] border-b-4 border-black px-4 py-3 flex items-start justify-between gap-3">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-3">
                    <h3 id="assistantTitle" class="text-brand font-black uppercase text-sm tracking-widest">
                        Tro ly he thong
                    </h3>

                    @if ($assistantHasLessonMode)
                        <div class="inline-flex border-2 border-black bg-black/30">
                            <button type="button" data-mode="lesson"
                                class="assistant-mode-toggle px-3 py-1 text-[10px] font-black uppercase text-white bg-brand text-black">
                                Bai hoc
                            </button>
                            <button type="button" data-mode="website"
                                class="assistant-mode-toggle px-3 py-1 text-[10px] font-black uppercase text-white hover:bg-white/10 transition-colors">
                                Trang
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button id="assistantNewSession"
                    type="button"
                    class="text-[10px] uppercase font-black text-brand border border-brand px-2 py-1 hover:bg-brand hover:text-black transition-colors">
                    Phien moi
                </button>
                <button id="assistantClose"
                    type="button"
                    class="text-slate-400 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <div id="assistantMessages"
            class="flex-1 overflow-y-auto px-4 py-4 space-y-4 custom-scrollbar bg-[#1E1E2E]">
            <div class="text-slate-400 text-sm italic">Dang tai lich su chat...</div>
        </div>

        <div class="border-t border-slate-700 bg-black/20">
            <button id="assistantQuickActionsToggle"
                type="button"
                class="w-full px-4 py-2 flex items-center justify-between text-[10px] uppercase font-black text-slate-300 hover:text-white transition-colors">
                <span>Goi y cau hoi</span>
                <i id="assistantQuickActionsIcon" class="fa-solid fa-chevron-down text-[10px]"></i>
            </button>

            <div id="assistantQuickActions" class="hidden px-4 pb-3 flex flex-wrap gap-2"></div>
        </div>

        <div id="assistantStatus" class="hidden px-4 py-2 text-[11px] border-t border-slate-700 bg-black/20 text-slate-400"></div>

        <form id="assistantForm" class="border-t-4 border-black bg-[#2A2A3C] p-4">
            @csrf
            <div class="space-y-3">
                <textarea id="assistantInput"
                    rows="3"
                    maxlength="2000"
                    placeholder="Hoi ve tien do hoc, quiz, chung chi hoac cach dung he thong..."
                    class="w-full resize-none bg-black text-white border-2 border-slate-700 focus:border-brand outline-none px-3 py-3 text-sm"></textarea>

                <div class="flex items-center justify-between gap-3">
                    <p id="assistantHint" class="text-[10px] text-slate-500 leading-tight uppercase font-bold"></p>

                    <button type="submit"
                        class="shrink-0 bg-brand text-black font-black uppercase px-6 py-2 border-2 border-black hover:bg-white transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                        Gui cau hoi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
