@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        @include('backend.section.breadcrumb', [
            'title' => 'Yêu cầu xóa tài khoản',
            'sub_title' => 'Quản lý yêu cầu từ người dùng',
        ])

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h6 class="mb-0 text-uppercase">Yêu cầu xóa tài khoản</h6>
                <small class="text-muted">Duyệt yêu cầu sẽ vô hiệu hóa và ẩn danh tài khoản, không xóa cứng dữ liệu giao dịch.</small>
            </div>
            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back"></i> Danh sách người dùng
            </a>
        </div>
        <hr />

        <div class="row row-cols-1 row-cols-md-3 g-3 mb-3">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Đang chờ xử lý</p>
                                <h4 class="my-1 text-warning">{{ $requests->total() }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                                <i class="bx bx-time"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Người dùng</th>
                                <th>Liên hệ</th>
                                <th>Lý do</th>
                                <th>Dữ liệu liên quan</th>
                                <th>Ngày yêu cầu</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $requestItem)
                                @php
                                    $user = $requestItem->user;
                                    $orderCount = $user?->orders?->count() ?? 0;
                                    $enrollmentCount = $user?->enrollments?->count() ?? 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($user?->photo)
                                                <img src="{{ asset($user->photo) }}" width="45" height="45" class="rounded-circle" alt="Avatar">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:45px;height:45px;">
                                                    <i class="bx bx-user fs-4"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $user->name ?? 'Không tìm thấy user' }}</strong>
                                                <div class="text-muted small">ID: {{ $user->id ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $user->email ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $user->phone ?? 'Chưa có số điện thoại' }}</small>
                                    </td>
                                    <td style="max-width: 320px;">
                                        @if ($requestItem->account_deletion_reason)
                                            <div class="text-wrap">{{ $requestItem->account_deletion_reason }}</div>
                                        @else
                                            <span class="text-muted">Không cung cấp lý do</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">Đơn hàng: {{ $orderCount }}</span>
                                        <span class="badge bg-info text-dark">Khóa học: {{ $enrollmentCount }}</span>
                                    </td>
                                    <td>
                                        {{ optional($requestItem->account_deletion_requested_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <form action="{{ route('admin.user.account-deletion.reject', $requestItem) }}" method="POST"
                                                onsubmit="return confirm('Từ chối yêu cầu xóa tài khoản này?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                    Từ chối
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.user.account-deletion.approve', $requestItem) }}" method="POST"
                                                onsubmit="return confirm('Duyệt yêu cầu này? Tài khoản sẽ bị vô hiệu hóa và ẩn danh thông tin cá nhân.')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Duyệt xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bx bx-check-circle fs-1 text-success"></i>
                                        <div class="fw-bold mt-2">Không có yêu cầu xóa tài khoản đang chờ</div>
                                        <small class="text-muted">Khi user gửi yêu cầu từ trang cài đặt, yêu cầu sẽ xuất hiện tại đây.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
