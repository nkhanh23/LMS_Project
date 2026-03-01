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
                        <label for="lecture_title" class="form-label">Tiêu đề bài học</label>
                        <input type="text" class="form-control" name="lecture_title" id="lecture-title"
                            value="{{ $lecture->lecture_title }}" placeholder="Nhập tiêu đề bài học" required>
                    </div>



                    <div class="col-md-12 mt-3">
                        <input type="url" class="form-control video_url" name="url"
                            placeholder="Enter the YouTube video URL" value="{{ old('url', $lecture->url) }}" required>

                        <iframe class="videoPreview"
                            style="margin-top: 15px; width: 100%; height: 400px; display: none;" frameborder="0"
                            allowfullscreen></iframe>

                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="video_duration" class="form-label">Thời lượng video</label>

                        <input type="number" step="0.01" class="form-control" name="video_duration"
                            value="{{ old('video_duration', $lecture->video_duration) }}" id="video_duration"
                            required />
                    </div>




                    <div class="col-md-12 mt-3">
                        <label for="content" class="form-label">Nội dung bài học</label>

                        <textarea class="form-control editor" name="content" required>{{ $lecture->content }}</textarea>
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
            let videoInputs = document.querySelectorAll(".video_url");

            videoInputs.forEach(videoInput => {
                let videoPreview = videoInput.closest('.col-md-12').querySelector(
                    ".videoPreview");

                function extractYouTubeVideoID(url) {
                    let regex =
                        /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
                    let match = url.match(regex);
                    return match ? match[1] : null;
                }

                function updateVideoPreview() {
                    let url = videoInput.value;
                    let videoId = extractYouTubeVideoID(url);

                    if (videoId) {
                        videoPreview.src = `https://www.youtube.com/embed/${videoId}`;
                        videoPreview.style.display = "block";
                    } else {
                        videoPreview.src = "";
                        videoPreview.style.display = "none";
                    }
                }

                videoInput.addEventListener("input", updateVideoPreview);

                if (videoInput.value.trim() !== "") {
                    updateVideoPreview();
                }
            });
        });
    </script>
@endpush
