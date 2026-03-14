<div id="tab-notes" class="tab-content py-8 hidden">
    <div class="max-w-4xl space-y-10">
        @auth
            <!-- Add Note Button & Form -->
            <div class="bg-cyber-surface border-4 border-black p-6 pixel-shadow mb-10">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                    <h3 class="text-white font-black uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-note-sticky text-brand"></i> Ghi chú của bạn
                    </h3>

                    <button type="button" id="addNoteBtn"
                        class="bg-brand text-black font-black uppercase px-6 py-2 border-2 border-black hover:bg-white transition-colors pixel-shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Thêm ghi chú tại <span id="currentVideoTime"
                            class="bg-black text-brand px-2 py-0.5 rounded ml-1">00:00</span>
                    </button>
                </div>
                <form id="noteForm" data-mode="add" data-edit-id="">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="lecture_id" value="{{ $currentLecture->id }}">
                    <input type="hidden" name="video_second" id="noteVideoSecond" value="0">

                    <div id="noteFormContainer" class="hidden relative mt-4 border-t-2 border-slate-800 pt-6">
                        <h4 id="noteFormTitle" class="text-xs font-bold text-brand uppercase mb-3">Thêm ghi chú mới</h4>
                        <textarea id="noteContent" name="note" placeholder="Nhập nội dung ghi chú của bạn tại đây..."
                            class="w-full bg-cyber-dark border-2 border-slate-700 p-4 text-white focus:border-brand focus:ring-0 transition-colors font-sans min-h-[120px]"></textarea>
                        <div id="noteContentError" class="text-red-400 text-sm mt-2"></div>
                        <div class="flex justify-end gap-2 mt-3">
                            <button type="button" id="cancelNoteBtn"
                                class="px-6 py-2 border-2 border-slate-600 text-white hover:bg-white/10 transition-colors uppercase font-bold text-sm">
                                Hủy
                            </button>
                            <button type="submit" id="saveNoteBtn"
                                class="bg-brand text-black font-black uppercase px-6 py-2 border-2 border-black hover:bg-white transition-colors pixel-shadow-sm text-sm">
                                Lưu ghi chú
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Notes List -->
            <div class="flex items-center justify-between border-b-2 border-slate-800 pb-4">
                <h4 id="noteCountDisplay" class="text-lg font-black text-white uppercase tracking-tighter">
                    {{ count($notes ?? []) }} Ghi chú
                </h4>

                <div class="flex gap-4 text-xs font-bold text-slate-500 uppercase">
                    <button type="button" class="text-brand">Mới nhất</button>
                    <button type="button" class="hover:text-white">Cũ nhất</button>
                </div>
            </div>

            <div id="noteListWrapper">
                @include('frontend.pages.learning.partials.note-list', ['notes' => $notes ?? collect()])
            </div>
        @else
            <p class="text-slate-400">Vui lòng đăng nhập để sử dụng ghi chú.</p>
        @endauth
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addNoteBtn = document.getElementById('addNoteBtn');
            const noteForm = document.getElementById('noteForm');
            const noteFormContainer = document.getElementById('noteFormContainer');
            const cancelNoteBtn = document.getElementById('cancelNoteBtn');
            const currentVideoTimeSpan = document.getElementById('currentVideoTime');
            const noteVideoSecondInput = document.getElementById('noteVideoSecond');
            const noteContent = document.getElementById('noteContent');
            const noteListWrapper = document.getElementById('noteListWrapper');
            const noteCountDisplay = document.getElementById('noteCountDisplay');
            const tabNotes = document.getElementById('tab-notes');
            const noteFormTitle = document.getElementById('noteFormTitle');
            const saveNoteBtn = document.getElementById('saveNoteBtn');

            if (!tabNotes) return;

            // Update current time display on button
            setInterval(() => {
                const video = document.querySelector('video');
                if (video && currentVideoTimeSpan && noteFormContainer.classList.contains('hidden')) {
                    const time = video.currentTime;
                    const m = Math.floor(time / 60).toString().padStart(2, '0');
                    const s = Math.floor(time % 60).toString().padStart(2, '0');
                    currentVideoTimeSpan.textContent = `${m}:${s}`;
                }
            }, 1000);

            // Toggle form (Add mode)
            addNoteBtn?.addEventListener('click', function() {
                const video = document.querySelector('video');
                noteForm.dataset.mode = 'add';
                noteForm.dataset.editId = '';
                noteFormTitle.innerText = 'Thêm ghi chú mới';
                saveNoteBtn.innerText = 'Lưu ghi chú';
                
                if (video) {
                    const time = Math.floor(video.currentTime);
                    noteVideoSecondInput.value = time;
                    const m = Math.floor(time / 60).toString().padStart(2, '0');
                    const s = Math.floor(time % 60).toString().padStart(2, '0');
                    currentVideoTimeSpan.textContent = `${m}:${s}`;
                }
                noteFormContainer.classList.remove('hidden');
                noteContent.value = '';
                noteContent.focus();
            });

            cancelNoteBtn?.addEventListener('click', function() {
                noteFormContainer.classList.add('hidden');
                noteContent.value = '';
                document.getElementById('noteContentError').innerHTML = '';
            });

            // AJAX Save/Update
            noteForm?.addEventListener('submit', async function(e) {
                e.preventDefault();
                const errorBox = document.getElementById('noteContentError');
                const mode = noteForm.dataset.mode;
                const editId = noteForm.dataset.editId;
                
                let url = '{{ route('lecture.notes.store') }}';
                let method = 'POST';
                
                if (mode === 'edit') {
                    url = `/learning/notes/${editId}`;
                    method = 'POST'; // We'll use method spoofing or PATCH if the route matches
                }

                const formData = new FormData(noteForm);
                if (mode === 'edit') {
                    formData.append('_method', 'PATCH');
                }

                saveNoteBtn.disabled = true;
                saveNoteBtn.innerText = 'Đang xử lý...';
                errorBox.innerHTML = '';

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        if (mode === 'add') {
                            const noteItems = document.getElementById('noteItems');
                            document.getElementById('emptyNoteMessage')?.remove();
                            if (noteItems) {
                                noteItems.insertAdjacentHTML('afterbegin', data.html);
                            } else {
                                noteListWrapper.innerHTML = `<div id="noteItems" class="space-y-6" data-store-url="{{ route('lecture.notes.store') }}">${data.html}</div>`;
                            }
                        } else {
                            // Update existing item
                            const item = document.getElementById(`note-item-${editId}`);
                            if (item) {
                                item.outerHTML = data.html;
                            }
                        }
                        
                        noteForm.reset();
                        noteFormContainer.classList.add('hidden');
                        
                        // Update count
                        const currentCount = noteListWrapper.querySelectorAll('.note-item').length;
                        noteCountDisplay.innerText = `${currentCount} Ghi chú`;
                        
                        Swal.fire({ 
                            icon: 'success', 
                            title: mode === 'add' ? 'Đã thêm ghi chú!' : 'Đã cập nhật!', 
                            position: 'top-end', 
                            toast: true, 
                            showConfirmButton: false, 
                            timer: 2000 
                        });
                    } else if (response.status === 422) {
                        errorBox.innerHTML = data.errors?.note?.[0] || 'Vui lòng nhập nội dung ghi chú';
                    }
                } catch (err) {
                    console.error('Error saving/updating note:', err);
                } finally {
                    saveNoteBtn.disabled = false;
                    saveNoteBtn.innerText = mode === 'add' ? 'Lưu ghi chú' : 'Cập nhật ghi chú';
                }
            });

            // Action delegation
            tabNotes.addEventListener('click', async function(e) {
                // Seek
                const seekBtn = e.target.closest('.note-seek-btn');
                if (seekBtn) {
                    const second = seekBtn.dataset.second;
                    const video = document.querySelector('video');
                    if (video) {
                        video.currentTime = second;
                        video.play();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                    return;
                }

                // Edit
                const editBtn = e.target.closest('.note-edit-btn');
                if (editBtn) {
                    const id = editBtn.dataset.id;
                    const contentValue = editBtn.dataset.note;
                    
                    noteForm.dataset.mode = 'edit';
                    noteForm.dataset.editId = id;
                    noteFormTitle.innerText = 'Chỉnh sửa ghi chú';
                    saveNoteBtn.innerText = 'Cập nhật ghi chú';
                    
                    noteContent.value = contentValue;
                    noteFormContainer.classList.remove('hidden');
                    noteContent.focus();
                    
                    // Scroll to form
                    noteForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                // Delete
                const deleteBtn = e.target.closest('.note-delete-btn');
                if (deleteBtn) {
                    const id = deleteBtn.dataset.id;
                    const result = await Swal.fire({
                        title: 'Xóa ghi chú?',
                        text: "Bạn không thể hoàn tác hành động này!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#A6E22E',
                        confirmButtonText: 'Xóa ngay',
                        cancelButtonText: 'Hủy',
                        background: '#2A2A3C',
                        color: '#F8F8F2'
                    });

                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/learning/notes/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });

                            if (response.ok) {
                                document.getElementById(`note-item-${id}`)?.remove();
                                const currentCount = noteListWrapper.querySelectorAll('.note-item').length;
                                noteCountDisplay.innerText = `${currentCount} Ghi chú`;
                                if (currentCount === 0) {
                                    document.getElementById('noteItems').innerHTML = '<p id="emptyNoteMessage" class="text-center text-slate-400 uppercase font-bold tracking-widest text-sm">Chưa có ghi chú nào</p>';
                                }
                                Swal.fire({ icon: 'success', title: 'Đã xóa!', position: 'top-end', toast: true, showConfirmButton: false, timer: 1500 });
                            }
                        } catch (e) {
                            console.error('Delete failed:', e);
                        }
                    }
                }
            });
        });
    </script>
@endpush
