{{-- Course Preview Modal - Cyber/Pixel themed --}}
<div class="fixed inset-0 z-[9999] hidden" id="previewModal">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" id="previewModalBackdrop"></div>
    {{-- Modal dialog --}}
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-4xl mx-auto border-4 border-black pixel-shadow" id="previewModalContent">
            {{-- Modal header --}}
            <div class="flex items-start justify-between p-4 border-b border-slate-800 bg-cyber-dark">
                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-widest">Course Preview</span>
                    <h3 class="font-bold text-slate-100 text-lg">
                        {{ $course->course_name }}
                    </h3>
                </div>
                <button class="text-slate-400 hover:text-white transition-colors" id="previewModalClose"
                    aria-label="Close">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            {{-- Modal body - Video embed --}}
            <div class="relative bg-black" style="aspect-ratio: 16/9;">
                <input type="hidden" id="previewVideoUrl" value="{{ old('url', $course->video_url) }}">
                <iframe id="previewVideoFrame" class="w-full h-full absolute inset-0" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen style="display: none;"></iframe>
                {{-- Play icon placeholder (shown when no video) --}}
                <div id="previewVideoPlaceholder" class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-play-circle text-6xl text-brand/30"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #previewModalContent {
        animation: previewModalSlideIn 0.3s ease-out;
    }

    @keyframes previewModalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
</style>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('previewModal');
            var backdrop = document.getElementById('previewModalBackdrop');
            var closeBtn = document.getElementById('previewModalClose');
            var videoFrame = document.getElementById('previewVideoFrame');
            var videoPlaceholder = document.getElementById('previewVideoPlaceholder');
            var videoUrlInput = document.getElementById('previewVideoUrl');
            var videoUrl = videoUrlInput ? videoUrlInput.value : '';

            function extractYouTubeVideoID(url) {
                if (!url) return null;
                var regex =
                    /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]+)/;
                var match = url.match(regex);
                return match ? match[1] : null;
            }

            function openPreviewModal() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                // Load video
                console.log('Video URL:', videoUrl);
                var videoId = extractYouTubeVideoID(videoUrl);
                console.log('Extracted Video ID:', videoId);
                if (videoId) {
                    videoFrame.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1';
                    videoFrame.style.display = 'block';
                    videoPlaceholder.style.display = 'none';
                } else {
                    console.warn('Could not extract YouTube video ID from URL:', videoUrl);
                }
            }

            function closePreviewModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                // Stop video
                videoFrame.src = '';
                videoFrame.style.display = 'none';
                videoPlaceholder.style.display = 'flex';
            }

            // Open modal when clicking thumbnail triggers
            var triggers = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#previewModal"]');
            triggers.forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    openPreviewModal();
                });
            });

            // Close modal
            closeBtn.addEventListener('click', closePreviewModal);
            backdrop.addEventListener('click', closePreviewModal);

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closePreviewModal();
                }
            });
        });
    </script>
@endpush
