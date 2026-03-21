@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', ['title' => 'Đơn hàng', 'sub_title' => 'Tất cả đơn hàng'])
        <div style="display: flex; align-items:center; justify-content:space-between">
            <h6 class="mb-0 text-uppercase">Tất cả đơn hàng</h6>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.order.index') }}" method="GET" id="filter-form">
                    <div class="row align-items-end">

                        <div class="col-md-3">
                            <label class="form-label font-weight-bold">Từ ngày</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ $filters['start_date'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold">Đến ngày</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ $filters['end_date'] ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label font-weight-bold">Khoảng giá (VND/USD): <span id="amount-show"
                                    class="text-danger"></span></label>
                            <div id="slider-range" class="mt-2 mb-3"></div>

                            <input type="hidden" name="min_amount" id="min_amount"
                                value="{{ $filters['min_amount'] ?? 0 }}">
                            <input type="hidden" name="max_amount" id="max_amount"
                                value="{{ $filters['max_amount'] ?? 10000 }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label font-weight-bold">Thanh toán</label>
                            <select name="payment_method" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <option value="stripe"
                                    {{ ($filters['payment_method'] ?? '') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="paypal"
                                    {{ ($filters['payment_method'] ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="cash" {{ ($filters['payment_method'] ?? '') == 'cash' ? 'selected' : '' }}>
                                    Tiền mặt</option>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100 mb-1">Lọc</button>
                            <a href="{{ route('admin.order.index') }}" class="btn btn-secondary w-100">Xóa</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Ngày</th>
                                <th>Mã giao dịch</th>
                                <th>Số tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, h:i A') }}</td>
                                    <td>{{ $item->payment->transaction_id ?? 'N/A' }}</td>
                                    <td>
                                        {{ number_format($item->price, 0) }}
                                    </td>
                                    <td>
                                        {{ $item->payment->payment_type ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $item->status }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.order.show', $item->id) }}" class="btn btn-primary" title="Xem chi tiết">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                <path
                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                            </svg>
                                        </a>

                                        @if($item->status === 'completed' && !in_array($item->refund_status, ['requested', 'approved', 'processed']))
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#manualRefundModal{{ $item->id }}" title="Hoàn tiền">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2z"/>
                                                    <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966a.25.25 0 0 0 .41-.192"/>
                                                </svg>
                                            </button>

                                            <!-- Manual Refund Modal -->
                                            <div class="modal fade" id="manualRefundModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.orders.manual_refund', $item->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Manual Refund Order #{{ $item->id }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-start">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Số tiền hoàn (Approved Amount)</label>
                                                                    <input type="number" step="0.01" name="approved_amount" class="form-control"
                                                                        value="{{ $item->total_amount }}" required>
                                                                    <small class="text-muted">Tổng tiền đơn hàng: {{ number_format($item->total_amount) }}</small>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Lý do hoàn tiền (Reason)</label>
                                                                    <textarea name="reason" class="form-control" rows="3" required placeholder="Nhập lý do..."></textarea>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Ghi chú admin (Internal Note)</label>
                                                                    <textarea name="admin_note" class="form-control" rows="2" placeholder="Ghi chú thêm..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-warning">Xác nhận Hoàn tiền</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif






                                    </td>
                                </tr>
                            @endforeach
                        </tbody>


                    </table>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Lấy giá trị min/max từ hidden input (đã có từ URL nếu user đang ở trạng thái lọc)
            let currentMin = parseInt($('#min_amount').val()) || 0;
            let currentMax = parseInt($('#max_amount').val()) || 10000; // Thay 10000 bằng Max Price hệ thống

            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 10000000, // Cấu hình Max Limit của thanh trượt
                step: 10, // Bước nhảy của thanh trượt
                values: [currentMin, currentMax],
                slide: function(event, ui) {
                    // Khi kéo trượt, update Text hiển thị cho user
                    $("#amount-show").text("VNĐ " + ui.values[0].toLocaleString() + " - VNĐ " + ui
                        .values[1].toLocaleString());
                    // Gắn value vào hidden input để đẩy lên URL
                    $("#min_amount").val(ui.values[0]);
                    $("#max_amount").val(ui.values[1]);
                }
            });

            // Khởi tạo text hiển thị khi trang vừa load xong
            $("#amount-show").text("VNĐ " + $("#slider-range").slider("values", 0).toLocaleString() +
                " - VNĐ " + $("#slider-range").slider("values", 1).toLocaleString());
        });
    </script>
@endpush
