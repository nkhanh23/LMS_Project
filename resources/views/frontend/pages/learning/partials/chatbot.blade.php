<div id="tab-chatbot" class="tab-content py-8 hidden">
    <div class="max-w-4xl space-y-6">
        <!-- Chatbot Container -->
        <div class="bg-cyber-surface border-4 border-black p-6 pixel-shadow relative">
            <div class="absolute -top-4 left-6 bg-brand text-black px-3 py-1 font-bold text-xs uppercase tracking-widest border-2 border-black flex items-center gap-2">
                <i class="fa-solid fa-robot"></i> AI ASSISTANT v1.0
            </div>

            <!-- Messages Area -->
            <div id="chat-messages" class="bg-cyber-dark border-2 border-slate-800 p-4 mb-6 overflow-y-auto h-[450px] font-sans flex flex-col gap-4 scrollbar-cyber">
                <div class="flex items-center justify-center h-full">
                    <div class="text-slate-500 uppercase font-black tracking-widest text-sm animate-pulse">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i> Đang tải lịch sử chat...
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-brand to-cyber-cyan rounded opacity-20 group-focus-within:opacity-40 transition duration-500 blur"></div>
                
                <div class="relative">
                    <textarea id="chat-message" 
                        class="w-full bg-cyber-dark border-2 border-slate-700 p-4 text-white focus:border-brand focus:ring-0 transition-colors font-sans min-h-[100px] pr-32 mb-0"
                        placeholder="Bạn cần giải thích thêm về nội dung bài học này?... (Nhấn Enter để gửi)"></textarea>

                    <div class="absolute bottom-4 right-4 flex items-center gap-3">
                        <button id="send-chat-btn" 
                            class="bg-brand text-black font-black uppercase px-6 py-2 border-2 border-black hover:bg-white transition-all transform active:scale-95 flex items-center gap-2 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none">
                            <span>GỬI</span>
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hint -->
            <div class="mt-4 flex items-center gap-2 text-[10px] text-slate-500 uppercase font-bold tracking-widest">
                <i class="fa-solid fa-circle-info text-cyber-cyan"></i>
                AI có thể nhầm lẫn. Hãy xem kỹ kiến thức từ bài giảng.
            </div>
        </div>
    </div>
</div>

<style>
    .scrollbar-cyber::-webkit-scrollbar {
        width: 6px;
    }
    .scrollbar-cyber::-webkit-scrollbar-track {
        background: #0f172a;
    }
    .scrollbar-cyber::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 3px;
    }
    .scrollbar-cyber::-webkit-scrollbar-thumb:hover {
        background: #facc15;
    }

    .chat-bubble-user {
        position: relative;
        background: rgba(250, 204, 21, 0.1);
        border: 2px solid rgba(250, 204, 21, 0.3);
        border-right-width: 6px;
        border-right-color: #facc15;
    }

    .chat-bubble-assistant {
        position: relative;
        background: rgba(34, 211, 238, 0.05);
        border: 2px solid rgba(34, 211, 238, 0.2);
        border-left-width: 6px;
        border-left-color: #22d3ee;
    }

    .message-content pre {
        background: #000;
        padding: 1rem;
        border: 1px solid #334155;
        margin: 0.5rem 0;
        overflow-x: auto;
    }
</style>

