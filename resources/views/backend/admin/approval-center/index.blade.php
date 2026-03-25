@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h4 class="mb-4">Trung tâm quản lý</h4>

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>Yêu cầu giảng viên</h6>
                            <h3>{{ $stats['pending_instructor_requests'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>Phê duyệt khóa học</h6>
                            <h3>{{ $stats['pending_course_approvals'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>Báo cáo nội dung</h6>
                            <h3>{{ $stats['pending_reports'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>Yêu cầu hoàn tiền</h6>
                            <h3>{{ $stats['pending_refunds'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách chờ xử lý</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Loại</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($queueItems as $item)
                                <tr>
                                    <td>{{ $item['type'] }}</td>
                                    <td>{{ $item['title'] }} #{{ $item['id'] }}</td>
                                    <td>{{ $item['status'] }}</td>
                                    <td>{{ $item['created_at'] }}</td>
                                    <td>
                                        <a href="{{ $item['url'] }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có mục nào đang chờ xử lý</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
