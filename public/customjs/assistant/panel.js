(function () {
    const root = document.getElementById('stacklearn-assistant');
    if (!root) return;

    const panel = document.getElementById('assistantPanel');
    const toggleBtn = document.getElementById('assistantToggle');
    const closeBtn = document.getElementById('assistantClose');
    const newSessionBtn = document.getElementById('assistantNewSession');
    const messagesEl = document.getElementById('assistantMessages');
    const quickActionsEl = document.getElementById('assistantQuickActions');
    const quickActionsToggleEl = document.getElementById('assistantQuickActionsToggle');
    const quickActionsIconEl = document.getElementById('assistantQuickActionsIcon');
    const form = document.getElementById('assistantForm');
    const input = document.getElementById('assistantInput');
    const submitBtn = form?.querySelector('button[type="submit"]');
    const statusEl = document.getElementById('assistantStatus');
    const titleEl = document.getElementById('assistantTitle');
    const hintEl = document.getElementById('assistantHint');
    const modeButtons = root.querySelectorAll('.assistant-mode-toggle');
    const searchParams = new URLSearchParams(window.location.search);

    const csrfToken = panel.dataset.csrf;
    const hasLessonMode = root.dataset.hasLessonMode === '1';

    const state = {
        mode: root.dataset.defaultMode || 'website',
        quickActionsOpen: false,
        website: {
            historyUrl: panel.dataset.websiteHistoryUrl,
            askUrl: panel.dataset.websiteAskUrl,
            newSessionUrl: panel.dataset.websiteNewSessionUrl,
            historyLoaded: false,
        },
        lesson: {
            courseId: panel.dataset.lessonCourseId || '',
            courseTitle: panel.dataset.lessonCourseTitle || '',
            lectureId: panel.dataset.lessonLectureId || '',
            lectureTitle: panel.dataset.lessonLectureTitle || '',
            historyUrl: panel.dataset.lessonHistoryUrl || '',
            askUrl: panel.dataset.lessonAskUrl || '',
            newSessionUrl: panel.dataset.lessonNewSessionUrl || '',
            historyLoaded: false,
        }
    };

    const quickActions = {
        website: [
            'Khoa nao toi chua hoc?',
            'Tien do khoa hoc cua toi',
            'Toi co chung chi nao roi?',
            'Lich su quiz cua toi',
            'Yeu cau hoan tien cua toi',
            'Cach dung wishlist'
        ],
        lesson: [
            'Tom tat noi dung bai nay',
            'Giai thich y chinh cua bai hoc',
            'Bai nay dang noi ve gi?'
        ]
    };

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

    function renderQuickActions() {
        if (!quickActionsEl) return;

        const actions = state.mode === 'lesson' ? quickActions.lesson : quickActions.website;
        quickActionsEl.innerHTML = actions.map((action) => {
            return `<button type="button" class="assistant-quick-action px-2 py-1 border border-slate-700 bg-black/30 text-slate-200 text-[10px] uppercase font-bold hover:bg-brand hover:text-black hover:border-black transition-colors">${escapeHtml(action)}</button>`;
        }).join('');

        quickActionsEl.querySelectorAll('.assistant-quick-action').forEach((button) => {
            button.addEventListener('click', () => {
                if (input) {
                    input.value = button.textContent.trim();
                    input.focus();
                }
            });
        });
    }

    function renderQuickActionsVisibility() {
        if (!quickActionsEl || !quickActionsIconEl) return;

        quickActionsEl.classList.toggle('hidden', !state.quickActionsOpen);
        quickActionsIconEl.classList.toggle('fa-chevron-down', !state.quickActionsOpen);
        quickActionsIconEl.classList.toggle('fa-chevron-up', state.quickActionsOpen);
    }

    function renderHeader() {
        if (state.mode === 'lesson') {
            titleEl.textContent = 'AI Tutor bai hoc';
            input.placeholder = 'Hoi ve noi dung bai hoc hien tai...';
            hintEl.textContent = '';
        } else {
            titleEl.textContent = 'Tro ly he thong';
            input.placeholder = 'Hoi ve tien do hoc, quiz, chung chi hoac cach dung he thong...';
            hintEl.textContent = '';
        }

        modeButtons.forEach((button) => {
            const isActive = button.dataset.mode === state.mode;
            button.classList.toggle('bg-brand', isActive);
            button.classList.toggle('text-black', isActive);
            button.classList.toggle('text-white', !isActive);
        });
    }

    function scrollToBottom() {
        if (messagesEl) {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }
    }

    function messageHtml(message) {
        const isUser = message.role === 'user';
        const content = escapeHtml(message.content || message.answer || '');

        return `
            <div class="flex ${isUser ? 'justify-end' : 'justify-start'}">
                <div class="${isUser ? 'bg-brand text-black' : 'bg-[#2A2A3C] text-white'} max-w-[90%] border-2 border-black px-4 py-3">
                    <div class="text-[10px] uppercase font-black tracking-widest mb-2 ${isUser ? 'text-black/70' : 'text-brand'}">
                        ${isUser ? 'Ban' : 'AI Tutor'}
                    </div>
                    <div class="text-sm leading-relaxed whitespace-pre-wrap">${content}</div>
                </div>
            </div>
        `;
    }

    function appendMessage(message) {
        if (!messagesEl) return;
        messagesEl.insertAdjacentHTML('beforeend', messageHtml(message));
        scrollToBottom();
    }

    function getModeState() {
        return state[state.mode];
    }

    async function loadHistory(force = false) {
        const modeState = getModeState();
        if (modeState.historyLoaded && !force) return;

        setStatus('Dang tai lich su chat...');

        let url = new URL(modeState.historyUrl, window.location.origin);
        if (state.mode === 'lesson') {
            url.searchParams.set('course_id', modeState.courseId);
            url.searchParams.set('lecture_id', modeState.lectureId);
        }

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
                throw new Error(payload.message || 'Khong the tai lich su chat.');
            }

            messagesEl.innerHTML = '';
            const data = payload.data || {};
            const messages = data.messages || [];

            if (!messages.length) {
                messagesEl.innerHTML = `<div class="text-slate-400 text-sm italic">${state.mode === 'lesson' ? 'Chua co lich su chat cho bai hoc nay.' : 'Chua co lich su chat cho tro ly he thong.'}</div>`;
            } else {
                messages.forEach((message) => appendMessage(message));
            }

            modeState.historyLoaded = true;
            setStatus('');
        } catch (error) {
            setStatus(error.message || 'Khong the tai lich su chat.', 'error');
        }
    }

    async function askQuestion(messageContent) {
        const modeState = getModeState();
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('message', messageContent);

        if (state.mode === 'lesson') {
            formData.append('course_id', modeState.courseId);
            formData.append('lecture_id', modeState.lectureId);
        }

        if (submitBtn) submitBtn.disabled = true;
        if (input) input.disabled = true;
        setStatus('AI dang xu ly...');

        appendMessage({ role: 'user', content: messageContent });

        try {
            const response = await fetch(modeState.askUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Khong the gui cau hoi.');
            }

            const data = payload.data || {};
            const answer = data.answer || data.message || 'AI chua tra ve noi dung tra loi.';

            appendMessage({
                role: 'assistant',
                content: answer,
            });

            setStatus('AI da tra loi xong.', 'success');
        } catch (error) {
            appendMessage({
                role: 'assistant',
                content: error.message || 'Xin loi, minh chua the tra loi luc nay. Ban thu lai sau it phut.'
            });
            setStatus(error.message || 'Co loi xay ra khi gui cau hoi.', 'error');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
            if (input) {
                input.disabled = false;
                input.value = '';
                input.focus();
            }
        }
    }

    async function createNewSession() {
        const modeState = getModeState();

        if (state.mode === 'lesson') {
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('course_id', modeState.courseId);
            formData.append('lecture_id', modeState.lectureId);

            try {
                const response = await fetch(modeState.newSessionUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Khong the tao phien chat moi.');
                }

                modeState.historyLoaded = true;
                messagesEl.innerHTML = '<div class="text-slate-400 text-sm italic">Phien chat moi cho bai hoc hien tai da san sang.</div>';
                renderQuickActions();
                setStatus(payload.message || 'Da tao phien chat moi.', 'success');
            } catch (error) {
                setStatus(error.message || 'Khong the tao phien chat moi.', 'error');
            }

            return;
        }

        try {
            const response = await fetch(modeState.newSessionUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Khong the tao phien chat moi.');
            }

            modeState.historyLoaded = true;
            messagesEl.innerHTML = '<div class="text-slate-400 text-sm italic">Phien chat moi da san sang. Ban co the bat dau cuoc tro chuyen moi.</div>';
            renderQuickActions();
            setStatus(payload.message || 'Da tao phien chat moi.', 'success');
        } catch (error) {
            setStatus(error.message || 'Khong the tao phien chat moi.', 'error');
        }
    }

    function openPanel() {
        panel.classList.remove('hidden');
        renderHeader();
        renderQuickActions();
        renderQuickActionsVisibility();
        loadHistory();
    }

    function closePanel() {
        panel.classList.add('hidden');
        setStatus('');
    }

    function switchMode(mode) {
        if (mode === state.mode) return;
        state.mode = mode;
        renderHeader();
        renderQuickActions();
        renderQuickActionsVisibility();

        if (messagesEl) {
            messagesEl.innerHTML = '<div class="text-slate-400 text-sm italic">Dang tai lich su chat...</div>';
        }

        loadHistory(true);
    }

    async function handleInitialAssistantState() {
        const requestedMode = searchParams.get('assistant');
        const requestedAction = searchParams.get('assistant_action');

        if (!requestedMode && !requestedAction) {
            return;
        }

        if (requestedMode === 'website') {
            state.mode = 'website';
        } else if (requestedMode === 'lesson' && hasLessonMode) {
            state.mode = 'lesson';
        }

        openPanel();

        if (requestedAction === 'new') {
            await createNewSession();
        }
    }

    toggleBtn?.addEventListener('click', () => {
        if (panel.classList.contains('hidden')) {
            openPanel();
        } else {
            closePanel();
        }
    });

    closeBtn?.addEventListener('click', closePanel);
    newSessionBtn?.addEventListener('click', createNewSession);
    quickActionsToggleEl?.addEventListener('click', () => {
        state.quickActionsOpen = !state.quickActionsOpen;
        renderQuickActionsVisibility();
    });

    modeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            switchMode(button.dataset.mode);
        });
    });

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = (input?.value || '').trim();
        if (message.length < 2) {
            setStatus('Cau hoi qua ngan.', 'error');
            input?.focus();
            return;
        }

        await askQuestion(message);
    });

    window.StackLearnAssistantPanel = {
        reloadLessonContext(context = {}) {
            if (!hasLessonMode) return;

            if (context.courseId) {
                state.lesson.courseId = String(context.courseId);
                panel.dataset.lessonCourseId = state.lesson.courseId;
            }

            if (context.lectureId) {
                state.lesson.lectureId = String(context.lectureId);
                panel.dataset.lessonLectureId = state.lesson.lectureId;
            }

            if (context.lectureTitle) {
                state.lesson.lectureTitle = String(context.lectureTitle);
                panel.dataset.lessonLectureTitle = state.lesson.lectureTitle;
            }

            state.lesson.historyLoaded = false;

            if (state.mode === 'lesson' && messagesEl) {
                messagesEl.innerHTML = '<div class="text-slate-400 text-sm italic">Da chuyen sang bai hoc moi. Gui cau hoi de tai phien chat cua bai hoc nay.</div>';
                renderHeader();
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        renderHeader();
        renderQuickActions();
        renderQuickActionsVisibility();
        handleInitialAssistantState();
    });
})();
