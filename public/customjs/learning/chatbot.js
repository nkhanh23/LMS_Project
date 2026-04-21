(function () {
    const root = document.getElementById('lesson-chatbot');
    if (!root) return;

    const askUrl = root.dataset.askUrl;
    const historyUrl = root.dataset.historyUrl;
    const csrfToken = root.dataset.csrf;
    let courseId = root.dataset.courseId;
    let lectureId = root.dataset.lectureId;

    const toggleBtn = document.getElementById('lessonChatbotToggle');
    const closeBtn = document.getElementById('lessonChatbotClose');
    const panel = document.getElementById('lessonChatbotPanel');
    const messagesEl = document.getElementById('lessonChatbotMessages');
    const form = document.getElementById('lessonChatbotForm');
    const input = document.getElementById('lessonChatbotInput');
    const submitBtn = document.getElementById('lessonChatbotSubmit');
    const statusEl = document.getElementById('lessonChatbotStatus');
    const lectureTitleEl = document.getElementById('lessonChatbotLectureTitle');

    let historyLoaded = false;

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function setStatus(message = '', type = 'info') {
        if (!message) {
            statusEl.classList.add('hidden');
            statusEl.textContent = '';
            return;
        }

        statusEl.classList.remove('hidden');
        statusEl.textContent = message;
        statusEl.className = 'px-4 py-2 text-xs border-b bg-black/30';

        if (type === 'error') {
            statusEl.classList.add('text-red-300', 'border-red-900/60');
        } else if (type === 'success') {
            statusEl.classList.add('text-emerald-300', 'border-emerald-900/60');
        } else {
            statusEl.classList.add('text-slate-300', 'border-slate-700');
        }
    }

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function citationHtml(citations) {
        if (!Array.isArray(citations) || !citations.length) return '';

        const items = citations.map((citation) => {
            const title = escapeHtml(citation.document_title || 'Tài liệu');
            const snippet = escapeHtml(citation.snippet || '');
            const chunkId = citation.chunk_id ? `#${citation.chunk_id}` : '-';

            return `
                <div class="mt-2 rounded border border-slate-700 bg-black/30 p-3">
                    <div class="text-[11px] uppercase tracking-widest text-brand font-bold mb-1">
                        Nguồn • chunk ${chunkId}
                    </div>
                    <div class="text-xs text-white font-semibold mb-1">${title}</div>
                    <div class="text-xs text-slate-300 leading-relaxed">${snippet}</div>
                </div>
            `;
        }).join('');

        return `<div class="mt-2">${items}</div>`;
    }

    function messageHtml(role, content, citations = []) {
        const isUser = role === 'user';

        return `
            <div class="flex ${isUser ? 'justify-end' : 'justify-start'}">
                <div class="${isUser ? 'bg-brand text-black' : 'bg-[#2A2A3C] text-white'} max-w-[90%] border-2 border-black px-4 py-3">
                    <div class="text-[10px] uppercase font-black tracking-widest mb-2 ${isUser ? 'text-black/70' : 'text-brand'}">
                        ${isUser ? 'Bạn' : 'AI Tutor'}
                    </div>
                    <div class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(content)}</div>
                    ${!isUser ? citationHtml(citations) : ''}
                </div>
            </div>
        `;
    }

    function appendMessage(role, content, citations = []) {
        messagesEl.insertAdjacentHTML('beforeend', messageHtml(role, content, citations));
        scrollToBottom();
    }

    function clearWelcomeBoxIfNeeded() {
        const boxes = messagesEl.querySelectorAll('div.rounded-md.border-dashed');
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

            const messages = payload.data?.messages || [];
            if (!messages.length) {
                messagesEl.innerHTML = `
                    <div class="rounded-md border border-dashed border-slate-600 p-4 text-sm text-slate-300 bg-black/20">
                        Chưa có hội thoại nào cho bài học này. Bạn có thể bắt đầu bằng cách hỏi tóm tắt nội dung bài.
                    </div>
                `;
            } else {
                messages.forEach((message) => {
                    appendMessage(message.role, message.content, message.citations || []);
                });
            }

            historyLoaded = true;
            setStatus('');
        } catch (error) {
            setStatus(error.message || 'Không thể tải lịch sử chat.', 'error');
        }
    }

    async function askQuestion(message) {
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('course_id', courseId);
        formData.append('lecture_id', lectureId);
        formData.append('message', message);

        submitBtn.disabled = true;
        input.disabled = true;
        setStatus('AI đang suy nghĩ...');

        clearWelcomeBoxIfNeeded();
        appendMessage('user', message);

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

            appendMessage('assistant', answer, citations);
            setStatus('AI đã trả lời xong.', 'success');
        } catch (error) {
            appendMessage(
                'assistant',
                'Xin lỗi, mình chưa thể trả lời lúc này. Bạn thử lại sau ít phút.'
            );
            setStatus(error.message || 'Có lỗi xảy ra khi gửi câu hỏi.', 'error');
        } finally {
            submitBtn.disabled = false;
            input.disabled = false;
            input.value = '';
            input.focus();
        }
    }

    function openPanel() {
        panel.classList.remove('hidden');
        loadHistory();
    }

    function closePanel() {
        panel.classList.add('hidden');
        setStatus('');
    }

    toggleBtn?.addEventListener('click', () => {
        if (panel.classList.contains('hidden')) {
            openPanel();
        } else {
            closePanel();
        }
    });

    closeBtn?.addEventListener('click', closePanel);

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = (input.value || '').trim();
        if (message.length < 2) {
            setStatus('Câu hỏi quá ngắn.', 'error');
            input.focus();
            return;
        }

        await askQuestion(message);
    });

    // Hỗ trợ nếu sau này lesson đổi qua AJAX
    window.StackLearnLessonChatbot = {
        reload(context = {}) {
            if (context.courseId) {
                courseId = String(context.courseId);
                root.dataset.courseId = courseId;
            }

            if (context.lectureId) {
                lectureId = String(context.lectureId);
                root.dataset.lectureId = lectureId;
            }

            if (context.lectureTitle) {
                lectureTitleEl.textContent = context.lectureTitle;
            }

            historyLoaded = false;
            messagesEl.innerHTML = `
                <div class="rounded-md border border-dashed border-slate-600 p-4 text-sm text-slate-300 bg-black/20">
                    Đã chuyển sang bài học mới. Mở lại panel để tải lịch sử chat của bài này.
                </div>
            `;
        }
    };
})();