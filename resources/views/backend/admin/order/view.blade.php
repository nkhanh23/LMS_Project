@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Đơn hàng',
            'sub_title' => 'Chi tiết đơn hàng',
        ])
        <!--end breadcrumb-->
        <div style="display: flex; align-items:center; justify-content:space-between">
            <h6 class="mb-0 text-uppercase">Chi tiết đơn hàng</h6>
        </div>

        <hr />

        <div class="row align-items-stretch mt-5">
            <div class="col-md-6">

                <div class="card h-100">
                    <div class="card-body">

                        <div style="display:flex; align-items:center; justify-content: flex-start; gap: 15px">
                            <div>
                                <img src="{{ !empty($user_info->photo) ? asset($user_info->photo) : asset('backend/assets/images/avatars/avatar-2.png') }}"
                                    class="text-center" width="120" height='120' style="border-radius: 60px" />
                            </div>
                            <div style="display: flex; flex-direction:column; gap: 10px;">
                                <span>Tên : {{ $user_info->name }}</span>
                                <span>Email : {{ $user_info->email }}</span>
                                <span>Số điện thoại : {{ $user_info->phone }}</span>
                                <span>Địa chỉ: {{ $user_info->address }}</span>
                                <span>Bio: {{ $user_info->bio }}</span>
                                <span>Giới tính: {{ $user_info->gender }}</span>
                            </div>

                        </div>



                    </div>
                </div>

            </div>

            <div class="col-md-6">

                <div class="card h-100">
                    <div class="card-body">

                        <div style="display:flex; align-items:center; justify-content: flex-start; gap: 15px">




                            <div style="display: flex; flex-direction:column; gap: 10px;">
                                <span>Total Amount : {{ $payment_info->total_amount }}</span>
                                <span>Payment Type : {{ $payment_info->payment_type }}</span>
                                <span>Invoice Number : {{ $payment_info->invoice_no }}</span>
                                <span>Order Date: {{ $payment_info->created_at->format('F d, Y') }}</span>

                                <span>Trx Id: {{ $payment_info->transaction_id }}</span>

                            </div>

                        </div>



                    </div>
                </div>

            </div>


        </div>

        <div class="mt-5">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Course Name</th>
                                    <th>Category</th>
                                    <th>Instructor</th>
                                    <th>Price</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payment_info['order'] as $item)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($item->course->course_image) }}" width="80"
                                                height="80" style="border-radius: 5px" />
                                        </td>

                                        <td>{{ $item->course->course_name }}</td>
                                        <td>
                                            {{ $item->course->category->name }}
                                        </td>

                                        <td>
                                            {{ $item->instructor->name }}
                                        </td>

                                        <td>
                                            ${{ $item->price }}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="mt-5">
            @foreach($payment_info->order as $order)
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>Order #{{ $order->id }}</strong></p>
                        <p>Course: {{ $order->course->course_name ?? $order->course_title }}</p>
                        <p>Status: {{ $order->status }}</p>
                        <p>Refund Status: {{ $order->refund_status }}</p>

                        @if(in_array($order->status, ['pending']))
                            <!-- Manual Cancel Modal Trigger -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#manualCancelModal{{ $order->id }}">
                                Manual Cancel
                            </button>

                            <!-- Manual Cancel Modal -->
                            <div class="modal fade" id="manualCancelModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.orders.manual_cancel', $order) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Manual Cancel Order #{{ $order->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Lý do cancel</label>
                                                    <textarea name="reason" class="form-control" rows="2" required placeholder="Nhập lý do hủy..."></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ghi chú admin</label>
                                                    <textarea name="admin_note" class="form-control" rows="2" placeholder="Ghi chú nội bộ..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-danger">Xác nhận Hủy đơn</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
