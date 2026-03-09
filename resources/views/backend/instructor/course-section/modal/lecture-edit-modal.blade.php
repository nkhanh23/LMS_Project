<div class="modal" id="course-edit-{{ $lecture->id }}">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cập nhật bài học</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="post" action="{{ route('instructor.lecture.update', $lecture->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="course_id" value="{{ $course->id }}" />
                    <input type="hidden" name="section_id" value="{{ $data->id }}" />
                    <div class="col-md-12">
                        <label for="lecture_title_{{ $lecture->id }}" class="form-label">Tiêu đề bài học</label>
                        <input type="text" class="form-control" name="lecture_title"
                            id="lecture_title_{{ $lecture->id }}" value="{{ $lecture->lecture_title }}"
                            placeholder="Nhập tiêu đề bài học" required>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="lecture_type_{{ $lecture->id }}" class="form-label">Loại bài học</label>
                        <select class="form-select lecture-type-select" name="type"
                            id="lecture_type_{{ $lecture->id }}" required>
                            <option value="video" {{ ($lecture->type ?? 'video') == 'video' ? 'selected' : '' }}>Video
                                (YouTube)</option>
                            <option value="text" {{ ($lecture->type ?? '') == 'text' ? 'selected' : '' }}>Văn bản
                                (Text)</option>
                            <option value="document" {{ ($lecture->type ?? '') == 'document' ? 'selected' : '' }}>Tài
                                liệu (PDF, Word...)</option>
                        </select>
                    </div>

                    <div class="video-fields"
                        style="{{ ($lecture->type ?? 'video') == 'video' ? 'display: block;' : 'display: none;' }}">
                        <div class="col-md-12 mt-3">
                            <label for="video_url_{{ $lecture->id }}" class="form-label">YouTube Video URL</label>
                            <input type="url" class="form-control video_url" name="url"
                                id="video_url_{{ $lecture->id }}" placeholder="Enter the YouTube video URL"
                                value="{{ old('url', $lecture->url) }}"
                                {{ ($lecture->type ?? 'video') == 'video' ? 'required' : '' }}>

                            <iframe class="videoPreview" id="videoPreview_{{ $lecture->id }}"
                                style="margin-top: 15px; width: 100%; height: 400px; display: none;" frameborder="0"
                                allowfullscreen></iframe>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="video_duration_{{ $lecture->id }}" class="form-label">Thời lượng video</label>
                            <input type="number" step="0.01" class="form-control" name="video_duration"
                                value="{{ old('video_duration', $lecture->video_duration) }}"
                                id="video_duration_{{ $lecture->id }}"
                                {{ ($lecture->type ?? 'video') == 'video' ? 'required' : '' }} />
                        </div>
                    </div>

                    <div class="document-fields"
                        style="{{ ($lecture->type ?? '') == 'document' ? 'display: block;' : 'display: none;' }}">
                        <div class="col-md-12 mt-3">
                            <label for="document_file_{{ $lecture->id }}" class="form-label">Upload tài liệu (PDF,
                                Word...)</label>
                            <input type="file" class="form-control" name="document_file"
                                id="document_file_{{ $lecture->id }}" accept=".pdf,.doc,.docx,.txt">
                            <small class="text-muted">Chỉ chọn file nếu bạn muốn thay đổi tài liệu hiện tại.</small>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 text-fields">
                        <label for="content_{{ $lecture->id }}"
                            class="form-label content-label">{{ ($lecture->type ?? '') == 'text' ? 'Nội dung bài học' : 'Mô tả bài học (Tùy chọn)' }}</label>
                        <textarea class="form-control editor" name="content" id="content_{{ $lecture->id }}"
                            {{ ($lecture->type ?? '') == 'text' ? 'required' : '' }}>{{ $lecture->content }}</textarea>
                    </div>


                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                    </div>
                </form>
            </div>



        </div>
    </div>
</div>


@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let modal = document.getElementById("course-edit-{{ $lecture->id }}");
            if (!modal) return;

            let videoInput = modal.querySelector("#video_url_{{ $lecture->id }}");
            let videoPreview = modal.querySelector("#videoPreview_{{ $lecture->id }}");

            let typeSelect = modal.querySelector("#lecture_type_{{ $lecture->id }}");
            let videoFields = modal.querySelector(".video-fields");
            let documentFields = modal.querySelector(".document-fields");
            let contentLabel = modal.querySelector(".content-label");
            let videoDurationInput = modal.querySelector("#video_duration_{{ $lecture->id }}");
            let documentFileInput = modal.querySelector("#document_file_{{ $lecture->id }}");
            let contentInput = modal.querySelector("textarea[name='content']");

            function toggleLectureFields() {
                let type = typeSelect.value;
                if (type === 'video') {
                    videoFields.style.display = 'block';
                    documentFields.style.display = 'none';

                    videoInput.setAttribute('required', 'required');
                    videoDurationInput.setAttribute('required', 'required');
                    if (documentFileInput) documentFileInput.removeAttribute('required');
                    contentInput.removeAttribute('required');
                    if (contentLabel) contentLabel.innerText = "Mô tả bài học (Tùy chọn)";
                } else if (type === 'document') {
                    videoFields.style.display = 'none';
                    documentFields.style.display = 'block';

                    videoInput.removeAttribute('required');
                    videoDurationInput.removeAttribute('required');
                    if (documentFileInput) documentFileInput.removeAttribute('required');
                    contentInput.removeAttribute('required');
                    if (contentLabel) contentLabel.innerText = "Mô tả bài học (Tùy chọn)";
                } else if (type === 'text') {
                    videoFields.style.display = 'none';
                    documentFields.style.display = 'none';

                    videoInput.removeAttribute('required');
                    videoDurationInput.removeAttribute('required');
                    if (documentFileInput) documentFileInput.removeAttribute('required');
                    contentInput.setAttribute('required', 'required');
                    if (contentLabel) contentLabel.innerText = "Nội dung bài học";
                }
            }

            modal.addEventListener("shown.bs.modal", function() {
                toggleLectureFields(); // trigger on load
                if (typeSelect) {
                    typeSelect.addEventListener("change", toggleLectureFields);
                }

                videoInput.addEventListener("input", function() {
                    let url = videoInput.value;
                    let videoId = extractYouTubeVideoID(url);

                    if (videoId) {
                        videoPreview.src = `https://www.youtube.com/embed/${videoId}`;
                        videoPreview.style.display = "block";
                    } else {
                        videoPreview.src = "";
                        videoPreview.style.display = "none";
                    }
                });

                // Trigger preview if already has value
                if (videoInput.value.trim() !== "") {
                    let videoId = extractYouTubeVideoID(videoInput.value);
                    if (videoId) {
                        videoPreview.src = `https://www.youtube.com/embed/${videoId}`;
                        videoPreview.style.display = "block";
                    }
                }
            });

            modal.addEventListener("hidden.bs.modal", function() {
                videoPreview.src = ""; // Reset video when modal closes
                videoPreview.style.display = "none";
            });

            function extractYouTubeVideoID(url) {
                let regex =
                    /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
                let match = url.match(regex);
                return match ? match[1] : null;
            }
        });
    </script>
@endpush
