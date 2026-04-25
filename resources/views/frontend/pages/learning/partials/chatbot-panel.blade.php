<div id="lesson-chatbot" class="fixed bottom-5 right-5 z-[999]">
    {{-- Floating Toggle Button --}}
    <button id="lessonChatbotToggle"
        type="button"
        class="flex items-center gap-2 bg-brand text-black font-black uppercase px-4 py-3 border-4 border-black pixel-shadow hover:-translate-y-0.5 transition-all">
        <i class="fa-solid fa-robot"></i>
        <span>AI Tutor</span>
    </button>

    {{-- Main Chatbot Panel --}}
    <div id="chatbot-panel"
        class="hidden mt-3 w-[420px] max-w-[calc(100vw-2rem)] h-[75vh] max-h-[750px] bg-[#1E1E2E] border-4 border-black pixel-shadow flex flex-col overflow-hidden"
        data-course-id="{{ $course->id }}"
        data-lecture-id="{{ $currentLecture->id }}"
        data-history-url="{{ route('chatbot.history') }}"
        data-ask-url="{{ route('chatbot.ask') }}"
        data-csrf="{{ csrf_token() }}">

        {{-- Header / Context --}}
        <div class="bg-[#2A2A3C] border-b-4 border-black px-4 py-3 flex items-start justify-between gap-3">
            <div>
                <h3 class="text-brand font-black uppercase text-sm tracking-widest mb-1">
                    Gia sư ảo
                </h3>
                <p class="text-xs text-slate-300">
                    Đang hỏi trong bài: <strong class="text-white">{{ $currentLecture->lecture_title ?? 'Bài học hiện tại' }}</strong>
                </p>
                
                {{-- Metadata Area --}}
                <div id="chatbot-session-meta" class="mt-2 flex flex-wrap gap-2">
                    {{-- Dynamically populated: Session status, Evidence strength, etc. --}}
                </div>
            </div>

            <button id="lessonChatbotClose"
                type="button"
                class="text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Message History --}}
        <div id="chatbot-messages"
            class="flex-1 overflow-y-auto px-4 py-4 space-y-4 custom-scrollbar bg-[#1E1E2E]">
            <div class="text-muted text-sm italic">Đang tải lịch sử chat...</div>
        </div>

        {{-- Status / Citation Area (Optional placeholder if needed by logic) --}}
        <div id="chatbot-response-status" class="hidden px-4 py-2 text-[11px] border-t border-slate-700 bg-black/20 text-slate-400">
        </div>

        {{-- Input Form --}}
        <form id="chatbot-form" class="border-t-4 border-black bg-[#2A2A3C] p-4">
            @csrf
            <div class="space-y-3">
                <textarea id="chatbot-input"
                    rows="3"
                    maxlength="2000"
                    placeholder="Nhập câu hỏi của bạn..."
                    class="w-full resize-none bg-black text-white border-2 border-slate-700 focus:border-brand outline-none px-3 py-3 text-sm"></textarea>

                <div class="flex items-center justify-between gap-3">
                    <p class="text-[10px] text-slate-500 leading-tight uppercase font-bold">
                        AI ưu tiên trả lời dựa trên tài liệu bài học.
                    </p>

                    <button type="submit"
                        class="shrink-0 bg-brand text-black font-black uppercase px-6 py-2 border-2 border-black hover:bg-white transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                        Gửi câu hỏi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>