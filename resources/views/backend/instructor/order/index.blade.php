@extends('backend.instructor.master')

@section('content')
    <div class="page-content">

        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">My Orders</h4>
                    <small class="text-muted">Danh sách đơn hàng thuộc các course của bạn</small>
                </div>
                <div>
                    <a href="{{ route('instructor.orders.export.csv', request()->query()) }}" class="btn btn-outline-success">
                        Export CSV
                    </a>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('instructor.orders.export.csv', request()->query()) }}" class="btn btn-success">
                        Export CSV
                    </a>

                    <a href="{{ route('instructor.orders.export.excel', request()->query()) }}" class="btn btn-primary">
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('instructor.orders.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Khóa học</label>
                            <select name="course_id" class="form-select">
                                <option value="">Tất cả khóa học</option>
                                @if (isset($courses) && count($courses) > 0)
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}"
                                            {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->course_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('instructor.orders.index') }}" class="btn btn-secondary btn-sm">Reset filter</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Orders table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Danh sách đơn hàng</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Mã đơn</th>
                                <th>Học viên</th>
                                <th>Course</th>
                                <th>Số tiền</th>
                                <th>Thanh toán</th>
                                <th>Invoice</th>
                                <th>Trạng thái</th>
                                <th>Ngày thanh toán</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $key => $order)
                                <tr>
                                    <td>{{ $orders->firstItem() + $key }}</td>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>{{ $order->course_title ?? ($order->course->course_name ?? 'N/A') }}</td>
                                    <td>{{ number_format($order->price, 0, ',', '.') }} đ</td>
                                    <td>{{ $order->payment->payment_type ?? 'N/A' }}</td>
                                    <td>{{ $order->payment->invoice_no ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $status = strtolower($order->status ?? 'completed');
                                        @endphp

                                        @if ($status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($status === 'refunded')
                                            <span class="badge bg-info text-dark">Refunded</span>
                                        @elseif($status === 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ optional($order->paid_at)->format('d/m/Y H:i') ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('instructor.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-primary">
                                            Xem
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        Không tìm thấy đơn hàng nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $orders->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>)
@endsection
