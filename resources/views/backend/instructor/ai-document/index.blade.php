@extends('backend.instructor.master')

@section('content')
    @include('backend.section.breadcrumb', [
        'title' => 'AI Documents',
        'sub_title' => 'Knowledge Base Documents',
    ])

    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- Form upload tài liệu KB --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thêm tài liệu KB</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('instructor.ai-documents.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Khóa học</label>
                            <select name="course_id" class="form-control" required>
                                <option value="">-- Chọn khóa học --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bài học</label>
                            <select name="lecture_id" class="form-control">
                                <option value="">-- Không gắn bài học cụ thể --</option>
                                @foreach ($lectures as $lecture)
                                    <option value="{{ $lecture->id }}" data-course-id="{{ $lecture->course_id }}"
                                        {{ old('lecture_id') == $lecture->id ? 'selected' : '' }}>
                                        {{ $lecture->lecture_title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Có thể để trống nếu tài liệu áp dụng cho cả khóa học.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload file PDF / DOCX / TXT / MD</label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.docx,.txt,.md">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hoặc nhập content thủ công</label>
                            <textarea name="content" class="form-control" rows="8">{{ old('content') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngôn ngữ</label>
                            <input type="text" name="language" class="form-control" value="{{ old('language', 'vi') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Lưu tài liệu AI
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Danh sách tài liệu --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách tài liệu KB</h5>
                </div>
                <div class="card-body">
                    @if ($documents->count())
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tiêu đề</th>
                                        <th>Loại</th>
                                        <th>Trạng thái</th>
                                        <th>Chunks</th>
                                        <th width="180">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $doc)
                                        <tr>
                                            <td>{{ $doc->id }}</td>
                                            <td>
                                                <strong>{{ $doc->title }}</strong><br>
                                                <small class="text-muted">
                                                    Course: {{ $doc->course_id }}
                                                    @if ($doc->lecture_id)
                                                        | Lecture: {{ $doc->lecture_id }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td>{{ $doc->source_type }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $doc->index_status }}
                                                </span>
                                                @if ($doc->index_error)
                                                    <div class="small text-danger mt-1">
                                                        {{ $doc->index_error }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $doc->chunks->count() }}</td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ route('instructor.ai-documents.reindex', $doc->id) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">
                                                        Re-index
                                                    </button>
                                                </form>

                                                <form method="POST"
                                                    action="{{ route('instructor.ai-documents.destroy', $doc->id) }}"
                                                    class="d-inline" onsubmit="return confirm('Xóa tài liệu này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $documents->links() }}
                    @else
                        <div class="alert alert-info mb-0">
                            Chưa có tài liệu KB nào.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const courseSelect = document.querySelector('select[name="course_id"]');
            const lectureSelect = document.querySelector('select[name="lecture_id"]');

            if (!courseSelect || !lectureSelect) return;

            const allLectureOptions = Array.from(lectureSelect.querySelectorAll('option'));

            function filterLectureOptions() {
                const courseId = courseSelect.value;

                lectureSelect.innerHTML = '';

                allLectureOptions.forEach(option => {
                    if (option.value === '') {
                        lectureSelect.appendChild(option.cloneNode(true));
                        return;
                    }

                    if (!courseId || option.dataset.courseId === courseId) {
                        lectureSelect.appendChild(option.cloneNode(true));
                    }
                });
            }

            courseSelect.addEventListener('change', filterLectureOptions);
            filterLectureOptions();
        });
    </script>
@endsection
