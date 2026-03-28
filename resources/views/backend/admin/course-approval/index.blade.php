@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!--breadcrumb-->
            @include('backend.section.breadcrumb', [
                'title' => 'Phê duyệt khóa học',
                'sub_title' => 'Danh sách khóa học',
            ])
            <!--end breadcrumb-->

            <hr />

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center mb-3">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('admin.course-approvals.index') }}" class="d-flex g-2">
                                        <select name="course_status" class="form-select me-2" style="width: 200px;">
                                            <option value="">-- Tất cả trạng thái --</option>
                                            <option value="draft" {{ request('course_status') == 'draft' ? 'selected' : '' }}>
                                                Bản nháp</option>
                                            <option value="pending_review"
                                                {{ request('course_status') == 'pending_review' ? 'selected' : '' }}>Chờ
                                                duyệt</option>
                                            <option value="published"
                                                {{ request('course_status') == 'published' ? 'selected' : '' }}>Đã xuất bản
                                            </option>
                                            <option value="rejected"
                                                {{ request('course_status') == 'rejected' ? 'selected' : '' }}>Bị từ chối
                                            </option>
                                            <option value="hidden" {{ request('course_status') == 'hidden' ? 'selected' : '' }}>
                                                Bị ẩn</option>
                                        </select>
                                        <button class="btn btn-primary">Lọc</button>
                                    </form>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="d-inline-block border p-2 rounded bg-light" style="font-size: 0.75rem;">
                                        <strong>Rủi ro giảng viên:</strong>
                                        <span class="badge bg-success ms-2">Thấp (0-29)</span>
                                        <span class="badge bg-info text-dark ms-1">Trung bình (30-59)</span>
                                        <span class="badge bg-warning text-dark ms-1">Cao (60-99)</span>
                                        <span class="badge bg-danger ms-1">Rất cao (>=100)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Khóa học</th>
                                            <th>Giảng viên</th>
                                            <th>Trạng thái</th>
                                            <th>Ghi chú đánh giá</th>
                                            <th>Ngày gửi</th>
                                            <th width="260">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($courses as $course)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $course->course_name }}</td>
                                                <td>
                                                    {{ $course->user->name ?? '---' }}
                                                    @if(isset($course->user))
                                                        <br>
                                                        @php $risk = $course->user->risk_level; @endphp
                                                        <span class="badge {{ $risk['class'] }}" style="font-size: 0.7rem;">
                                                            Risk: {{ $risk['score'] }} ({{ $risk['label'] }})
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $course->approval_status }}</span>
                                                </td>
                                                <td>{{ $course->approval_note ?? '---' }}</td>
                                                <td>{{ $course->submitted_for_review_at ?? '---' }}</td>
                                                <td>
                                                    <div class="d-flex flex-column gap-2">
                                                        <a href="{{ route('admin.course-approvals.show', $course->id) }}"
                                                            class="btn btn-primary btn-sm w-100">
                                                            Xem chi tiết
                                                        </a>

                                                        @if (in_array($course->approval_status, ['pending_review', 'rejected', 'hidden']))
                                                            <form method="POST"
                                                                action="{{ route('admin.course-approvals.publish', $course->id) }}">
                                                                @csrf
                                                                <button class="btn btn-success btn-sm w-100">Xuất
                                                                    bản</button>
                                                            </form>
                                                        @endif

                                                        @if (in_array($course->approval_status, ['pending_review', 'draft']))
                                                            <button type="button" class="btn btn-danger btn-sm w-100"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#rejectCourseModal{{ $course->id }}">
                                                                Từ chối
                                                            </button>
                                                        @endif

                                                        @if ($course->approval_status === 'published')
                                                            <form method="POST"
                                                                action="{{ route('admin.course-approvals.hide', $course->id) }}">
                                                                @csrf
                                                                <button class="btn btn-warning btn-sm w-100">Ẩn</button>
                                                            </form>
                                                        @endif
                                                    </div>

                                                    <div class="modal fade" id="rejectCourseModal{{ $course->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form method="POST"
                                                                    action="{{ route('admin.course-approvals.reject', $course->id) }}">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Từ chối Khóa học</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <label class="form-label">Ghi chú đánh giá</label>
                                                                        <textarea name="review_note" class="form-control" rows="4" required></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Đóng</button>
                                                                        <button type="submit" class="btn btn-danger">Từ
                                                                            chối</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có khóa học nào</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{ $courses->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
