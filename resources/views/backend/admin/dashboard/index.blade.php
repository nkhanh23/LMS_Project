@extends('backend.admin.master')

@section('content')
    <div class="page-content">

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-primary">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Tổng user</p>
                        <h4 class="my-1 text-primary">{{ number_format($summary['total_users']) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-success">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Tổng instructor</p>
                        <h4 class="my-1 text-success">{{ number_format($summary['total_instructors']) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Tổng course</p>
                        <h4 class="my-1 text-warning">{{ number_format($summary['total_courses']) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-info">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Paid orders</p>
                        <h4 class="my-1 text-info">{{ number_format($summary['paid_orders']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mt-2">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Doanh thu hôm nay</p>
                        <h4 class="my-1">{{ number_format($summary['revenue_today'], 0, ',', '.') }} đ</h4>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Doanh thu tháng này</p>
                        <h4 class="my-1">{{ number_format($summary['revenue_month'], 0, ',', '.') }} đ</h4>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Course chờ duyệt</p>
                        <h4 class="my-1">{{ number_format($summary['pending_courses']) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">Instructor chờ duyệt</p>
                        <h4 class="my-1">{{ number_format($summary['pending_instructors']) }}</h4>
                        <a href="{{ route('admin.instructor-requests.index') }}" class="btn btn-primary btn-sm">Xem chi
                            tiết</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card radius-10">
                    <div class="card-body">
                        <h5 class="mb-3">Payment status</h5>
                        <p>Success: <strong>{{ $summary['payment_success'] }}</strong></p>
                        <p>Pending: <strong>{{ $summary['payment_pending'] }}</strong></p>
                        <p>Failed: <strong>{{ $summary['payment_failed'] }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card radius-10">
                    <div class="card-body">
                        <h5 class="mb-3">Top course theo doanh thu</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Lượt bán</th>
                                        <th>Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topCourses as $course)
                                        <tr>
                                            <td>{{ $course->course_title ?? 'N/A' }}</td>
                                            <td>{{ $course->total_sales }}</td>
                                            <td>{{ number_format($course->revenue, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Chưa có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
