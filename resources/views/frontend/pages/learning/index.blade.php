<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <title>{{ $course->course_title }} - StackLearn</title>
    @include('frontend.section.link')
    @include('frontend.section.style')
    <style>
        body {
            overflow: hidden;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hp-bar-bg {
            background: #1a2e18;
            border: 2px solid #000;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1E1E2E;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #A6E22E;
            border: 2px solid #000;
        }

        .learning-header {
            height: 60px;
            background: #2A2A3C;
            border-bottom: 4px solid #000;
            display: flex;
            align-items: center;
            padding: 0 20px;
            z-index: 50;
        }

        .learning-container {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .video-content {
            flex: 1;
            overflow-y: auto;
            background: #1E1E2E;
        }

        .curriculum-sidebar {
            width: 400px;
            border-left: 4px solid #000;
            background: #2A2A3C;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 1024px) {
            body {
                overflow: auto;
                height: auto;
            }
            .learning-container {
                flex-direction: column;
                height: auto;
            }
            .curriculum-sidebar {
                width: 100%;
                border-left: 0;
                border-top: 4px solid #000;
                height: auto;
            }
        }
    </style>
</head>

<body class="text-text-primary font-sans selection:bg-brand selection:text-black">
    <!-- ========== PRELOADER ========== -->
    @include('frontend.section.loader')
    <!-- ========== PRELOADER ========== -->

    <!-- Custom Learning Header -->
    <header class="learning-header bg-cyber-surface border-b-4 border-black flex justify-between items-center px-4 shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('chi-tiet', $course->course_name_slug) }}" class="text-white hover:text-brand transition-colors">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand flex items-center justify-center border-2 border-black">
                    <span class="text-black font-black text-xs">SL</span>
                </div>
                <h1 class="text-sm md:text-base font-bold text-white uppercase tracking-tight truncate max-w-[200px] md:max-w-md">
                    {{ $course->course_title }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="hidden md:flex items-center gap-2">
                <div class="w-32 h-2 bg-black border border-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-brand" style="width: 65%"></div>
                </div>
                <span class="text-[10px] font-bold text-brand uppercase">65% Hoàn thành</span>
            </div>
            
            <div class="flex items-center gap-4 text-slate-400 text-sm font-bold uppercase">
                <button class="hover:text-white transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-note-sticky"></i>
                    <span class="hidden sm:inline">Ghi chú</span>
                </button>
                <button class="hover:text-white transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-circle-question"></i>
                    <span class="hidden sm:inline">Hướng dẫn</span>
                </button>
            </div>
        </div>
    </header>

    <main class="learning-container">
        <!-- Left Side: Video Player & Tabs -->
        <div class="video-content custom-scrollbar">
            <div class="w-full">
                @include('frontend.pages.learning.video-player')
            </div>
        </div>

        <!-- Right Side: Curriculum Sidebar -->
        <div class="curriculum-sidebar custom-scrollbar shrink-0">
            @include('frontend.pages.learning.video-list')
        </div>
    </main>

    @include('frontend.section.script')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lecture AJAX Switching
            $(document).on('click', '.lecture-item-link', async function(e) {
                e.preventDefault();
                const lectureId = $(this).data('lecture-id');
                const $link = $(this);
                const $allLinks = $('.lecture-item-link');
                const $sidebarItems = $('.group\\/lecture');

                try {
                    // Update active state in sidebar
                    $sidebarItems.removeClass('bg-brand/5 border-l-4 border-l-brand');
                    $link.closest('.group\\/lecture').addClass('bg-brand/5 border-l-4 border-l-brand');
                    $allLinks.find('h4').removeClass('text-brand').addClass('text-slate-200');
                    $link.find('h4').removeClass('text-slate-200').addClass('text-brand');

                    const response = await fetch(`/learning/lecture/${lectureId}/data`);
                    const data = await response.json();

                    if (data.status === 'success') {
                        // 1. Update Player & Content
                        document.getElementById('learningPlayerWrapper').innerHTML = data.player_html;
                        
                        // 2. Update Q&A List
                        document.getElementById('discussionList').innerHTML = data.qna_html;

                        // 3. Sync Discussion Form (Critical for newly loaded content)
                        const discussionForm = document.getElementById('discussionForm');
                        if (discussionForm) {
                            discussionForm.querySelector('input[name="lecture_id"]').value = data.lecture.id;
                            discussionForm.querySelector('input[name="course_id"]').value = data.lecture.course_id;
                        }

                        // 3.1 Update Note List
                        const noteListWrapper = document.getElementById('noteListWrapper');
                        if (noteListWrapper && data.notes_html) {
                            noteListWrapper.innerHTML = data.notes_html;
                            
                            // Update note count
                            const noteCountDisplay = document.getElementById('noteCountDisplay');
                            const currentCount = noteListWrapper.querySelectorAll('.note-item').length;
                            if (noteCountDisplay) noteCountDisplay.innerText = `${currentCount} Ghi chú`;
                        }

                        // 3.2 Sync Note Form
                        const noteForm = document.getElementById('noteForm');
                        if (noteForm) {
                            noteForm.querySelector('input[name="lecture_id"]').value = data.lecture.id;
                            noteForm.querySelector('input[name="course_id"]').value = data.lecture.course_id;
                            // Reset form state
                            document.getElementById('noteFormContainer')?.classList.add('hidden');
                            document.getElementById('noteContent').value = '';
                        }

                        // 4. Update browser URL without reload
                        const newUrl = window.location.pathname.replace(/\/bai-hoc\/\d+/, `/bai-hoc/${lectureId}`);
                        window.history.pushState({ lectureId }, '', newUrl);

                        // 5. Update Title
                        document.title = `${data.lecture.title} - StackLearn`;
                        document.querySelector('h1').textContent = data.lecture.title;

                        // Force re-initialize scrollbars if needed
                        // Scroll to top of video/content
                        document.querySelector('.video-content').scrollTop = 0;
                    }
                } catch (err) {
                    console.error('Failed to load lecture:', err);
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể tải bài học.' });
                }
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(e) {
                if (e.state && e.state.lectureId) {
                    $(`.lecture-item-link[data-lecture-id="${e.state.lectureId}"]`).click();
                }
            });
        });
    </script>
</body>

</html>
