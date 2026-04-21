<div id="lesson-chatbot"
    class="fixed bottom-5 right-5 z-[999]"
    data-course-id="{{ $course->id }}"
    data-lecture-id="{{ $currentLecture->id }}"
    data-lecture-title="{{ e($currentLecture->lecture_title ?? 'Bài học hiện tại') }}"
    data-ask-url="{{ route('chatbot.ask') }}"
    data-history-url="{{ route('chatbot.history') }}"
    data-csrf="{{ csrf_token() }}">

    <button id="lessonChatbotToggle"
        type="button"
        class="flex items-center gap-2 bg-brand text-black font-black uppercase px-4 py-3 border-4 border-black pixel-shadow hover:-translate-y-0.5 transition-all">
        <i class="fa-solid fa-robot"></i>
        <span>AI Tutor</span>
    </button>

    <div id="lessonChatbotPanel"
        class="hidden mt-3 w-[380px] max-w-[calc(100vw-2rem)] h-[72vh] max-h-[720px] bg-[#1E1E2E] border-4 border-black pixel-shadow flex flex-col overflow-hidden">

        <div class="bg-[#2A2A3C] border-b-4 border-black px-4 py-3 flex items-start justify-between gap-3">
            <div>
                <h3 class="text-brand font-black uppercase text-sm tracking-widest mb-1">
                    Gia sư ảo
                </h3>
                <p class="text-xs text-slate-300">
                    Đang hỏi trong:
                    <span id="lessonChatbotLectureTitle" class="text-white font-semibold">
                        {{ $currentLecture->lecture_title ?? 'Bài học hiện tại' }}
                    </span>
                </p>
                <div class="mt-2 inline-flex items-center gap-2 text-[10px] uppercase font-bold tracking-wider">
                    <span class="bg-black text-brand px-2 py-1 border border-brand">
                        Phạm vi: lesson hiện tại
                    </span>
                </div>
            </div>

            <button id="lessonChatbotClose"
                type="button"
                class="text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div id="lessonChatbotStatus"
            class="hidden px-4 py-2 text-xs border-b border-slate-700 bg-black/30 text-slate-300">
        </div>

        <div id="lessonChatbotMessages"
            class="flex-1 overflow-y-auto px-4 py-4 space-y-4 custom-scrollbar bg-[#1E1E2E]">
            <div class="rounded-md border border-dashed border-slate-600 p-4 text-sm text-slate-300 bg-black/20">
                Hãy hỏi về nội dung bài học hiện tại. Trả lời sẽ ưu tiên theo lesson đang mở.
            </div>
        </div>

        <form id="lessonChatbotForm" class="border-t-4 border-black bg-[#2A2A3C] p-4">
            <div class="space-y-3">
                <textarea id="lessonChatbotInput"
                    rows="3"
                    maxlength="2000"
                    placeholder="Ví dụ: Tóm tắt nội dung chính của bài này giúp tôi"
                    class="w-full resize-none bg-black text-white border-2 border-slate-700 focus:border-brand outline-none px-3 py-3 text-sm"></textarea>

                <div class="flex items-center justify-between gap-3">
                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        AI chỉ nên trả lời trong phạm vi bài học/khóa học hiện tại.
                    </p>

                    <button id="lessonChatbotSubmit"
                        type="submit"
                        class="shrink-0 bg-brand text-black font-black uppercase px-4 py-2 border-2 border-black hover:bg-white transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                        Gửi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>