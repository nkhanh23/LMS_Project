@extends('backend.admin.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Refund Requests</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order</th>
                        <th>User</th>
                        <th>Course</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Requested Amount</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($refundRequests as $refundRequest)
                        <tr>
                            <td>{{ $refundRequest->id }}</td>
                            <td>#{{ $refundRequest->order_id }}</td>
                            <td>{{ $refundRequest->user->name ?? 'N/A' }}</td>
                            <td>{{ $refundRequest->order->course->course_name ?? ($refundRequest->order->course_title ?? 'N/A') }}
                            </td>
                            <td>{{ $refundRequest->type }}</td>
                            <td>{{ $refundRequest->status }}</td>
                            <td>{{ number_format($refundRequest->requested_amount ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $refundRequest->reason }}</td>
                            <td>
                                @if ($refundRequest->status === 'pending')
                                    <form action="{{ route('admin.orders.refund_requests.approve', $refundRequest) }}"
                                        method="POST" class="mb-2">
                                        @csrf
                                        <input type="number" step="0.01" name="approved_amount" class="form-control mb-2"
                                            value="{{ $refundRequest->requested_amount }}">
                                        <textarea name="admin_note" class="form-control mb-2" rows="2" placeholder="Ghi chú admin"></textarea>
                                        <button class="btn btn-success btn-sm">Approve</button>
                                    </form>

                                    <form action="{{ route('admin.orders.refund_requests.reject', $refundRequest) }}"
                                        method="POST">
                                        @csrf
                                        <textarea name="admin_note" class="form-control mb-2" rows="2" placeholder="Lý do từ chối"></textarea>
                                        <button class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary">{{ $refundRequest->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">Chưa có refund request.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $refundRequests->links() }}
        </div>
    </div>
@endsection
