<div class="modal fade" id="course-edit-{{ $lecture->id }}">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Cập nhật bài học</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- Main Update Form --}}
                <form method="post" action="{{ route('instructor.lecture.update', $lecture->id) }}"
                    enctype="multipart/form-data" class="lecture-edit-form" id="lecture-update-form-{{ $lecture->id }}">
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
                            <option value="r2_video" {{ ($lecture->type ?? '') == 'r2_video' ? 'selected' : '' }}>
                                Upload Video (Cloudflare R2)</option>
                            <option value="text" {{ ($lecture->type ?? '') == 'text' ? 'selected' : '' }}>Văn bản
                                (Text)</option>
                            <option value="document" {{ ($lecture->type ?? '') == 'document' ? 'selected' : '' }}>Tài
                                liệu (PDF, Word...)</option>
                            <option value="quiz" {{ ($lecture->type ?? '') == 'quiz' ? 'selected' : '' }}>Bài tập
                                (Quiz)</option>
                        </select>
                    </div>

                    {{-- YouTube fields --}}
                    <div class="video-fields"
                        style="{{ ($lecture->type ?? 'video') == 'video' ? 'display: block;' : 'display: none;' }}">
                        <div class="col-md-12 mt-3">
                            <label for="video_url_{{ $lecture->id }}" class="form-label">YouTube Video URL</label>
                            <input type="url" class="form-control video_url" name="url"
                                id="video_url_{{ $lecture->id }}" placeholder="Nhập URL video YouTube"
                                value="{{ old('url', $lecture->url) }}">

                            <iframe class="videoPreview" id="videoPreview_{{ $lecture->id }}"
                                style="margin-top: 15px; width: 100%; height: 400px; display: none;" frameborder="0"
                                allowfullscreen></iframe>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="video_duration_{{ $lecture->id }}" class="form-label">Thời lượng video
                                (Phút)</label>
                            <input type="number" step="0.01" class="form-control" name="video_duration"
                                value="{{ old('video_duration', $lecture->video_duration) }}"
                                id="video_duration_{{ $lecture->id }}" />
                        </div>
                    </div>

                    {{-- R2 Video Upload fields --}}
                    <div class="form-group mt-3 lecture-r2-upload"
                        style="{{ ($lecture->type ?? '') == 'r2_video' ? 'display: block;' : 'display: none;' }}">
                        <label class="form-label">Tải lên Video mới (MP4/MOV)</label>
                        <input type="file" class="form-control video-file-r2" accept="video/mp4,video/x-m4v,video/*">
                        <small class="text-muted">Chỉ chọn file nếu bạn muốn thay đổi video hiện tại.</small>

                        <input type="hidden" name="r2_video_key" class="r2-video-key">

                        <div class="progress mt-2 upload-progress-container" style="height: 20px; display: none;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success upload-progress-bar"
                                role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100">0%</div>
                        </div>

                        {{-- Thông báo upload thành công --}}
                        <div class="upload-success-msg mt-2" style="display: none;">
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Video mới đã tải lên thành
                                công! Xem lại bên dưới trước khi cập nhật.</span>
                        </div>

                        {{-- Video preview --}}
                        <video class="r2-video-preview mt-3" controls
                            style="display: {{ (($lecture->type ?? '') == 'r2_video' && $lecture->url) ? 'block' : 'none' }}; width: 100%; max-height: 400px; border-radius: 8px; background: #000;"
                            @if(($lecture->type ?? '') == 'r2_video' && $lecture->url) src="{{ config('filesystems.disks.r2.url') }}/{{ $lecture->url }}" @endif>
                        </video>

                        @if (($lecture->type ?? '') == 'r2_video' && $lecture->url)
                            <div class="mt-2">
                                <small class="text-success"><i class="bi bi-check-circle"></i> Video hiện tại đã được
                                    tải lên ({{ $lecture->url }}).</small>
                            </div>
                        @endif
                    </div>

                    {{-- Document fields --}}
                    <div class="document-fields"
                        style="{{ ($lecture->type ?? '') == 'document' ? 'display: block;' : 'display: none;' }}">
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

                            @if (($lecture->type ?? '') == 'document' && $lecture->url)
                                <div class="mt-2 text-info">
                                    <small><i class="bi bi-file-earmark-check"></i> Tài liệu hiện tại: {{ $lecture->file_name ?? $lecture->url }}</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Content / Description --}}
                    <div class="col-md-12 mt-3 text-fields">
                        <label for="content_{{ $lecture->id }}"
                            class="form-label content-label">{{ ($lecture->type ?? '') == 'text' ? 'Nội dung bài học' : 'Mô tả bài học (Tùy chọn)' }}</label>
                        <textarea class="form-control editor" name="content" id="content_{{ $lecture->id }}">{{ $lecture->content }}</textarea>
                    </div>

                    @if(isset($lecture) && $lecture->type === 'quiz')
                        <div class="col-md-12 mt-3">
                            <a href="{{ route('instructor.quiz.edit', $lecture->id) }}" class="btn btn-primary w-100">
                                <i class="bi bi-pencil-square"></i> Edit Quiz
                            </a>
                        </div>
                    @endif
                </form>

                {{-- Separate Transcript Generation Form --}}
                @if(in_array($lecture->type, ['video', 'r2_video']))
                    <form method="POST"
                          action="{{ route('instructor.transcript.generate', $lecture->id) }}"
                          id="transcript-generate-form-{{ $lecture->id }}">
                        @csrf
                    </form>
                @endif

                {{-- ============================================ --}}
                {{-- TRANSCRIPT MANAGEMENT PANEL --}}
                {{-- ============================================ --}}
                @if(in_array($lecture->type, ['video', 'r2_video']))
                <div class="mt-4 border-top pt-3">
                    <h6 class="mb-3"><i class="bi bi-file-text"></i> Quản lý Transcript</h6>

                    {{-- Tab buttons --}}
                    <ul class="nav nav-tabs nav-tabs-sm" id="transcriptTabs-{{ $lecture->id }}" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#tab-view-{{ $lecture->id }}" type="button">
                                <i class="bi bi-eye"></i> Xem / Sửa
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#tab-auto-{{ $lecture->id }}" type="button">
                                <i class="bi bi-mic-fill"></i> Tự động
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#tab-manual-{{ $lecture->id }}" type="button">
                                <i class="bi bi-upload"></i> Thủ công
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content border border-top-0 rounded-bottom p-3" id="transcriptTabContent-{{ $lecture->id }}">

                        {{-- ===== TAB 1: Xem / Sửa ===== --}}
                        <div class="tab-pane fade show active" id="tab-view-{{ $lecture->id }}" role="tabpanel">
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary btn-load-transcript mb-3"
                                    data-lecture-id="{{ $lecture->id }}"
                                    data-url="{{ route('instructor.transcript.get', $lecture->id) }}">
                                <i class="bi bi-arrow-clockwise"></i> Tải Transcript
                            </button>

                            <div class="transcript-panel" id="transcript-panel-{{ $lecture->id }}" style="display: none;">
                                {{-- Info Bar --}}
                                <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                    <div class="transcript-meta small text-muted" id="transcript-meta-{{ $lecture->id }}"></div>
                                    <div class="d-flex gap-2">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary btn-toggle-edit-transcript"
                                                id="btn-edit-{{ $lecture->id }}"
                                                data-lecture-id="{{ $lecture->id }}"
                                                style="display: none;">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-success btn-save-transcript"
                                                id="btn-save-{{ $lecture->id }}"
                                                data-lecture-id="{{ $lecture->id }}"
                                                data-url="{{ route('instructor.transcript.update', $lecture->id) }}"
                                                style="display: none;">
                                            <i class="bi bi-check-lg"></i> Lưu
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary btn-cancel-edit-transcript"
                                                id="btn-cancel-{{ $lecture->id }}"
                                                data-lecture-id="{{ $lecture->id }}"
                                                style="display: none;">
                                            Hủy
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-warning btn-reindex-transcript"
                                                data-lecture-id="{{ $lecture->id }}"
                                                data-url="{{ route('instructor.transcript.reindex', $lecture->id) }}">
                                            <i class="bi bi-arrow-repeat"></i> Re-index
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger btn-delete-transcript"
                                                data-lecture-id="{{ $lecture->id }}"
                                                data-url="{{ route('instructor.transcript.delete', $lecture->id) }}">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </div>
                                </div>

                                {{-- Read-only view --}}
                                <div class="transcript-view-content border rounded p-3"
                                     id="transcript-view-{{ $lecture->id }}"
                                     style="max-height: 300px; overflow-y: auto; background: #f8f9fa; font-size: 14px; line-height: 1.7; white-space: pre-wrap; word-wrap: break-word;">
                                </div>

                                {{-- Editable textarea --}}
                                <textarea class="form-control transcript-edit-content"
                                          id="transcript-edit-{{ $lecture->id }}"
                                          rows="12"
                                          style="display: none; font-size: 14px; line-height: 1.7;"></textarea>

                                <div class="transcript-save-feedback mt-2" id="transcript-feedback-{{ $lecture->id }}"></div>
                            </div>

                            {{-- No transcript message --}}
                            <div class="transcript-empty-msg text-muted small" id="transcript-empty-{{ $lecture->id }}" style="display: none;">
                                <i class="bi bi-info-circle"></i> Chưa có transcript. Hãy tạo tự động hoặc thêm thủ công.
                            </div>
                        </div>

                        {{-- ===== TAB 2: Tự động generate ===== --}}
                        <div class="tab-pane fade" id="tab-auto-{{ $lecture->id }}" role="tabpanel">
                            <p class="small text-muted mb-3">
                                <i class="bi bi-info-circle"></i>
                                Hệ thống sẽ tự động lấy phụ đề từ YouTube (nếu có) hoặc dùng AI để tạo transcript từ audio.
                            </p>
                            <form method="POST"
                                  action="{{ route('instructor.transcript.generate', $lecture->id) }}"
                                  id="transcript-generate-form-{{ $lecture->id }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100"
                                        onclick="return confirm('Tạo transcript tự động cho bài học này?')">
                                    <i class="bi bi-mic-fill"></i> Generate Transcript
                                </button>
                            </form>

                            @if($lecture->transcriptJobs()->latest()->exists())
                                @php $latestTranscriptJob = $lecture->transcriptJobs()->latest()->first(); @endphp
                                <div class="small mt-3">
                                    Trạng thái:
                                    <span class="badge {{ $latestTranscriptJob->status === 'done' ? 'bg-success' : ($latestTranscriptJob->status === 'failed' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $latestTranscriptJob->status }}
                                    </span>
                                    @if($latestTranscriptJob->error_message)
                                        <div class="text-danger small mt-1">{{ $latestTranscriptJob->error_message }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- ===== TAB 3: Thêm thủ công ===== --}}
                        <div class="tab-pane fade" id="tab-manual-{{ $lecture->id }}" role="tabpanel">
                            <form method="POST"
                                  action="{{ route('instructor.transcript.store-manual', $lecture->id) }}"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Upload file (PDF, DOCX, TXT)</label>
                                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.txt">
                                    <small class="text-muted">Tối đa 10MB. Nội dung sẽ được trích xuất tự động.</small>
                                </div>

                                <div class="text-center text-muted my-2">
                                    <small>— hoặc —</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nhập nội dung thủ công</label>
                                    <textarea name="manual_content" class="form-control" rows="6"
                                              placeholder="Dán hoặc nhập nội dung transcript tại đây..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-circle"></i> Thêm Transcript
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
                @endif

                {{-- Action Buttons Area --}}
                <div class="mt-4 border-top pt-4">
                    <div class="d-grid gap-2">
                        <button type="submit" form="lecture-update-form-{{ $lecture->id }}" class="btn btn-primary w-100 btn-submit-lecture">
                            <i class="bi bi-save"></i> Cập nhật bài học
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

