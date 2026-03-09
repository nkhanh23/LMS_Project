<div class="modal" id="course-{{ $data->id }}">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Thêm bài học</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="post" action="{{ route('instructor.lecture.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}" />
                    <input type="hidden" name="section_id" value="{{ $data->id }}" />
                    <div class="col-md-12">
                        <label for="lecture_title_{{ $data->id }}" class="form-label">Tiêu đề bài học</label>
                        <input type="text" class="form-control" name="lecture_title"
                            id="lecture_title_{{ $data->id }}" placeholder="Nhập tiêu đề bài học" required>
                    </div>



                    <div class="col-md-12 mt-3">
                        <label for="lecture_type_{{ $data->id }}" class="form-label">Loại bài học</label>
                        <select class="form-select lecture-type-select" name="type"
                            id="lecture_type_{{ $data->id }}" required>
                            <option value="video">Video (YouTube)</option>
                            <option value="text">Văn bản (Text)</option>
                            <option value="document">Tài liệu (PDF, Word...)</option>
                        </select>
                    </div>

                    <div class="video-fields">
                        <div class="col-md-12 mt-3">
                            <label for="video_url_{{ $data->id }}" class="form-label">YouTube Video URL</label>
                            <input type="url" class="form-control" name="url" id="video_url_{{ $data->id }}"
                                placeholder="Nhập URL video YouTube" value="{{ old('url') }}" required>
                            <iframe id="videoPreview_{{ $data->id }}"
                                style="margin-top: 15px; display: none; width: 100%; height: 400px;" frameborder="0"
                                allowfullscreen></iframe>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="video_duration_{{ $data->id }}" class="form-label">Thời lượng video</label>
                            <input type="number" step="0.01" class="form-control" name="video_duration"
                                value="{{ old('video_duration') }}" id="video_duration_{{ $data->id }}"
                                required />
                        </div>
                    </div>

                    <div class="document-fields" style="display: none;">
                        <div class="col-md-12 mt-3">
                            <label for="document_file_{{ $data->id }}" class="form-label">Upload tài liệu (PDF,
                                Word...)</label>
                            <input type="file" class="form-control" name="document_file"
                                id="document_file_{{ $data->id }}" accept=".pdf,.doc,.docx,.txt">
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 text-fields">
                        <label for="content_{{ $data->id }}" class="form-label content-label">Nội dung / Mô
                            tả</label>
                        <textarea class="form-control editor" name="content" id="content_{{ $data->id }}" required></textarea>
                    </div>


                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary w-100">Thêm bài học</button>
                    </div>
                </form>
            </div>



        </div>
    </div>
</div>



@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let modal = document.getElementById("course-{{ $data->id }}");
            let videoInput = modal.querySelector("#video_url_{{ $data->id }}");
            let videoPreview = modal.querySelector("#videoPreview_{{ $data->id }}");

            let typeSelect = modal.querySelector(".lecture-type-select");
            let videoFields = modal.querySelector(".video-fields");
            let documentFields = modal.querySelector(".document-fields");
            let contentLabel = modal.querySelector(".content-label");
            let videoDurationInput = modal.querySelector("#video_duration_{{ $data->id }}");
            let documentFileInput = modal.querySelector("#document_file_{{ $data->id }}");
            let contentInput = modal.querySelector("textarea[name='content']");

            function toggleLectureFields() {
                let type = typeSelect.value;
                if (type === 'video') {
                    videoFields.style.display = 'block';
                    documentFields.style.display = 'none';

                    videoInput.setAttribute('required', 'required');
                    videoDurationInput.setAttribute('required', 'required');
                    documentFileInput.removeAttribute('required');
                    contentInput.removeAttribute('required');
                    if (contentLabel) contentLabel.innerText = "Mô tả bài học (Tùy chọn)";
                } else if (type === 'document') {
                    videoFields.style.display = 'none';
                    documentFields.style.display = 'block';

                    videoInput.removeAttribute('required');
                    videoDurationInput.removeAttribute('required');
                    documentFileInput.setAttribute('required', 'required');
                    contentInput.removeAttribute('required');
                    if (contentLabel) contentLabel.innerText = "Mô tả bài học (Tùy chọn)";
                } else if (type === 'text') {
                    videoFields.style.display = 'none';
                    documentFields.style.display = 'none';

                    videoInput.removeAttribute('required');
                    videoDurationInput.removeAttribute('required');
                    documentFileInput.removeAttribute('required');
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
