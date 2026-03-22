@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!--breadcrumb-->
            @include('backend.section.breadcrumb', [
                'title' => 'Báo cáo vi phạm',
                'sub_title' => 'Danh sách báo cáo',
            ])
            <div style="display: flex; align-items:center; justify-content:space-between">
                <h6 class="mb-0 text-uppercase">Danh sách báo cáo vi phạm</h6>
            </div>

            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>Reviewing
                        </option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Loại nội dung</label>
                    <select name="reportable_type" class="form-control">
                        <option value="">-- Tất cả --</option>
                        <option value="course_review" {{ request('reportable_type') == 'course_review' ? 'selected' : '' }}>
                            Review</option>
                        <option value="lecture_discussion"
                            {{ request('reportable_type') == 'lecture_discussion' ? 'selected' : '' }}>Discussion</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Lý do</label>
                    <select name="reason_code" class="form-control">
                        <option value="">-- Tất cả --</option>
                        <option value="spam" {{ request('reason_code') == 'spam' ? 'selected' : '' }}>Spam</option>
                        <option value="abuse" {{ request('reason_code') == 'abuse' ? 'selected' : '' }}>Abuse</option>
                        <option value="harassment" {{ request('reason_code') == 'harassment' ? 'selected' : '' }}>Harassment
                        </option>
                        <option value="hate_speech" {{ request('reason_code') == 'hate_speech' ? 'selected' : '' }}>Hate
                            speech</option>
                        <option value="adult" {{ request('reason_code') == 'adult' ? 'selected' : '' }}>Adult</option>
                        <option value="misinformation" {{ request('reason_code') == 'misinformation' ? 'selected' : '' }}>
                            Misinformation</option>
                        <option value="other" {{ request('reason_code') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary me-2">Lọc</button>
                    <a href="{{ route('admin.moderation.reports.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Reporter</th>
                                    <th>Reported User</th>
                                    <th>Type</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td>#{{ $report->id }}</td>
                                        <td>{{ $report->reporter->name ?? 'N/A' }}</td>
                                        <td>{{ $report->reportedUser->name ?? 'N/A' }}</td>
                                        <td>{{ $report->reportable_type }}</td>
                                        <td>{{ $report->reason_code }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $report->status }}</span>
                                        </td>
                                        <td>{{ $report->created_at }}</td>
                                        <td>
                                            <a href="{{ route('admin.moderation.reports.show', $report->id) }}"
                                                class="btn btn-sm btn-primary">
                                                Xem
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Chưa có report nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
