<div class="modal fade" id="course-{{ $data->id }}">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Thêm bài học</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="post" action="{{ route('instructor.lecture.store') }}" enctype="multipart/form-data"
                    class="lecture-create-form">
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
                            <option value="video">Link YouTube</option>
                            <option value="r2_video">Upload Video (Cloudflare R2)</option>
                            <option value="text">Văn bản (Text)</option>
                            <option value="document">Tài liệu (PDF, Word...)</option>
                            <option value="quiz">Quiz</option>
                        </select>
                    </div>

                    <div class="video-fields">
                        <div class="col-md-12 mt-3">
                            <label for="video_url_{{ $data->id }}" class="form-label">YouTube Video URL</label>
                            <input type="url" class="form-control" name="url" id="video_url_{{ $data->id }}"
                                placeholder="Nhập URL video YouTube">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="video_duration_{{ $data->id }}" class="form-label">Thời lượng video
                                (Phút)</label>
                            <input type="number" step="0.01" class="form-control" name="video_duration"
                                value="{{ old('video_duration') }}" id="video_duration_{{ $data->id }}" />
                        </div>
                    </div>

                    <div class="form-group mt-3 lecture-r2-upload" style="display: none;">
                        <label class="form-label">Tải lên Video (MP4/MOV)</label>
                        <input type="file" class="form-control video-file-r2" accept="video/mp4,video/x-m4v,video/*">

                        <input type="hidden" name="r2_video_key" class="r2-video-key">

                        <div class="progress mt-2 upload-progress-container" style="height: 20px; display: none;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success upload-progress-bar"
                                role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100">0%</div>
                        </div>

                        {{-- Thông báo upload thành công --}}
                        <div class="upload-success-msg mt-2" style="display: none;">
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Video đã tải lên thành
                                công! Xem lại bên dưới trước khi lưu.</span>
                        </div>

                        {{-- Video preview sau khi upload --}}
                        <video class="r2-video-preview mt-3" controls
                            style="display: none; width: 100%; max-height: 400px; border-radius: 8px; background: #000;"></video>
                    </div>

                    <div class="document-fields" style="display: none;">
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Tải lên tài liệu (R2)</label>
                            <input type="file" class="form-control document-file-r2"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.txt">

                            <input type="hidden" name="r2_document_key" class="r2-document-key">
                            <input type="hidden" name="file_name" class="document-original-name">
                            <input type="hidden" name="mime_type" class="document-mime-type">
                            <input type="hidden" name="file_size" class="document-file-size">

                            <div class="progress mt-2 document-upload-progress-container"
                                style="height: 20px; display: none;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success document-upload-progress-bar"
                                    role="progressbar" style="width: 0%;">0%</div>
                            </div>

                            <div class="document-upload-success-msg mt-2" style="display:none;">
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Tài liệu đã tải lên Cloudflare R2 thành công!
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 text-fields">
                        <label for="content_{{ $data->id }}" class="form-label content-label">Nội dung / Mô
                            tả</label>
                        <textarea class="form-control editor" name="content" id="content_{{ $data->id }}"></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 btn-submit-lecture">Lưu bài học</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
