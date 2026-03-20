@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">


            <!--breadcrumb-->
            @include('backend.section.breadcrumb', [
                'title' => 'Phê duyệt & Quản lý Giảng viên',
                'sub_title' => 'Danh sách yêu cầu và giảng viên',
            ])
            <!--end breadcrumb-->

            <hr />

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card mb-4">


                <div class="card-body">
                    <form method="GET" action="{{ route('admin.instructor-requests.index') }}" class="row g-3 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="request_keyword" class="form-control"
                                placeholder="Tìm theo tên, email, phone, headline..."
                                value="{{ request('request_keyword') }}">
                        </div>

                        <div class="col-md-3">
                            <select name="request_status" class="form-select">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="pending" {{ request('request_status') == 'pending' ? 'selected' : '' }}>
                                    Chờ duyệt
                                </option>
                                <option value="approved" {{ request('request_status') == 'approved' ? 'selected' : '' }}>
                                    Đã duyệt
                                </option>
                                <option value="rejected" {{ request('request_status') == 'rejected' ? 'selected' : '' }}>
                                    Bị từ chối
                                </option>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary">Lọc request</button>
                            <a href="{{ route('admin.instructor-requests.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Người dùng</th>
                                    <th>Tiêu đề</th>
                                    <th>Số điện thoại</th>
                                    <th>Trạng thái</th>
                                    <th>Ghi chú Admin</th>
                                    <th>Ngày duyệt</th>
                                    <th width="240">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $key => $item)
                                    <tr>
                                        <td>{{ $requests->firstItem() + $key }}</td>
                                        <td>
                                            <strong>{{ $item->user->name ?? 'N/A' }}</strong><br>
                                            <small>{{ $item->user->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $item->headline ?? '---' }}</td>
                                        <td>{{ $item->phone ?? '---' }}</td>
                                        <td>
                                            <span
                                                class="badge 
                                                @if ($item->status == 'pending') bg-warning text-dark
                                                @elseif($item->status == 'approved') bg-success
                                                @else bg-danger @endif">
                                                @if ($item->status == 'pending')
                                                    Chờ duyệt
                                                @elseif($item->status == 'approved')
                                                    Đã duyệt
                                                @else
                                                    Bị từ chối
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $item->admin_note ?? '---' }}</td>
                                        <td>
                                            {{ $item->reviewed_at ? \Carbon\Carbon::parse($item->reviewed_at)->format('d/m/Y H:i') : '---' }}
                                        </td>
                                        <td>
                                            @if ($item->status == 'pending')
                                                <form action="{{ route('admin.instructor-requests.approve', $item->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Duyệt yêu cầu
                                                    </button>
                                                </form>

                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#rejectRequestModal{{ $item->id }}">
                                                    Từ chối yêu cầu
                                                </button>
                                            @else
                                                <span class="text-muted">Đã xử lý</span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Modal Reject Request --}}
                                    <div class="modal fade" id="rejectRequestModal{{ $item->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.instructor-requests.reject', $item->id) }}"
                                                method="POST" class="modal-content">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Từ chối Yêu cầu Giảng viên</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label class="form-label">Lý do từ chối</label>
                                                    <textarea name="admin_note" class="form-control" rows="4" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-danger">Từ chối</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Không có yêu cầu giảng viên</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $requests->links() }}
                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.instructor-requests.index') }}" class="row g-3 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="instructor_keyword" class="form-control"
                                placeholder="Tìm theo tên, email, số điện thoại..." value="{{ request('instructor_keyword') }}">
                        </div>

                        <div class="col-md-3">
                            <select name="instructor_status" class="form-select">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="approved"
                                    {{ request('instructor_status') == 'approved' ? 'selected' : '' }}>
                                    Đã duyệt
                                </option>
                                <option value="pending" {{ request('instructor_status') == 'pending' ? 'selected' : '' }}>
                                    Chờ duyệt
                                </option>
                                <option value="suspended"
                                    {{ request('instructor_status') == 'suspended' ? 'selected' : '' }}>
                                    Bị đình chỉ
                                </option>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary">Lọc giảng viên</button>
                            <a href="{{ route('admin.instructor-requests.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Giảng viên</th>
                                    <th>Số điện thoại</th>
                                    <th>Trạng thái</th>
                                    <th>Ghi chú đánh giá</th>
                                    <th>Ngày duyệt</th>
                                    <th width="250">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($instructors as $key => $instructor)
                                    <tr>
                                        <td>{{ $instructors->firstItem() + $key }}</td>
                                        <td>
                                            <strong>{{ $instructor->name }}</strong><br>
                                            <small>{{ $instructor->email }}</small>
                                        </td>
                                        <td>{{ $instructor->phone ?? '---' }}</td>
                                        <td>
                                            <span
                                                class="badge
                                                @if ($instructor->instructor_approval_status == 'approved') bg-success
                                                @elseif($instructor->instructor_approval_status == 'pending') bg-warning text-dark
                                                @elseif($instructor->instructor_approval_status == 'suspended') bg-danger
                                                @else bg-secondary @endif">
                                                @if ($instructor->instructor_approval_status == 'approved')
                                                    Đã duyệt
                                                @elseif($instructor->instructor_approval_status == 'pending')
                                                    Chờ duyệt
                                                @elseif($instructor->instructor_approval_status == 'suspended')
                                                    Bị đình chỉ
                                                @else
                                                    Không xác định
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $instructor->instructor_review_note ?? '---' }}</td>
                                        <td>
                                            {{ $instructor->instructor_reviewed_at ? \Carbon\Carbon::parse($instructor->instructor_reviewed_at)->format('d/m/Y H:i') : '---' }}
                                        </td>
                                        <td>
                                            @if ($instructor->instructor_approval_status !== 'approved')
                                                <form
                                                    action="{{ route('admin.instructor-requests.instructors.approve', $instructor->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Duyệt giảng viên
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($instructor->instructor_approval_status !== 'suspended')
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#suspendInstructorModal{{ $instructor->id }}">
                                                    Đình chỉ
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Modal Suspend Instructor --}}
                                    <div class="modal fade" id="suspendInstructorModal{{ $instructor->id }}"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form
                                                action="{{ route('admin.instructor-requests.instructors.suspend', $instructor->id) }}"
                                                method="POST" class="modal-content">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Đình chỉ Giảng viên</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                     <label class="form-label">Lý do đình chỉ</label>
                                                    <textarea name="review_note" class="form-control" rows="4" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-danger">Đình chỉ</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                         <td colspan="7" class="text-center">Không có giảng viên nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $instructors->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
