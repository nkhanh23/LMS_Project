@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-12">
                    <h4>Approval Center</h4>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                {{-- Instructor Approval --}}
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Instructor Approval</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.approval-center.index') }}" class="row g-2 mb-3">
                                <div class="col-md-3">
                                    <select name="instructor_status" class="form-select">
                                        <option value="">-- Tất cả trạng thái --</option>
                                        <option value="pending"
                                            {{ request('instructor_status') == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="approved"
                                            {{ request('instructor_status') == 'approved' ? 'selected' : '' }}>Approved
                                        </option>
                                        <option value="suspended"
                                            {{ request('instructor_status') == 'suspended' ? 'selected' : '' }}>Suspended
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary">Lọc</button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Instructor</th>
                                            <th>Status</th>
                                            <th>Review note</th>
                                            <th>Reviewed at</th>
                                            <th width="240">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($instructors as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $item->name }}</strong><br>
                                                    <small>{{ $item->email }}</small>
                                                </td>
                                                <td>
                                                    @if ($item->instructor_approval_status === 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($item->instructor_approval_status === 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($item->instructor_approval_status === 'suspended')
                                                        <span class="badge bg-danger">Suspended</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->instructor_review_note ?? '---' }}</td>
                                                <td>{{ $item->instructor_reviewed_at ?? '---' }}</td>
                                                <td>
                                                    <div class="d-flex flex-column gap-2">
                                                        @if ($item->instructor_approval_status !== 'approved')
                                                            <form method="POST"
                                                                action="{{ route('admin.approval-center.instructors.approve', $item->id) }}">
                                                                @csrf
                                                                <button
                                                                    class="btn btn-success btn-sm w-100">Approve</button>
                                                            </form>
                                                        @endif

                                                        <button type="button" class="btn btn-danger btn-sm w-100"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#suspendInstructorModal{{ $item->id }}">
                                                            Suspend
                                                        </button>
                                                    </div>

                                                    <div class="modal fade" id="suspendInstructorModal{{ $item->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form method="POST"
                                                                    action="{{ route('admin.approval-center.instructors.suspend', $item->id) }}">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Suspend Instructor</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <label class="form-label">Review note</label>
                                                                        <textarea name="review_note" class="form-control" rows="4" required></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Đóng</button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Suspend</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Không có instructor</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{ $instructors->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

                {{-- Course Approval --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Course Approval</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.approval-center.index') }}" class="row g-2 mb-3">
                                <div class="col-md-3">
                                    <select name="course_status" class="form-select">
                                        <option value="">-- Tất cả trạng thái --</option>
                                        <option value="draft" {{ request('course_status') == 'draft' ? 'selected' : '' }}>
                                            Draft</option>
                                        <option value="pending_review"
                                            {{ request('course_status') == 'pending_review' ? 'selected' : '' }}>Pending
                                            Review</option>
                                        <option value="published"
                                            {{ request('course_status') == 'published' ? 'selected' : '' }}>Published
                                        </option>
                                        <option value="rejected"
                                            {{ request('course_status') == 'rejected' ? 'selected' : '' }}>Rejected
                                        </option>
                                        <option value="hidden"
                                            {{ request('course_status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary">Lọc</button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Course</th>
                                            <th>Instructor</th>
                                            <th>Status</th>
                                            <th>Review note</th>
                                            <th>Submitted at</th>
                                            <th width="260">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($courses as $course)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $course->course_name }}</td>
                                                <td>{{ $course->user->name ?? '---' }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $course->approval_status }}</span>
                                                </td>
                                                <td>{{ $course->approval_note ?? '---' }}</td>
                                                <td>{{ $course->submitted_for_review_at ?? '---' }}</td>
                                                <td>
                                                    <div class="d-flex flex-column gap-2">
                                                        @if (in_array($course->approval_status, ['pending_review', 'rejected', 'hidden']))
                                                            <form method="POST"
                                                                action="{{ route('admin.approval-center.courses.publish', $course->id) }}">
                                                                @csrf
                                                                <button
                                                                    class="btn btn-success btn-sm w-100">Publish</button>
                                                            </form>
                                                        @endif

                                                        @if (in_array($course->approval_status, ['pending_review', 'draft']))
                                                            <button type="button" class="btn btn-danger btn-sm w-100"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#rejectCourseModal{{ $course->id }}">
                                                                Reject
                                                            </button>
                                                        @endif

                                                        @if ($course->approval_status === 'published')
                                                            <form method="POST"
                                                                action="{{ route('admin.approval-center.courses.hide', $course->id) }}">
                                                                @csrf
                                                                <button class="btn btn-warning btn-sm w-100">Hide</button>
                                                            </form>
                                                        @endif
                                                    </div>

                                                    <div class="modal fade" id="rejectCourseModal{{ $course->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form method="POST"
                                                                    action="{{ route('admin.approval-center.courses.reject', $course->id) }}">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Reject Course</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <label class="form-label">Review note</label>
                                                                        <textarea name="review_note" class="form-control" rows="4" required></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Đóng</button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Reject</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có course</td>
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
