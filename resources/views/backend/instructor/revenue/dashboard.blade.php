@extends('backend.instructor.master')

@section('content')
    <div class="page-content">

        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Revenue Dashboard</h4>
                    <small class="text-muted">Theo dõi doanh thu và hiệu suất bán khóa học</small>
                </div>
                <div>
                    <a href="{{ route('instructor.orders.index') }}" class="btn btn-outline-primary">
                        Xem đơn hàng
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('instructor.revenue.dashboard') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">Lọc dữ liệu</button>
                            <a href="{{ route('instructor.revenue.dashboard') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Summary cards --}}
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Tổng doanh thu</h6>
                        <h4 class="fw-bold text-success">
                            {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }} đ
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Tổng đơn hàng</h6>
                        <h4 class="fw-bold">
                            {{ $summary['total_orders'] ?? 0 }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Số khóa học đã bán</h6>
                        <h4 class="fw-bold text-primary">
                            {{ $summary['total_courses_sold'] ?? 0 }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Khóa học có phát sinh bán</h6>
                        <h4 class="fw-bold text-warning">
                            {{ $summary['distinct_courses_sold'] ?? 0 }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Trong khu vực hiển thị Card thống kê -->
            <div class="col-12 col-lg-4">
                <div class="card radius-10 bg-gradient-deepblue">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 text-white">{{ number_format($available_balance, 0, ',', '.') }} VNĐ</h5>
                            <div class="ms-auto">
                                <i class='bx bx-wallet fs-3 text-white'></i>
                            </div>
                        </div>
                        <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: 100%"></div>
                        </div>
                        <div class="d-flex align-items-center text-white">
                            <p class="mb-0">Số dư khả dụng (Net)</p>
                            <!-- Nút mở Modal Payout -->
                            <button class="btn btn-light btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#payoutModal">
                                Rút Tiền
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            {{-- Revenue By Day --}}
            <div class="col-md-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Doanh thu theo ngày</h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Số lượng bán</th>
                                        <th>Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dailyRevenue as $item)
                                        <tr>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->report_date)->format('d/m/Y') }}
                                            </td>
                                            <td>{{ $item->sold_count }}</td>
                                            <td>{{ number_format($item->revenue, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                Chưa có dữ liệu doanh thu theo ngày
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top courses --}}
            <div class="col-md-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Top course bán chạy</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Đã bán</th>
                                        <th>Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topCourses as $course)
                                        <tr>
                                            <td>{{ $course->course_title }}</td>
                                            <td>{{ $course->sold_count }}</td>
                                            <td>{{ number_format($course->revenue, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                Chưa có dữ liệu top course
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Revenue By Month --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Doanh thu theo tháng</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tháng</th>
                                        <th>Số lượng bán</th>
                                        <th>Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($monthlyRevenue as $item)
                                        <tr>
                                            <td>{{ str_pad($item->month, 2, '0', STR_PAD_LEFT) }}/{{ $item->year }}</td>
                                            <td>{{ $item->sold_count }}</td>
                                            <td>{{ number_format($item->revenue, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                Chưa có dữ liệu doanh thu theo tháng
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng Lịch sử Rút tiền -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="mb-3">Lịch sử rút tiền</h5>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Ngày yêu cầu</th>
                                <th>Ngân hàng</th>
                                <th>Số tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payout_history as $history)
                                <tr>
                                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $history->bank_name }} - {{ $history->account_number }}</td>
                                    <td>{{ number_format($history->amount, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        @if($history->status == 'approved')
                                            <span class="badge bg-success">Đã duyệt</span>
                                            <div class="text-muted mt-1" style="font-size: 11px;">
                                                Mã GD: {{ $history->transaction_reference }}
                                            </div>
                                        @elseif($history->status == 'rejected')
                                            <span class="badge bg-danger" title="{{ $history->admin_note }}">Từ chối (Di chuột để xem)</span>
                                        @else
                                            <span class="badge bg-warning">Đang chờ</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $payout_history->links() }}
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Modal Rút Tiền -->
    <div class="modal fade" id="payoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('instructor.revenue.request-payout') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Yêu cầu rút tiền</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Số dư có thể rút</label>
                            <input type="text" class="form-control"
                                value="{{ number_format($available_balance, 0, ',', '.') }} VNĐ" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số tiền cần rút (VNĐ)</label>
                            <input type="number" name="amount" class="form-control" min="100000"
                                max="{{ $available_balance }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên Ngân Hàng</label>
                            <input type="text" name="bank_name" class="form-control" placeholder="VD: Vietcombank"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số tài khoản</label>
                            <input type="text" name="account_number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên chủ tài khoản</label>
                            <input type="text" name="account_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary"
                            {{ $available_balance < 100000 ? 'disabled' : '' }}>Gửi yêu cầu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
