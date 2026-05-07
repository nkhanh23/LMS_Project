@extends('backend.admin.master')
@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Quản lý Yêu cầu rút tiền</h5>
        <form method="GET" action="{{ route('admin.payouts.index') }}">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
            </select>
        </form>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Giảng viên</th>
                    <th>Số tiền</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payouts as $payout)
                <tr>
                    <td>#{{ $payout->id }}</td>
                    <td>
                        <strong>{{ $payout->instructor->name }}</strong><br>
                        <small class="text-muted">{{ $payout->instructor->email }}</small>
                    </td>
                    <td class="text-primary font-weight-bold">{{ number_format($payout->amount, 0, ',', '.') }} đ</td>
                    <td>{{ $payout->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($payout->status == 'pending') <span class="badge bg-warning text-dark">Đang chờ</span>
                        @elseif($payout->status == 'approved') <span class="badge bg-success">Đã duyệt</span>
                        @else <span class="badge bg-danger">Từ chối</span> @endif
                    </td>
                    <td>
                        @if($payout->status == 'pending')
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#processModal{{ $payout->id }}">Xử lý</button>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="processModal{{ $payout->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.payouts.approve', $payout->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Xử lý rút tiền #{{ $payout->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Giảng viên: <strong>{{ $payout->instructor->name }}</strong></p>
                                                <p>Số tiền yêu cầu: <strong class="text-danger">{{ number_format($payout->amount, 0, ',', '.') }} VNĐ</strong></p>
                                                <hr>
                                                <div class="form-group mb-3">
                                                    <label>Hành động</label>
                                                    <select name="status" class="form-select" id="actionSelect{{ $payout->id }}" onchange="toggleRefField({{ $payout->id }})" required>
                                                        <option value="approved">Duyệt & Đã chuyển khoản</option>
                                                        <option value="rejected">Từ chối yêu cầu</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3" id="refField{{ $payout->id }}">
                                                    <label>Mã giao dịch (Nếu duyệt)</label>
                                                    <input type="text" name="transaction_reference" class="form-control" placeholder="Nhập mã bill ngân hàng...">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Ghi chú cho giảng viên</label>
                                                    <textarea name="admin_note" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary">Xác nhận</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <button class="btn btn-sm btn-secondary" disabled>Đã xử lý</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $payouts->links() }}
    </div>
</div>

<script>
    function toggleRefField(id) {
        const select = document.getElementById('actionSelect' + id);
        const refField = document.getElementById('refField' + id);
        const form = select.closest('form');
        
        if (select.value === 'rejected') {
            refField.style.display = 'none';
            let url = "{{ route('admin.payouts.reject', ':id') }}";
            form.action = url.replace(':id', id);
        } else {
            refField.style.display = 'block';
            let url = "{{ route('admin.payouts.approve', ':id') }}";
            form.action = url.replace(':id', id);
        }
    }
</script>
@endsection 
