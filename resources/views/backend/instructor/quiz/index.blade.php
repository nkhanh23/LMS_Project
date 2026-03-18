@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Quiz',
            'sub_title' => 'Quản lý Quiz',
        ])
        <!--end breadcrumb-->

        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-uppercase">Danh sách Quiz</h6>
            <a href="{{ route('instructor.course.index') }}" class="btn btn-primary px-4">Quay lại khóa học</a>
        </div>
        <hr />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('instructor.quiz.index') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Khóa học</label>
                        <select name="course_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Tất cả khóa học</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ (string) $courseId === (string) $course->id ? 'selected' : '' }}>
                                    {{ $course->course_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Chương</label>
                        <select name="section_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Tất cả chương</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ (string) $sectionId === (string) $section->id ? 'selected' : '' }}>
                                    {{ $section->section_title ?? 'Chương #' . $section->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bài giảng</label>
                        <select name="lecture_id" class="form-select">
                            <option value="">Tất cả bài giảng</option>
                            @foreach ($lectures as $lecture)
                                <option value="{{ $lecture->id }}"
                                    {{ (string) $lectureId === (string) $lecture->id ? 'selected' : '' }}>
                                    {{ $lecture->lecture_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2 px-4">Lọc</button>
                        <a href="{{ route('instructor.quiz.index') }}" class="btn btn-secondary px-4">Xóa lọc</a>
                    </div>
                </form>

                @if ($quizzes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tiêu đề Quiz</th>
                                    <th>Khóa học</th>
                                    <th>Chương</th>
                                    <th>Bài giảng</th>
                                    <th>Câu hỏi</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizzes as $index => $lecture)
                                    <tr>
                                        <td>{{ $quizzes->firstItem() + $index }}</td>
                                        <td>{{ $lecture->quiz->title ?? $lecture->lecture_title }}</td>
                                        <td>{{ $lecture->course->course_name }}</td>
                                        <td>{{ $lecture->section->section_title ?? '-' }}</td>
                                        <td>{{ $lecture->lecture_title }}</td>
                                        <td>{{ $lecture->quiz->questions->count() ?? 0 }}</td>
                                        <td>
                                            @if ($lecture->quiz->is_active ?? false)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Bản nháp</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('instructor.quiz.edit', $lecture->id) }}"
                                                class="btn btn-sm btn-primary px-3">
                                                Ch chỉnh sửa Quiz
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $quizzes->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        Không tìm thấy Quiz nào.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
