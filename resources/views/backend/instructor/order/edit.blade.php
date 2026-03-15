@extends('backend.instructor.master')

@section('content')
    <div class="page-content">

        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Order Detail</h4>
                    <small class="text-muted">Chi tiết đơn hàng #{{ $order->id }}</small>
                </div>
                <div>
                    <a href="{{ route('instructor.orders.index') }}" class="btn btn-secondary">
                        Quay lại
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Thông tin học viên</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Họ tên:</strong>
                        <div>{{ $order->user->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <div>{{ $order->user->email ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Thông tin đơn hàng</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Course:</strong>
                        <div>{{ $order->course_title ?? ($order->course->course_name ?? 'N/A') }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Giá:</strong>
                        <div>{{ number_format($order->price ?? 0, 0, ',', '.') }} đ</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Invoice:</strong>
                        <div>{{ $order->payment->invoice_no ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Payment Type:</strong>
                        <div>{{ $order->payment->payment_type ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Ngày thanh toán:</strong>
                        <div>{{ optional($order->paid_at)->format('d/m/Y H:i') ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Trạng thái:</strong>
                        <div>{{ ucfirst($order->status ?? 'completed') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Thông tin doanh thu</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Gross Amount:</strong>
                        <div>{{ number_format($order->gross_amount ?? ($order->price ?? 0), 0, ',', '.') }} đ</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Platform Amount:</strong>
                        <div>{{ number_format($order->platform_amount ?? 0, 0, ',', '.') }} đ</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Net Amount:</strong>
                        <div>{{ number_format($order->net_amount ?? ($order->price ?? 0), 0, ',', '.') }} đ</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Payment Metadata</h5>

                @php
                    $payment = $order->payment;
                    $excludedFields = ['id', 'created_at', 'updated_at'];
                @endphp

                @if ($payment)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                @foreach ($payment->getAttributes() as $key => $value)
                                    @if (!in_array($key, $excludedFields))
                                        <tr>
                                            <th style="width: 30%">{{ $key }}</th>
                                            <td>{{ $value !== null && $value !== '' ? $value : 'N/A' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Không có payment metadata.</p>
                @endif
            </div>
        </div>

    </div>
@endsection
