@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!--breadcrumb-->
            @include('backend.section.breadcrumb', [
                'title' => 'Phê duyệt khóa học',
                'sub_title' => 'Chi tiết phê duyệt',
            ])
            <!--end breadcrumb-->
            
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Thông tin khóa học</h5>
                            <span class="badge bg-info">{{ $course->approval_status }}</span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <img src="{{ !empty($course->course_image) ? url($course->course_image) : url('upload/no_image.jpg') }}" 
                                         alt="Course Image" class="img-fluid rounded border">
                                </div>
                                <div class="col-md-8">
                                    <h4 class="mb-2">{{ $course->course_name }}</h4>
                                    <p class="mb-1"><strong>Giảng viên:</strong> 
                                        {{ $course->user->name ?? '---' }}
                                        @if(isset($course->user))
                                            @php $risk = $course->user->risk_level; @endphp
                                            <span class="badge {{ $risk['class'] }} ms-2" style="font-size: 0.75rem;">
                                                Risk: {{ $risk['score'] }} ({{ $risk['label'] }})
                                            </span>
                                        @endif
                                    </p>
                                    <p class="mb-1"><strong>Danh mục:</strong> {{ $course->category->category_name ?? '---' }} / {{ $course->subcategory->subcategory_name ?? '---' }}</p>
                                    <p class="mb-1"><strong>Giá:</strong> {{ number_format($course->selling_price, 0, ',', '.') }}đ</p>
                                    <p class="mb-1"><strong>Ngày gửi duyệt:</strong> {{ $course->submitted_for_review_at ?? '---' }}</p>
                                </div>
                            </div>
                            <hr>
                            <h6>Mô tả ngắn:</h6>
                            <p>{{ $course->course_title }}</p>
                            <h6>Mô tả chi tiết:</h6>
                            <div class="border p-3 bg-light rounded" style="max-height: 300px; overflow-y: auto;">
                                {!! $course->description !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border shadow-none">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Course Quality Checklist</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($qualityChecks as $check)
                                    <li class="list-group-item d-flex justify-content-between align-items-start px-0">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold text-capitalize">{{ str_replace('_', ' ', $check['check_key']) }}</div>
                                            @if($check['status'] === 'fail')
                                                <small class="text-danger">{{ $check['message'] }}</small>
                                            @endif
                                        </div>
                                        @if($check['status'] === 'pass')
                                            <span class="badge bg-success rounded-pill">PASS</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">FAIL</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            <hr>

                            <div class="d-grid gap-2">
                                @if (in_array($course->approval_status, ['pending_review', 'rejected', 'hidden']))
                                    <form method="POST" action="{{ route('admin.course-approvals.publish', $course->id) }}">
                                        @csrf
                                        <button class="btn btn-success w-100">Duyệt & Xuất bản</button>
                                    </form>
                                @endif

                                @if (in_array($course->approval_status, ['pending_review', 'draft']))
                                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        Từ chối phê duyệt
                                    </button>
                                @endif
                                
                                @if ($course->approval_status === 'published')
                                    <form method="POST" action="{{ route('admin.course-approvals.hide', $course->id) }}">
                                        @csrf
                                        <button class="btn btn-warning w-100">Ẩn khóa học</button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.course-approvals.index') }}" class="btn btn-outline-secondary w-100">Quay lại danh sách</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.course-approvals.reject', $course->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Từ chối phê duyệt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Lý do từ chối (Ghi chú đánh giá)</label>
                            <textarea name="review_note" class="form-control" rows="5" required placeholder="Nhập lý do tại sao khóa học không đạt yêu cầu..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
