(function () {
    const root = document.getElementById('lesson-chatbot');
    if (!root) return;

    const panel = document.getElementById('chatbot-panel');
    if (!panel) return;

    const askUrl = panel.dataset.askUrl;
    const historyUrl = panel.dataset.historyUrl;
    const csrfToken = panel.dataset.csrf;
    let courseId = panel.dataset.courseId;
    let lectureId = panel.dataset.lectureId;

    const toggleBtn = document.getElementById('lessonChatbotToggle');
    const closeBtn = document.getElementById('lessonChatbotClose');
    const messagesEl = document.getElementById('chatbot-messages');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const submitBtn = form?.querySelector('button[type="submit"]');
    const statusEl = document.getElementById('chatbot-response-status');
    const sessionMetaEl = document.getElementById('chatbot-session-meta');

    let historyLoaded = false;

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function setStatus(message = '', type = 'info') {
        if (!statusEl) return;
        if (!message) {
            statusEl.classList.add('hidden');
            statusEl.textContent = '';
            return;
        }

        statusEl.classList.remove('hidden');
        statusEl.textContent = message;
        statusEl.className = 'px-4 py-2 text-[11px] border-t bg-black/20';

        if (type === 'error') {
            statusEl.classList.add('text-red-400', 'border-red-900/60');
        } else if (type === 'success') {
            statusEl.classList.add('text-emerald-400', 'border-emerald-900/60');
        } else {
            statusEl.classList.add('text-slate-400', 'border-slate-700');
        }
    }

    function scrollToBottom() {
        if (messagesEl) {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }
    }

    function renderSessionMeta(data) {
        if (!sessionMetaEl) return;
        const parts = [];
        if (data.session_id) parts.push(`Session #${data.session_id}`);
        if (data.last_activity_at) parts.push(`Hoạt động cuối: ${data.last_activity_at}`);
        if (data.session_status) parts.push(`Trạng thái: ${data.session_status}`);
        
        sessionMetaEl.innerHTML = parts.map(p => `<span class="bg-black/30 text-slate-300 px-2 py-1 border border-slate-700 text-[10px] rounded">${p}</span>`).join('');
    }

    function renderStatusMeta(message) {
        return '';
    }

    function citationHtml(citations) {
        return '';
    }

    function messageHtml(message) {
        const isUser = message.role === 'user';
        const citations = message.citations || [];
        const content = escapeHtml(message.content || message.answer || '');

        return `
            <div class="flex ${isUser ? 'justify-end' : 'justify-start'}">
                <div class="${isUser ? 'bg-brand text-black' : 'bg-[#2A2A3C] text-white'} max-w-[90%] border-2 border-black px-4 py-3">
                    <div class="text-[10px] uppercase font-black tracking-widest mb-2 ${isUser ? 'text-black/70' : 'text-brand'}">
                        ${isUser ? 'Bạn' : 'AI Tutor'}
                    </div>
                    <div class="text-sm leading-relaxed whitespace-pre-wrap">${content}</div>
                    ${renderStatusMeta(message)}
                    ${!isUser ? citationHtml(citations) : ''}
                </div>
            </div>
        `;
    }

    function appendMessage(message) {
        if (!messagesEl) return;
        messagesEl.insertAdjacentHTML('beforeend', messageHtml(message));
        scrollToBottom();
    }

    function clearWelcomeBoxIfNeeded() {
        if (!messagesEl) return;
        const boxes = messagesEl.querySelectorAll('.text-muted.italic');
        boxes.forEach((box) => box.remove());
    }

    async function loadHistory(force = false) {
        if (historyLoaded && !force) return;

        setStatus('Đang tải lịch sử chat...');

        const url = new URL(historyUrl, window.location.origin);
        url.searchParams.set('course_id', courseId);
        url.searchParams.set('lecture_id', lectureId);

        try {
            const response = await fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Không thể tải lịch sử chat.');
            }

            messagesEl.innerHTML = '';
            
            const data = payload.data || {};
            renderSessionMeta(data);

            const messages = data.messages || [];
            if (!messages.length) {
                messagesEl.innerHTML = `
                    <div class="text-slate-400 text-sm italic">Chưa có lịch sử chat cho bài học này.</div>
                `;
            } else {
                messages.forEach((message) => {
                    appendMessage(message);
                });
            }

            historyLoaded = true;
            setStatus('');
        } catch (error) {
            setStatus(error.message || 'Không thể tải lịch sử chat.', 'error');
        }
    }

    async function askQuestion(messageContent) {
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('course_id', courseId);
        formData.append('lecture_id', lectureId);
        formData.append('message', messageContent);

        if (submitBtn) submitBtn.disabled = true;
        if (input) input.disabled = true;
        setStatus('AI đang suy nghĩ...');

        clearWelcomeBoxIfNeeded();
        appendMessage({ role: 'user', content: messageContent });

        try {
            const response = await fetch(askUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Không thể gửi câu hỏi.');
            }

            const data = payload.data || {};
            const answer =
                data.answer ||
                data.response ||
                data.content ||
                data.message ||
                'AI chưa trả về nội dung trả lời.';
            const citations = data.citations || [];

            appendMessage({
                role: 'assistant',
                content: answer,
                citations: citations,
                answer_status: data.answer_status,
                evidence_strength: data.evidence_strength,
                source_scope: data.source_scope
            });
            setStatus('AI đã trả lời xong.', 'success');
        } catch (error) {
            appendMessage({
                role: 'assistant',
                content: 'Xin lỗi, mình chưa thể trả lời lúc này. Bạn thử lại sau ít phút.'
            });
            setStatus(error.message || 'Có lỗi xảy ra khi gửi câu hỏi.', 'error');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
            if (input) {
                input.disabled = false;
                input.value = '';
                input.focus();
            }
        }
    }

    function openPanel() {
        if (panel) panel.classList.remove('hidden');
        loadHistory();
    }

    function closePanel() {
        if (panel) panel.classList.add('hidden');
        setStatus('');
    }

    toggleBtn?.addEventListener('click', () => {
        if (panel && panel.classList.contains('hidden')) {
            openPanel();
        } else {
            closePanel();
        }
    });

    closeBtn?.addEventListener('click', closePanel);

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = (input?.value || '').trim();
        if (message.length < 2) {
            setStatus('Câu hỏi quá ngắn.', 'error');
            if (input) input.focus();
            return;
        }

        await askQuestion(message);
    });

    // Auto load history if panel is open by default
    document.addEventListener('DOMContentLoaded', function () {
        if (panel && !panel.classList.contains('hidden')) {
            loadHistory();
        }
    });

    // Hỗ trợ nếu sau này lesson đổi qua AJAX
    window.StackLearnLessonChatbot = {
        reload(context = {}) {
            if (!panel) return;
            if (context.courseId) {
                courseId = String(context.courseId);
                panel.dataset.courseId = courseId;
            }

            if (context.lectureId) {
                lectureId = String(context.lectureId);
                panel.dataset.lectureId = lectureId;
            }

            historyLoaded = false;
            if (messagesEl) {
                messagesEl.innerHTML = `
                    <div class="text-slate-400 text-sm italic">
                        Đã chuyển sang bài học mới. Mở lại panel để tải lịch sử chat.
                    </div>
                `;
            }
        }
    };
})();