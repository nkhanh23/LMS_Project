@extends('backend.instructor.master')

@section('content')
<div class="page-content">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Tin nhắn</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hội thoại</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <div class="card border-0 shadow-sm" style="height: calc(100vh - 180px); overflow: hidden;">
        <div class="row g-0 h-100">
            <!-- Sidebar: Conversations List -->
            <div class="col-12 col-lg-4 col-xl-3 border-end d-flex flex-column h-100 bg-white">
                <div class="p-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: #667eea;">
                        <i class="bx bx-message-square-dots me-1"></i> Cuộc hội thoại
                    </h6>
                </div>
                <div class="flex-grow-1 overflow-auto" id="conversation-list">
                    @forelse($conversations as $conversation)
                        @php
                            $otherUser = Auth::id() === $conversation->student_id ? $conversation->instructor : $conversation->student;
                            $lastMessage = $conversation->messages->first();
                        @endphp
                        @if($otherUser)
                        <div class="p-3 border-bottom conversation-item {{ request('id') == $conversation->id ? 'active' : '' }}" 
                             style="cursor: pointer; transition: all 0.2s;"
                             onclick="loadConversation({{ $otherUser->id }}, {{ $conversation->id }}, this)">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset($otherUser->photo) }}" class="rounded-circle me-3 border shadow-sm" width="45" height="45" style="object-fit: cover;" alt="">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-1 text-truncate fw-semibold" style="font-size: 14px;">{{ $otherUser->name }}</h6>
                                    <p class="mb-0 text-muted text-truncate" style="font-size: 13px;">
                                        {{ $lastMessage->message ?? 'Chưa có tin nhắn' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="bx bx-conversation fs-1 mb-2 opacity-50"></i>
                            <p>Chưa có cuộc hội thoại nào.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="col-12 col-lg-8 col-xl-9 d-flex flex-column h-100 position-relative bg-light">
                <!-- Header -->
                <div id="chat-header" class="p-3 border-bottom bg-white d-flex align-items-center d-none shadow-sm z-1">
                    <img id="header-avatar" src="" class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;" alt="">
                    <h6 id="header-name" class="mb-0 fw-bold text-dark"></h6>
                </div>

                <!-- Messages Container -->
                <div id="chat-box" class="flex-grow-1 p-4 overflow-auto d-flex flex-column gap-3">
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                        <i class="bx bx-message-rounded-dots" style="font-size: 4rem; opacity: 0.2;"></i>
                        <p class="mt-2">Chọn một cuộc hội thoại để bắt đầu chat</p>
                    </div>
                </div>

                <!-- Input Area -->
                <div id="chat-input-area" class="p-3 bg-white border-top d-none">
                    <form id="chat-form" class="d-flex gap-2 align-items-end">
                        <textarea id="chat-input" class="form-control rounded-3 px-3 py-2 bg-light border-0 shadow-none" 
                                  rows="1" placeholder="Nhập tin nhắn..." 
                                  style="resize: none; max-height: 100px; overflow-y: auto;"></textarea>
                        <button type="submit" class="btn btn-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" 
                                style="width: 44px; height: 44px;">
                            <i class="bx bxs-send fs-5"></i>
                        </button>
                    </form>
                </div>

                <!-- Loading Overlay -->
                <div id="chat-loading" class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center d-none" style="z-index: 10;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<style>
    .conversation-item:hover { background-color: #f8f9fa; }
    .conversation-item.active { background-color: #e2e8f0; border-left: 4px solid #667eea; }
    
    .chat-bubble {
        max-width: 75%;
        padding: 10px 16px;
        border-radius: 12px;
        font-size: 14.5px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        line-height: 1.5;
    }
    .chat-bubble-me {
        background-color: #667eea;
        background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 4px;
    }
    .chat-bubble-other {
        background-color: white;
        color: #212529;
        border-bottom-left-radius: 4px;
        border: 1px solid #e2e8f0;
    }
    .chat-time {
        font-size: 11px;
        color: #a0aec0;
        margin-top: 4px;
        font-weight: 500;
    }
    
    #chat-box::-webkit-scrollbar, #conversation-list::-webkit-scrollbar, #chat-input::-webkit-scrollbar { width: 6px; }
    #chat-box::-webkit-scrollbar-track, #conversation-list::-webkit-scrollbar-track, #chat-input::-webkit-scrollbar-track { background: transparent; }
    #chat-box::-webkit-scrollbar-thumb, #conversation-list::-webkit-scrollbar-thumb, #chat-input::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 3px; }
    
    #chat-input:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
    }
</style>

<script>
    let currentConversationId = null;
    let activeChannel = null;

    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatLoading = document.getElementById('chat-loading');
    const chatHeader = document.getElementById('chat-header');
    const chatInputArea = document.getElementById('chat-input-area');

    function loadConversation(instructorId, conversationId = null, element = null) {
        chatLoading.classList.remove('d-none');
        
        // Cập nhật trạng thái active cho item được chọn
        document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
        if (element) {
            element.classList.add('active');
        }

        fetch(`/chat/conversation/${instructorId}`)
            .then(res => res.json())
            .then(data => {
                const conversation = data.conversation;
                const messages = data.messages;
                const otherUser = {{ Auth::id() }} === conversation.student_id ? conversation.instructor : conversation.student;

                currentConversationId = conversation.id;

                // Hiển thị Header và Input
                chatHeader.classList.remove('d-none');
                chatInputArea.classList.remove('d-none');
                document.getElementById('header-name').textContent = otherUser.name;

                let avatarUrl = otherUser.photo;
                if (avatarUrl && !avatarUrl.startsWith('http')) {
                    avatarUrl = '/' + avatarUrl;
                }
                document.getElementById('header-avatar').src = avatarUrl;

                // Render tin nhắn
                chatBox.innerHTML = '';
                messages.forEach(msg => {
                    renderMessage(msg);
                });

                chatBox.scrollTop = chatBox.scrollHeight;
                
                // Đăng ký nhận tin nhắn realtime
                subscribeToConversation(currentConversationId);
                
                chatLoading.classList.add('d-none');
                chatInput.focus();
            });
    }

    function renderMessage(msg) {
        const isMyMessage = msg.sender_id === {{ Auth::id() }};
        const timeStr = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        const messageHtml = `
            <div class="d-flex flex-column ${isMyMessage ? 'align-items-end' : 'align-items-start'} w-100">
                <div class="chat-bubble ${isMyMessage ? 'chat-bubble-me' : 'chat-bubble-other'}">
                    ${msg.message.replace(/\n/g, '<br>')}
                </div>
                <div class="chat-time d-flex gap-2 ${isMyMessage ? 'flex-row-reverse' : ''}">
                    <span>${timeStr}</span>
                </div>
            </div>
        `;
        chatBox.insertAdjacentHTML('beforeend', messageHtml);
    }

    function subscribeToConversation(conversationId) {
        if (!window.Echo) {
            console.warn('Laravel Echo chưa được tải.');
            return;
        }

        if (activeChannel) {
            window.Echo.leave(`conversation.${activeChannel}`);
        }

        activeChannel = conversationId;

        window.Echo.private(`conversation.${conversationId}`)
            .listen('.message.sent', (e) => {
                if (e.message.sender_id === {{ Auth::id() }}) return;
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

    // Shift+Enter xuống dòng, Enter gửi tin nhắn
    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Auto-load tin nhắn đầu tiên khi vào trang
    window.addEventListener('load', function() {
        @if (request('instructor_id'))
            loadConversation({{ request('instructor_id') }});
        @elseif ($conversations->count() > 0)
            @php
                $firstConv = $conversations->first();
                $otherUser = Auth::id() === $firstConv->student_id ? $firstConv->instructor : $firstConv->student;
            @endphp
            
            // Tìm và click vào item đầu tiên
            const firstItem = document.querySelector('.conversation-item');
            if(firstItem) {
                loadConversation({{ $otherUser->id }}, {{ $firstConv->id }}, firstItem);
            }
        @endif
    });
</script>
@endpush