<script>
    const askUrl = @json(route('chatbot.ask'));
    const historyUrl = @json(route('chatbot.history'));
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatBox = document.getElementById('chat-messages');
    const sendButton = document.getElementById('send-chat-btn');
    const input = document.getElementById('chat-message');

    // Dynamic IDs
    const getCourseId = () => {{ $course->id }};
    const getLectureId = () => {
        return window.currentLectureId || {{ $currentLecture->id ?? $lecture->id }};
    };

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    function renderCitations(citations = []) {
        if (!citations || !citations.length) return '';

        return `
            <div class="mt-4 space-y-2 border-t border-slate-700/50 pt-3">
                <div class="text-[10px] uppercase tracking-widest text-brand font-black flex items-center gap-2">
                    <i class="fa-solid fa-book-open"></i> Nguồn tham khảo
                </div>
                <div class="grid gap-2">
                    ${citations.map(c => `
                        <div class="rounded-sm bg-black/40 border border-slate-800 p-2 text-[11px] text-slate-300 hover:border-brand/30 transition-colors group/cite relative">
                            <div class="font-bold text-cyber-cyan mb-0.5">${c.document_title ?? 'Tài liệu'} <span class="text-[9px] opacity-40 ml-1 font-mono">#${c.chunk_id}</span></div>
                            <div class="opacity-80 line-clamp-2 italic leading-relaxed">"${c.snippet ?? ''}"</div>
                            
                            <!-- Full snippet on hover -->
                            <div class="hidden group-hover/cite:block absolute left-0 right-0 bottom-full mb-2 p-3 bg-cyber-dark border border-slate-700 shadow-2xl z-20 pointer-events-none text-xs not-italic">
                                ${c.snippet}
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    function appendMessage(role, content, citations = []) {
        const isUser = role === 'user';
        const alignClass = isUser ? 'ml-auto' : 'mr-auto';
        const bubbleClass = isUser ? 'chat-bubble-user' : 'chat-bubble-assistant';
        const icon = isUser ? 'fa-user' : 'fa-robot';
        const color = isUser ? 'text-brand' : 'text-cyber-cyan';
        const sender = isUser ? 'BẠN' : 'AI TUTOR';

        const messageHtml = `
            <div class="flex flex-col ${isUser ? 'items-end' : 'items-start'} max-w-[90%] ${alignClass}">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-black uppercase tracking-tighter ${color}">${sender}</span>
                    <i class="fa-solid ${icon} text-[10px] ${color}"></i>
                </div>
                <div class="p-4 rounded-sm text-sm leading-relaxed text-slate-200 ${bubbleClass} font-sans relative">
                    <div class="message-content">
                        ${content.replace(/\n/g, '<br>')}
                    </div>
                    ${role === 'assistant' ? renderCitations(citations) : ''}
                </div>
            </div>
        `;

        // Remove loading spinner if it exists
        if (chatBox.querySelector('.animate-pulse')) {
            chatBox.innerHTML = '';
        }

        chatBox.insertAdjacentHTML('beforeend', messageHtml);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    async function loadHistory() {
        chatBox.innerHTML = `
            <div class="flex items-center justify-center h-full">
                <div class="text-slate-500 uppercase font-black tracking-widest text-sm animate-pulse">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i> Đang đọc lịch sử...
                </div>
            </div>
        `;

        try {
            const params = new URLSearchParams({
                course_id: getCourseId(),
                lecture_id: getLectureId()
            });

            const response = await fetch(historyUrl + '?' + params.toString(), {
                headers: { 'Accept': 'application/json' }
            });

            const data = await response.json();
            chatBox.innerHTML = '';

            if (!data.success) {
                chatBox.innerHTML = `<div class="text-red-400 p-4 border border-red-900 bg-red-950/20 uppercase font-black text-xs text-center">Không tải được lịch sử chat</div>`;
                return;
            }

            if (!data.data.messages || data.data.messages.length === 0) {
                chatBox.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full opacity-30">
                        <i class="fa-solid fa-ghost text-4xl mb-4"></i>
                        <div class="text-slate-500 uppercase font-black tracking-widest text-sm">Chưa có dữ liệu hội thoại</div>
                    </div>
                `;
                return;
            }

            data.data.messages.forEach(message => {
                appendMessage(message.role, message.content, message.citations || []);
            });
        } catch (error) {
            console.error(error);
            chatBox.innerHTML = `<div class="text-red-400 p-4 text-center uppercase font-black text-xs">Không thể kết nối máy chủ AI</div>`;
        }
    }

    async function sendMessage() {
        const message = input.value.trim();
        if (!message || sendButton.disabled) return;

        // Display user message
        appendMessage('user', message);
        input.value = '';
        input.style.height = 'auto';
        
        // Show temporary typing indicator
        const typingId = 'typing-' + Date.now();
        chatBox.insertAdjacentHTML('beforeend', `
            <div id="${typingId}" class="flex items-center gap-2 text-cyber-cyan opacity-50 animate-pulse text-[10px] font-black uppercase tracking-widest">
                <i class="fa-solid fa-robot"></i> AI đang suy nghĩ...
            </div>
        `);
        chatBox.scrollTop = chatBox.scrollHeight;

        // Disable input
        sendButton.disabled = true;
        sendButton.classList.add('opacity-50');

        try {
            const response = await fetch(askUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: message,
                    course_id: getCourseId(),
                    lecture_id: getLectureId()
                })
            });

            const data = await response.json();
            
            // Remove typing indicator
            document.getElementById(typingId)?.remove();

            if (!data.success) {
                appendMessage('assistant', `<span class="text-red-400">🚨 LỖI: ${data.message || 'Hệ thống AI tạm thời gián đoạn.'}</span>`);
                return;
            }

            appendMessage('assistant', data.data.answer, data.data.citations || []);
        } catch (error) {
            document.getElementById(typingId)?.remove();
            appendMessage('assistant', '<span class="text-red-400">🚨 LỖI: Không thể kết nối đến máy chủ AI.</span>');
        } finally {
            sendButton.disabled = false;
            sendButton.classList.remove('opacity-50');
            input.focus();
        }
    }

    sendButton.addEventListener('click', sendMessage);

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Auto-resize textarea
    input.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Handle tab focus
    window.addEventListener('chatbot-tab-focused', loadHistory);
    
    // Initial load
    loadHistory();
});
</script>
