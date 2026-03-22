<div id="tab-qa" class="tab-content py-8 hidden">
    <div class="max-w-4xl space-y-10">

        @auth
            <form id="discussionForm" action="{{ route('lecture.discussion.store') }}" method="POST">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="lecture_id" value="{{ $currentLecture->id }}">

                <div class="bg-cyber-surface border-4 border-black p-6 pixel-shadow mb-10">
                    <h3 class="text-white font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-comment-dots text-brand"></i> Đặt câu hỏi mới
                    </h3>

                    <div class="relative">
                        <textarea id="discussionContent" name="content" placeholder="Bạn có thắc mắc gì về bài học này?..."
                            class="w-full bg-cyber-dark border-2 border-slate-700 p-4 text-white focus:border-brand focus:ring-0 transition-colors font-sans min-h-[120px]"></textarea>

                        <div id="discussionContentError" class="text-red-400 text-sm mt-2"></div>

                        <div class="absolute bottom-4 right-4">
                            <button type="submit" id="discussionSubmitBtn"
                                class="bg-brand text-black font-black uppercase px-6 py-2 border-2 border-black hover:bg-white transition-colors pixel-shadow-sm">
                                Gửi câu hỏi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <p class="text-center text-slate-400 uppercase font-bold tracking-widest text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> Vui lòng đăng nhập để tham gia hỏi đáp
            </p>
        @endauth

        <div id="discussionAlert" class="mt-3"></div>

        <div id="discussionList" class="space-y-6">
            @include('frontend.pages.learning.partials.qna-list')
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabQa = document.getElementById('tab-qa');
            const discussionForm = document.getElementById('discussionForm');
            const contentError = document.getElementById('discussionContentError');
            const discussionCount = document.getElementById('discussionCount');

            if (!tabQa || window.qaInitialized) return;
            window.qaInitialized = true;

            // Helper to get current IDs from the form (always up to date)
            const getCurrentConfig = () => {
                return {
                    courseId: discussionForm?.querySelector('input[name="course_id"]')?.value,
                    lectureId: discussionForm?.querySelector('input[name="lecture_id"]')?.value,
                    storeUrl: document.getElementById('discussionItems')?.dataset.storeUrl || '/lecture/discussion',
                    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                };
            };

            // --- Helpers ---
            function updateCount(delta) {
                const countEl = document.getElementById('discussionCount');
                if (!countEl) return;
                const match = countEl.textContent.match(/^(\d+)/);
                if (match) {
                    const next = Math.max(0, parseInt(match[1], 10) + delta);
                    countEl.textContent = `${next} Câu hỏi & Thảo luận`;
                }
            }

            function buildReplyForm(parentId) {
                const config = getCurrentConfig();
                return `
                    <form class="discussion-reply-form mt-3" data-parent-id="${parentId}">
                        <input type="hidden" name="_token" value="${config.csrfToken}">
                        <input type="hidden" name="course_id" value="${config.courseId}">
                        <input type="hidden" name="lecture_id" value="${config.lectureId}">
                        <input type="hidden" name="parent_id" value="${parentId}">

                        <textarea name="content" 
                            class="w-full bg-cyber-dark border-2 border-slate-700 p-3 text-white focus:border-brand focus:ring-0 transition-colors font-sans min-h-[90px]"
                            placeholder="Nhập phản hồi của bạn..."></textarea>

                        <div class="reply-error text-red-400 text-sm mt-2"></div>

                        <div class="mt-3 flex gap-2">
                            <button type="submit" class="bg-brand text-black font-black uppercase px-4 py-2 border-2 border-black hover:bg-white transition-colors pixel-shadow-sm">
                                Gửi phản hồi
                            </button>
                            <button type="button" class="reply-cancel-btn px-4 py-2 border-2 border-slate-600 text-white hover:bg-white/10 transition-colors">
                                Hủy
                            </button>
                        </div>
                    </form>
                `;
            }

            // --- Global Actions (Delete) ---
            window.deleteDiscussion = async function(id) {
                const result = await Swal.fire({
                    title: 'BẠN CÓ CHẮC CHẮN?',
                    text: "Thảo luận này sẽ bị xóa!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#A6E22E',
                    confirmButtonText: 'XÓA NGAY',
                    cancelButtonText: 'HỦY',
                    background: '#2A2A3C',
                    color: '#F8F8F2'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/lecture/discussion/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': getCurrentConfig().csrfToken,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const item = document.getElementById(`discussion-item-${id}`);
                            if (item) {
                                item.style.opacity = '0';
                                item.style.transform = 'scale(0.95)';
                                setTimeout(() => {
                                    item.remove();
                                    updateCount(-1);
                                    const itemsContainer = document.getElementById('discussionItems');
                                    if (itemsContainer && itemsContainer.children.length === 0) {
                                        itemsContainer.innerHTML = '<p id="emptyDiscussionMessage" class="text-center text-slate-500 py-10 uppercase font-black tracking-widest text-sm">Không có thảo luận nào</p>';
                                    }
                                }, 300);
                            }
                            Swal.fire({ icon: 'success', title: 'Đã xóa!', position: 'top-end', toast: true, showConfirmButton: false, timer: 1500 });
                        }
                    } catch (e) {
                        console.error('Delete failed:', e);
                    }
                }
            };

            // --- Event Listeners with Delegation on persistent parent ---
            tabQa.addEventListener('click', function(e) {
                const replyBtn = e.target.closest('.discussion-reply-toggle');
                if (replyBtn) {
                    const id = replyBtn.dataset.discussionId;
                    const container = document.getElementById(`reply-form-container-${id}`);
                    if (!container) return;

                    if (container.querySelector('.discussion-reply-form')) {
                        container.classList.toggle('hidden');
                    } else {
                        container.innerHTML = buildReplyForm(id);
                        container.classList.remove('hidden');
                        container.querySelector('textarea')?.focus();
                    }
                    return;
                }

                const cancelBtn = e.target.closest('.reply-cancel-btn');
                if (cancelBtn) {
                    const container = cancelBtn.closest('[id^="reply-form-container-"]');
                    if (container) {
                        container.innerHTML = '';
                        container.classList.add('hidden');
                    }
                    return;
                }

                const deleteBtn = e.target.closest('.discussion-delete-btn');
                if (deleteBtn) {
                    window.deleteDiscussion(deleteBtn.dataset.discussionId);
                }
            });

            tabQa.addEventListener('submit', async function(e) {
                const form = e.target.closest('.discussion-reply-form');
                if (!form) return;

                e.preventDefault();
                const config = getCurrentConfig();
                const parentId = form.dataset.parentId;
                const submitBtn = form.querySelector('button[type="submit"]');
                const errorBox = form.querySelector('.reply-error');

                submitBtn.disabled = true;
                if (errorBox) errorBox.innerHTML = '';

                try {
                    const response = await fetch(config.storeUrl, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': config.csrfToken },
                        body: new FormData(form)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        const children = document.getElementById(`discussion-children-${parentId}`);
                        if (children) children.insertAdjacentHTML('afterbegin', data.html);
                        
                        const container = form.closest('[id^="reply-form-container-"]');
                        if (container) {
                            container.innerHTML = '';
                            container.classList.add('hidden');
                        }
                        
                        Swal.fire({ icon: 'success', title: 'Đã phản hồi!', position: 'top-end', toast: true, showConfirmButton: false, timer: 2000 });
                    } else if (response.status === 422) {
                        if (errorBox) errorBox.innerHTML = data.errors?.content?.[0] || 'Nội dung không hợp lệ';
                    } else {
                        throw new Error(data.message);
                    }
                } catch (err) {
                    if (errorBox) errorBox.innerHTML = err.message || 'Lỗi hệ thống';
                } finally {
                    submitBtn.disabled = false;
                }
            });

            // --- Discussion Report Handling ---
            tabQa.addEventListener('submit', async function(e) {
                const reportForm = e.target.closest('.discussion-report-form');
                if (!reportForm) return;

                e.preventDefault();

                const submitBtn = reportForm.querySelector('button[type="submit"]');
                const discussionId = reportForm.dataset.discussionId;
                const config = getCurrentConfig();

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Đang gửi...';
                }

                try {
                    const response = await fetch(`/reports/discussions/${discussionId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(reportForm),
                    });

                    const data = await response.json();

                    if (response.ok && data.status === 'success') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message || 'Báo cáo đã được gửi',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#2A2A3C',
                            color: '#F8F8F2',
                            iconColor: '#A6E22E'
                        });
                        reportForm.reset();
                        reportForm.classList.add('hidden');
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: data.message || 'Gửi báo cáo thất bại',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#2A2A3C',
                            color: '#F8F8F2',
                            iconColor: '#ff4d4f'
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Có lỗi xảy ra',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#2A2A3C',
                        color: '#F8F8F2',
                        iconColor: '#ff4d4f'
                    });
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Gửi report';
                    }
                }
            });

            if (discussionForm) {
                let isMainSubmitting = false;
                discussionForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    if (isMainSubmitting) return;

                    const config = getCurrentConfig();
                    isMainSubmitting = true;
                    const btn = document.getElementById('discussionSubmitBtn');
                    const localContentError = document.getElementById('discussionContentError');
                    
                    if (localContentError) localContentError.innerHTML = '';
                    if (btn) { btn.disabled = true; btn.innerText = 'Đang gửi...'; }

                    try {
                        const response = await fetch(config.storeUrl, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': config.csrfToken },
                            body: new FormData(discussionForm)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            const discussionItems = document.getElementById('discussionItems');
                            document.getElementById('emptyDiscussionMessage')?.remove();
                            if (discussionItems) discussionItems.insertAdjacentHTML('afterbegin', data.html);
                            discussionForm.reset();
                            updateCount(1);
                            Swal.fire({ icon: 'success', title: 'Đã gửi câu hỏi!', position: 'top-end', toast: true, showConfirmButton: false, timer: 2000 });
                        } else if (response.status === 422) {
                            if (localContentError) localContentError.innerHTML = data.errors?.content?.[0];
                        }
                    } catch (err) {
                        console.error('Error submitting main form:', err);
                    } finally {
                        isMainSubmitting = false;
                        if (btn) { btn.disabled = false; btn.innerText = 'Gửi câu hỏi'; }
                    }
                });
            }
        });
    </script>
@endpush
