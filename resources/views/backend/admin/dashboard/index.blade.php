@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        {{-- ===== A. KPI CARDS WITH TRENDS ===== --}}
        <div class="row g-3 mb-4">
            @php
                $kpiCards = [
                    ['label' => 'Tổng Users', 'value' => $summary['total_users'], 'trend' => $trends['users'], 'icon' => 'group', 'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'],
                    ['label' => 'Instructors', 'value' => $summary['total_instructors'], 'trend' => $trends['instructors'], 'icon' => 'school', 'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'],
                    ['label' => 'Tổng Courses', 'value' => $summary['total_courses'], 'trend' => $trends['courses'], 'icon' => 'menu_book', 'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'],
                    ['label' => 'Paid Orders', 'value' => $summary['paid_orders'], 'trend' => $trends['orders'], 'icon' => 'shopping_cart', 'gradient' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'],
                    ['label' => 'Doanh thu tháng', 'value' => number_format($summary['revenue_month'], 0, ',', '.') . ' đ', 'trend' => $trends['revenue'], 'icon' => 'payments', 'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'],
                    ['label' => 'Enrollments', 'value' => $summary['total_enrollments'], 'trend' => $trends['enrollments'], 'icon' => 'how_to_reg', 'gradient' => 'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)'],
                ];
            @endphp

            @foreach ($kpiCards as $card)
                <div class="col-12 col-sm-6 col-xl-2">
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
                            <div class="d-flex align-items-center" style="font-size: 0.78rem;">
                                @if ($card['trend']['direction'] === 'up')
                                    <span class="badge bg-light border border-success text-success d-flex align-items-center px-2 py-1" style="border-radius: 8px;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">trending_up</span>
                                        <span class="ms-1">+{{ $card['trend']['percent'] }}%</span>
                                    </span>
                                @else
                                    <span class="badge bg-light border border-danger text-danger d-flex align-items-center px-2 py-1" style="border-radius: 8px;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">trending_down</span>
                                        <span class="ms-1">-{{ $card['trend']['percent'] }}%</span>
                                    </span>
                                @endif
                                <span class="text-muted ms-1">7 ngày</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ===== B & C. REVENUE + USER GROWTH CHARTS ===== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">
                            <span class="material-symbols-outlined align-middle me-1" style="color: #667eea;">bar_chart</span>
                            Doanh thu 30 ngày gần nhất
                        </h6>
                        <div id="revenueChart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">
                            <span class="material-symbols-outlined align-middle me-1" style="color: #43e97b;">show_chart</span>
                            User mới đăng ký
                        </h6>
                        <div id="userGrowthChart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== D. SYSTEM FLOW DIAGRAM ===== --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <span class="material-symbols-outlined align-middle me-1" style="color: #764ba2;">account_tree</span>
                    System Flow Overview
                </h6>
                <div class="d-flex flex-wrap align-items-start justify-content-center gap-2 py-3" id="systemFlow">
                    @php
                        $flowNodes = [
                            ['label' => 'Users', 'value' => number_format($flowStats['total_users']), 'icon' => 'group', 'color' => '#667eea'],
                            ['label' => 'Enrollments', 'value' => number_format($flowStats['total_enrollments']), 'icon' => 'how_to_reg', 'color' => '#4facfe'],
                            ['label' => 'Courses', 'value' => number_format($flowStats['total_courses']), 'icon' => 'menu_book', 'color' => '#43e97b'],
                            ['label' => 'Payments', 'value' => number_format($flowStats['total_payments_completed']), 'icon' => 'credit_card', 'color' => '#fa709a'],
                            ['label' => 'Revenue', 'value' => number_format($flowStats['total_revenue'], 0, ',', '.') . 'đ', 'icon' => 'payments', 'color' => '#fee140'],
                        ];
                        $enrollRate = $flowStats['total_users'] > 0 ? round(($flowStats['total_enrollments'] / $flowStats['total_users']) * 100, 1) : 0;
                    @endphp

                    @foreach ($flowNodes as $i => $node)
                        <div class="text-center" style="min-width: 110px;">
                            <div class="mx-auto d-flex align-items-center justify-content-center rounded-3 mb-2 position-relative"
                                style="width: 64px; height: 64px; background: {{ $node['color'] }}20; border: 2px solid {{ $node['color'] }};">
                                <span class="material-symbols-outlined" style="font-size: 28px; color: {{ $node['color'] }};">{{ $node['icon'] }}</span>
                            </div>
                            <div class="fw-bold" style="font-size: 1rem;">{{ $node['value'] }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $node['label'] }}</div>
                        </div>
                        @if ($i < count($flowNodes) - 1)
                            <div class="d-flex align-items-center" style="padding-top: 16px;">
                                <span class="material-symbols-outlined text-muted" style="font-size: 28px;">arrow_forward</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Quiz branch --}}
                <div class="d-flex justify-content-center mt-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="text-muted d-flex align-items-center" style="font-size: 0.8rem;">
                            <span class="material-symbols-outlined me-1" style="font-size: 16px;">subdirectory_arrow_right</span>
                            Quiz
                        </div>
                        <div class="badge bg-light border border-info text-info px-3 py-2" style="border-radius: 10px;">
                            <span class="material-symbols-outlined align-middle" style="font-size: 16px;">quiz</span>
                            {{ number_format($flowStats['total_quiz_attempts']) }} attempts
                        </div>
                        <span class="material-symbols-outlined text-muted">arrow_forward</span>
                        <div class="badge bg-light border border-success text-success px-3 py-2" style="border-radius: 10px;">
                            <span class="material-symbols-outlined align-middle" style="font-size: 16px;">check_circle</span>
                            {{ number_format($flowStats['total_quiz_passed']) }} passed
                        </div>
                        <span class="mx-3 text-muted">|</span>
                        <div class="badge bg-light border border-success text-success px-3 py-2" style="border-radius: 10px;">
                            <span class="material-symbols-outlined align-middle" style="font-size: 16px;">workspace_premium</span>
                            {{ number_format($flowStats['total_course_completed']) }} course completed
                        </div>
                    </div>
                </div>

                @if ($enrollRate < 30)
                    <div class="alert alert-warning d-flex align-items-center mt-3 mb-0 py-2" style="border-radius: 10px; font-size: 0.85rem;">
                        <span class="material-symbols-outlined me-2">warning</span>
                        Tỷ lệ enroll chỉ <strong class="mx-1">{{ $enrollRate }}%</strong> — Nhiều user nhưng ít enroll. Cần xem lại UX / marketing.
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== E. TOP PERFORMERS ===== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <span class="material-symbols-outlined align-middle me-1" style="color: #4facfe;">emoji_events</span>
                            Top Courses theo doanh thu
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light">
                                    <tr><th>#</th><th>Course</th><th class="text-center">Lượt bán</th><th class="text-end">Doanh thu</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topCourses as $i => $course)
                                        <tr>
                                            <td><span class="badge bg-light border border-primary text-primary">{{ $i + 1 }}</span></td>
                                            <td class="text-truncate" style="max-width: 200px;">{{ $course->course_title ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $course->total_sales }}</td>
                                            <td class="text-end fw-semibold">{{ number_format($course->revenue, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <span class="material-symbols-outlined align-middle me-1" style="color: #f5576c;">star</span>
                            Top Instructors theo doanh thu
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light">
                                    <tr><th>#</th><th>Instructor</th><th class="text-center">Lượt bán</th><th class="text-end">Doanh thu</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topInstructors as $i => $inst)
                                        <tr>
                                            <td><span class="badge bg-light border border-danger text-danger">{{ $i + 1 }}</span></td>
                                            <td>{{ $inst->instructor_name ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $inst->total_sales }}</td>
                                            <td class="text-end fw-semibold">{{ number_format($inst->revenue, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== F. ALERTS / WARNINGS ===== --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <span class="material-symbols-outlined align-middle me-1" style="color: #f5576c;">notifications_active</span>
                    Cảnh báo vận hành
                </h6>
                <div class="row g-3">
                    @foreach ($alerts as $alert)
                        @if ($alert['count'] > 0)
                            <div class="col-md-6 col-xl-3">
                                <a href="{{ $alert['url'] }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 rounded-3 border border-{{ $alert['type'] }} border-opacity-25 bg-{{ $alert['type'] }} bg-opacity-10 h-100"
                                        style="transition: all 0.2s ease;">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3 bg-{{ $alert['type'] }} bg-opacity-25"
                                            style="width: 44px; height: 44px; flex-shrink: 0;">
                                            <span class="material-symbols-outlined text-{{ $alert['type'] }}" style="font-size: 22px;">{{ $alert['icon'] }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-{{ $alert['type'] }}" style="font-size: 1.3rem;">{{ $alert['count'] }}</div>
                                            <div class="text-muted" style="font-size: 0.8rem;">{{ $alert['message'] }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach

                    @if (collect($alerts)->sum('count') === 0)
                        <div class="col-12">
                            <div class="text-center py-3 text-success">
                                <span class="material-symbols-outlined" style="font-size: 36px;">check_circle</span>
                                <p class="mb-0 mt-1">Hệ thống hoạt động ổn định — không có cảnh báo nào!</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== G. RECENT ACTIVITY FEED ===== --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <span class="material-symbols-outlined align-middle me-1" style="color: #43e97b;">history</span>
                    Hoạt động gần đây
                </h6>

                @if ($recentActivity->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <span class="material-symbols-outlined" style="font-size: 36px;">inbox</span>
                        <p class="mb-0 mt-1">Chưa có hoạt động nào được ghi nhận.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($recentActivity as $log)
                            <div class="list-group-item border-0 px-0 py-2 d-flex align-items-start">
                                <div class="d-flex align-items-center justify-content-center rounded-circle me-3 bg-primary bg-opacity-10 flex-shrink-0"
                                    style="width: 36px; height: 36px;">
                                    <span class="material-symbols-outlined text-primary" style="font-size: 18px;">person</span>
                                </div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div style="font-size: 0.85rem;">
                                        <strong>{{ $log->admin->name ?? 'System' }}</strong>
                                        <span class="text-muted">{{ $log->action ?? '' }}</span>
                                        <span class="badge bg-light border border-secondary text-secondary ms-1" style="font-size: 0.7rem;">{{ $log->entity_type ?? '' }}</span>
                                    </div>
                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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
            // Revenue Chart
            var revenueOptions = {
                series: [{
                    name: 'Doanh thu',
                    data: @json($revenueChart['data'])
                }],
                chart: { type: 'area', height: 310, toolbar: { show: false }, fontFamily: 'Space Grotesk, sans-serif' },
                colors: ['#667eea'],
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

            // User Growth Chart
            var userOptions = {
                series: [{
                    name: 'Users mới',
                    data: @json($userGrowthChart['data'])
                }],
                chart: { type: 'line', height: 310, toolbar: { show: false }, fontFamily: 'Space Grotesk, sans-serif' },
                colors: ['#43e97b'],
                stroke: { curve: 'smooth', width: 3 },
                markers: { size: 0, hover: { size: 5 } },
                xaxis: {
                    categories: @json($userGrowthChart['labels']),
                    labels: { style: { fontSize: '10px', colors: '#999' }, rotate: -45, rotateAlways: false },
                    tickAmount: 10
                },
                yaxis: {
                    labels: { style: { fontSize: '11px', colors: '#999' } }
                },
                tooltip: {
                    y: { formatter: function(v) { return v + ' users'; } }
                },
                dataLabels: { enabled: false },
                grid: { borderColor: '#f1f1f1', strokeDashArray: 4 },
            };
            new ApexCharts(document.querySelector("#userGrowthChart"), userOptions).render();
        });
    </script>
@endpush
