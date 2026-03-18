@extends('backend.admin.master')

@section('content')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Instructor Requests</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách yêu cầu</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Yêu cầu đăng ký Instructor</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="60">STT</th>
                                <th>User</th>
                                <th>Headline</th>
                                <th>Phone</th>
                                <th>Bio</th>
                                <th>Experience</th>
                                <th>Status</th>
                                <th>Admin Note</th>
                                <th>Reviewed At</th>
                                <th width="220">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <div>
                                            <strong>{{ $item->user->name ?? 'N/A' }}</strong><br>
                                            <small>{{ $item->user->email ?? '' }}</small>
                                        </div>
                                    </td>

                                    <td>{{ $item->headline ?? '---' }}</td>
                                    <td>{{ $item->phone ?? '---' }}</td>

                                    <td style="max-width: 220px; white-space: normal;">
                                        {{ $item->bio ?? '---' }}
                                    </td>

                                    <td style="max-width: 220px; white-space: normal;">
                                        {{ $item->experience ?? '---' }}
                                    </td>

                                    <td>
                                        @if ($item->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($item->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($item->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>

                                    <td style="max-width: 200px; white-space: normal;">
                                        {{ $item->admin_note ?? '---' }}
                                    </td>

                                    <td>
                                        {{ $item->reviewed_at ? \Carbon\Carbon::parse($item->reviewed_at)->format('d/m/Y H:i') : '---' }}
                                    </td>

                                    <td>
                                        @if ($item->status === 'pending')
                                            <div class="d-flex flex-column gap-2">

                                                <form action="{{ route('admin.instructor-requests.approve', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                                        Approve
                                                    </button>
                                                </form>

                                                <button type="button" class="btn btn-danger btn-sm w-100"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $item->id }}">
                                                    Reject
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">Đã xử lý</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Modal Reject --}}
                                <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.instructor-requests.reject', $item->id) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Từ chối yêu cầu</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Admin note / Lý do từ chối</label>
                                                        <textarea name="admin_note" class="form-control @error('admin_note') is-invalid @enderror" rows="4"
                                                            placeholder="Nhập lý do từ chối..." required></textarea>

                                                        @error('admin_note')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        Đóng
                                                    </button>
                                                    <button type="submit" class="btn btn-danger">
                                                        Xác nhận từ chối
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Chưa có yêu cầu nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
