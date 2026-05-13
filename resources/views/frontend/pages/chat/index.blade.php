@extends('frontend.master')
@section('content')
<main class="min-h-screen pt-10 pb-20 bg-cyber-dark text-white">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Breadcrumbs -->
        <nav class="mb-8 flex items-center gap-2 text-xs font-mono text-text-secondary uppercase tracking-widest">
            <a href="{{ route('frontend.home') }}" class="hover:text-brand transition-colors">Home</a>
            <i class="fas fa-chevron-right text-[8px]"></i>
            <span class="text-brand">Tin nhắn</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-6 h-[700px]">
            <!-- Sidebar: Conversations List -->
            <aside class="w-full lg:w-1/3 bg-cyber-surface border-2 border-black pixel-shadow flex flex-col">
                <div class="p-4 border-b border-black/20">
                    <h2 class="text-lg font-pixel text-brand uppercase">Cuộc hội thoại</h2>
                </div>
                <div class="flex-grow overflow-y-auto custom-scrollbar">
                    @forelse($conversations as $conversation)
                        @php
                            $otherUser = Auth::id() === $conversation->student_id ? $conversation->instructor : $conversation->student;
                            $lastMessage = $conversation->messages->first();
                        @endphp
                        <div class="conversation-item p-4 border-b border-black/10 hover:bg-black/20 cursor-pointer transition-colors {{ request('id') == $conversation->id ? 'bg-black/30 border-l-4 border-l-brand' : '' }}"
                             onclick="loadConversation({{ $otherUser->id }}, {{ $conversation->id }})">
                            <div class="flex gap-4 items-center">
                                <div class="w-12 h-12 shrink-0 bg-slate-800 border-2 border-black overflow-hidden">
                                    <img src="{{ asset($otherUser->photo) }}" alt="{{ $otherUser->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="overflow-hidden">
                                    <h3 class="font-bold text-sm truncate">{{ $otherUser->name }}</h3>
                                    <p class="text-xs text-slate-400 truncate">{{ $lastMessage->message ?? 'Chưa có tin nhắn' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 font-mono text-sm">
                            Chưa có cuộc hội thoại nào.
                        </div>
                    @endforelse
                </div>
            </aside>

            <!-- Main Chat Area -->
            <section class="flex-grow bg-cyber-surface border-2 border-black pixel-shadow flex flex-col relative overflow-hidden">
                <div id="chat-header" class="p-4 border-b border-black/20 flex items-center gap-4 bg-black/10 hidden">
                    <div id="header-avatar" class="w-10 h-10 bg-slate-800 border-2 border-black overflow-hidden">
                        <img src="" alt="" class="w-full h-full object-cover">
                    </div>
                    <h2 id="header-name" class="font-pixel text-brand text-sm uppercase"></h2>
                </div>

                <!-- Messages Container -->
                <div id="chat-box" class="flex-grow p-6 overflow-y-auto custom-scrollbar flex flex-col gap-4">
                    <div class="flex flex-col items-center justify-center h-full text-slate-500 font-mono italic">
                        <i class="far fa-comments text-4xl mb-4 opacity-20"></i>
                        <p>Chọn một cuộc hội thoại để bắt đầu chat</p>
                    </div>
                </div>

                <!-- Input Area -->
                <div id="chat-input-area" class="p-4 border-t border-black/20 bg-black/5 hidden">
                    <form id="chat-form" class="flex gap-2">
                        <textarea id="chat-input" 
                            class="flex-grow bg-cyber-dark border-2 border-black p-3 text-sm text-white focus:border-brand focus:ring-0 transition-colors font-sans min-h-[50px] max-h-[150px] resize-none"
                            placeholder="Nhập tin nhắn..."></textarea>
                        <button type="submit" class="bg-brand text-black font-black uppercase px-6 py-2 border-2 border-black hover:bg-white transition-colors pixel-shadow-sm shrink-0">
                            Gửi
                        </button>
                    </form>
                </div>

                <!-- Loading Overlay -->
                <div id="chat-loading" class="absolute inset-0 bg-black/60 z-50 flex items-center justify-center hidden">
                    <div class="text-brand font-pixel animate-pulse">Đang tải...</div>
                </div>
            </section>
        </div>
    </div>
</main>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
</style>

@push('scripts')
<script>
    let currentConversationId = null;
    let activeChannel = null;

    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatLoading = document.getElementById('chat-loading');
    const chatHeader = document.getElementById('chat-header');
    const chatInputArea = document.getElementById('chat-input-area');

    function loadConversation(instructorId, conversationId = null) {
        chatLoading.classList.remove('hidden');
        
        fetch(`/chat/conversation/${instructorId}`)
            .then(res => res.json())
            .then(data => {
                const conversation = data.conversation;
                const messages = data.messages;
                const otherUser = {{ Auth::id() }} === conversation.student_id ? conversation.instructor : conversation.student;

                currentConversationId = conversation.id;
                
                // Update UI Header
                chatHeader.classList.remove('hidden');
                chatInputArea.classList.remove('hidden');
                document.getElementById('header-name').textContent = otherUser.name;
                
                let avatarUrl = otherUser.photo;
                if (avatarUrl && !avatarUrl.startsWith('http')) {
                    avatarUrl = '/' + avatarUrl;
                }
                document.getElementById('header-avatar').querySelector('img').src = avatarUrl;

                // Render Messages
                chatBox.innerHTML = '';
                messages.forEach(msg => {
                    renderMessage(msg);
                });
                
                chatBox.scrollTop = chatBox.scrollHeight;
                
                // Setup Realtime Echo
                subscribeToConversation(currentConversationId);
                
                chatLoading.classList.add('hidden');
            });
    }

    function renderMessage(msg) {
        const isMyMessage = msg.sender_id === {{ Auth::id() }};
        const messageHtml = `
            <div class="${isMyMessage ? 'self-end text-right' : 'self-start text-left'} max-w-[80%]">
                <div class="inline-block p-3 rounded-sm border-2 border-black ${isMyMessage ? 'bg-brand text-black pixel-shadow-sm' : 'bg-slate-800 text-white'}">
                    <p class="text-sm font-sans">${msg.message}</p>
                </div>
                <div class="mt-1 flex items-center gap-2 ${isMyMessage ? 'justify-end' : ''}">
                    <span class="text-[10px] font-mono text-slate-500">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">${msg.sender.name}</span>
                </div>
            </div>
        `;
        chatBox.insertAdjacentHTML('beforeend', messageHtml);
    }

    function subscribeToConversation(conversationId) {
        if (!window.Echo) {
            console.warn('Laravel Echo chưa được tải. Vui lòng kiểm tra lại cấu hình.');
            return;
        }

        // Leave previous channel if any
        if (activeChannel) {
            window.Echo.leave(`conversation.${activeChannel}`);
        }

        activeChannel = conversationId;

        // Lắng nghe trên Private Channel (Code bạn cung cấp)
        window.Echo.private(`conversation.${conversationId}`)
            .listen('.message.sent', (e) => {
                // Tránh hiển thị 2 lần nếu là tin nhắn của chính mình
                if (e.message.sender_id === {{ Auth::id() }}) return;

                console.log('Tin nhắn mới nhận được:', e.message);
                renderMessage(e.message);
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message || !currentConversationId) return;

        chatInput.value = '';
        
        fetch(`/chat/send/${currentConversationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                renderMessage(data.message);
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    });

    // Support Shift+Enter for new line, Enter for send
    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Auto-load if instructorId is in URL, otherwise load first conversation
    window.addEventListener('load', function() {
        @if(request('instructor_id'))
            loadConversation({{ request('instructor_id') }});
        @elseif($conversations->count() > 0)
            @php
                $firstConv = $conversations->first();
                $otherUser = Auth::id() === $firstConv->student_id ? $firstConv->instructor : $firstConv->student;
            @endphp
            loadConversation({{ $otherUser->id }});
        @endif
    });
</script>
@endpush
@endsection
