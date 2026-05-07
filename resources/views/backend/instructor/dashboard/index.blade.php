@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        @if (!isApprovedUser())
            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                <div class="text-white">
                    <p style="font-size: 20px">Tài khoản của bạn chưa được duyệt. Vui lòng chờ quản trị viên duyệt.</p>
                </div>
            </div>
        @endif

        {{-- ===== A. KPI CARDS WITH TRENDS ===== --}}
        <div class="row g-3 mb-4">
            @php
                $kpiCards = [
                    ['label' => 'Tổng Courses', 'value' => $summary['total_courses'], 'trend' => $trends['courses'], 'icon' => 'menu_book', 'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'],
                    ['label' => 'Active Courses', 'value' => $summary['active_courses'], 'trend' => null, 'icon' => 'check_circle', 'gradient' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'],
                    ['label' => 'Tổng Students', 'value' => $summary['total_students'], 'trend' => $trends['students'], 'icon' => 'group', 'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'],
                    ['label' => 'Total Enrollments', 'value' => $summary['total_enrollments'], 'trend' => $trends['enrollments'], 'icon' => 'how_to_reg', 'gradient' => 'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)'],
                    ['label' => 'Total Revenue', 'value' => number_format($summary['total_revenue'], 0, ',', '.') . ' đ', 'trend' => $trends['revenue'], 'icon' => 'payments', 'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'],
                ];
            @endphp

            @foreach ($kpiCards as $card)
                <div class="col-12 col-sm-6 col-xl">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-body position-relative p-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="d-flex align-items-center justify-content-center rounded-circle me-2"
                                    style="width: 40px; height: 40px; background: {{ $card['gradient'] }}; flex-shrink: 0;">
                                    <span class="material-symbols-outlined text-white" style="font-size: 20px;">{{ $card['icon'] }}</span>
                                </div>
                                <span class="text-muted" style="font-size: 0.78rem; line-height: 1.2;">{{ $card['label'] }}</span>
                            </div>
                            <h4 class="mb-1 fw-bold" style="font-size: 1.3rem;">
                                {{ is_numeric($card['value']) ? number_format($card['value']) : $card['value'] }}
                            </h4>
                            
                            @if ($card['trend'] !== null)
                                <div class="d-flex align-items-center" style="font-size: 0.78rem;">
                                    @if ($card['trend']['direction'] === 'up')
                                        <span class="badge bg-success bg-opacity-10 text-success d-flex align-items-center px-2 py-1" style="border-radius: 8px;">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">trending_up</span>
                                            <span class="ms-1">+{{ $card['trend']['percent'] }}%</span>
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger d-flex align-items-center px-2 py-1" style="border-radius: 8px;">
                                            <span class="material-symbols-outlined" style="font-size: 14px;">trending_down</span>
                                            <span class="ms-1">-{{ $card['trend']['percent'] }}%</span>
                                        </span>
                                    @endif
                                    <span class="text-muted ms-1">7 ngày</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ===== B. CHARTS ===== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">
                            <span class="material-symbols-outlined align-middle me-1" style="color: #667eea;">show_chart</span>
                            Học viên mới đăng ký (30 ngày)
                        </h6>
                        <div id="studentChart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">
                            <span class="material-symbols-outlined align-middle me-1" style="color: #43e97b;">bar_chart</span>
                            Doanh thu (30 ngày)
                        </h6>
                        <div id="revenueChart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== C. TOP PERFORMERS & ACTIVITIES ===== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <span class="material-symbols-outlined align-middle me-1" style="color: #4facfe;">menu_book</span>
                            My Courses Overview
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course Name</th>
                                        <th class="text-center">Students</th>
                                        <th class="text-center">Avg Progress</th>
                                        <th class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myCourses as $course)
                                        <tr>
                                            <td class="text-truncate" style="max-width: 250px; font-weight: 500;">
                                                <a href="{{ route('instructor.course.edit', $course['id']) }}" class="text-decoration-none">
                                                    {{ $course['title'] }}
                                                </a>
                                            </td>
                                            <td class="text-center">{{ $course['students'] }}</td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="progress flex-grow-1 mx-2" style="height: 6px;">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $course['avg_progress'] }}%;"></div>
                                                    </div>
                                                    <span>{{ $course['avg_progress'] }}%</span>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                @if($course['status'] == 'Published')
                                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Published</span>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">{{ $course['status'] }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">Chưa có dữ liệu khóa học.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <span class="material-symbols-outlined align-middle me-1" style="color: #fa709a;">history</span>
                            Recent Activities
                        </h6>

                        @if ($recentActivities->isEmpty())
                            <div class="text-center py-4 text-muted">
                                <span class="material-symbols-outlined" style="font-size: 36px;">inbox</span>
                                <p class="mb-0 mt-1">Chưa có hoạt động nào.</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                                @foreach ($recentActivities as $act)
                                    <div class="list-group-item border-0 px-0 py-2 d-flex align-items-start">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3 bg-{{ $act['color'] }} bg-opacity-10 flex-shrink-0"
                                            style="width: 36px; height: 36px;">
                                            <span class="material-symbols-outlined text-{{ $act['color'] }}" style="font-size: 18px;">{{ $act['icon'] }}</span>
                                        </div>
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <div style="font-size: 0.85rem;">
                                                <strong>{{ $act['user'] }}</strong> 
                                                <span class="text-muted">{{ $act['action'] }}</span> 
                                                <span class="fw-medium text-dark">{{ $act['target'] }}</span>
                                            </div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($act['time'])->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    {{-- ApexCharts --}}
    <link href="{{ asset('backend/assets/plugins/apexcharts-bundle/css/apexcharts.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Student Line Chart
            var studentOptions = {
                series: [{
                    name: 'Học viên mới',
                    data: @json($studentChart['data'])
                }],
                chart: { type: 'line', height: 310, toolbar: { show: false }, fontFamily: 'Space Grotesk, sans-serif' },
                colors: ['#667eea'],
                stroke: { curve: 'smooth', width: 3 },
                markers: { size: 0, hover: { size: 5 } },
                xaxis: {
                    categories: @json($studentChart['labels']),
                    labels: { style: { fontSize: '10px', colors: '#999' }, rotate: -45, rotateAlways: false },
                    tickAmount: 10
                },
                yaxis: {
                    labels: { style: { fontSize: '11px', colors: '#999' } }
                },
                tooltip: {
                    y: { formatter: function(v) { return v + ' learners'; } }
                },
                dataLabels: { enabled: false },
                grid: { borderColor: '#f1f1f1', strokeDashArray: 4 },
            };
            new ApexCharts(document.querySelector("#studentChart"), studentOptions).render();

            // 2. Revenue Area Chart
            var revenueOptions = {
                series: [{
                    name: 'Doanh thu',
                    data: @json($revenueChart['data'])
                }],
                chart: { type: 'area', height: 310, toolbar: { show: false }, fontFamily: 'Space Grotesk, sans-serif' },
                colors: ['#43e97b'],
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 100] }
                },
                stroke: { curve: 'smooth', width: 2.5 },
                xaxis: {
                    categories: @json($revenueChart['labels']),
                    labels: { style: { fontSize: '10px', colors: '#999' }, rotate: -45, rotateAlways: false },
                    tickAmount: 10
                },
                yaxis: {
                    labels: {
                        style: { fontSize: '11px', colors: '#999' },
                        formatter: function(v) { return new Intl.NumberFormat('vi-VN').format(v) + 'đ'; }
                    }
                },
                tooltip: {
                    y: { formatter: function(v) { return new Intl.NumberFormat('vi-VN').format(v) + ' đ'; } }
                },
                dataLabels: { enabled: false },
                grid: { borderColor: '#f1f1f1', strokeDashArray: 4 },
            };
            new ApexCharts(document.querySelector("#revenueChart"), revenueOptions).render();
        });
    </script>
@endpush
