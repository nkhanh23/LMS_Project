@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            @include('backend.section.breadcrumb', [
                'title' => 'Báo cáo vi phạm',
                'sub_title' => 'Chi tiết báo cáo',
            ])
            <div style="display: flex; align-items:center; justify-content:space-between">
                <h6 class="mb-0 text-uppercase">Chi tiết báo cáo #{{ $report->id }}</h6>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ collect($errors->all())->first() }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card border mb-4">
                        <div class="card-header bg-light">
                            <strong>Thông tin report</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Reporter:</strong> {{ $report->reporter->name ?? 'N/A' }}</p>
                            <p><strong>Reported user:</strong> {{ $report->reportedUser->name ?? 'N/A' }}</p>
                            <p><strong>Type:</strong> {{ $report->reportable_type }}</p>
                            <p><strong>Reason:</strong> {{ $report->reason_code }}</p>
                            <p><strong>Description:</strong> {{ $report->description ?: '---' }}</p>
                            <p><strong>Status:</strong> {{ $report->status }}</p>
                            <p><strong>Reviewed by:</strong> {{ $report->reviewer->name ?? '---' }}</p>
                            <p><strong>Reviewed at:</strong> {{ $report->reviewed_at ?? '---' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border mb-4">
                        <div class="card-header bg-light">
                            <strong>Snapshot nội dung</strong>
                        </div>
                        <div class="card-body">
                            <pre style="white-space: pre-wrap">{{ json_encode($report->content_snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border">
                <div class="card-header bg-light">
                    <strong>Xử lý report</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.moderation.resolve', $report->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Policy</label>
                            <select name="policy_id" class="form-select" required>
                                <option value="">-- Chọn policy --</option>
                                @foreach($policies as $policy)
                                    <option value="{{ $policy->id }}">{{ $policy->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Action Template</label>
                            <select name="action_template_id" class="form-select" required>
                                <option value="">-- Chọn action --</option>
                                @foreach($actionTemplates as $action)
                                    <option value="{{ $action->id }}">{{ $action->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Resolution Note</label>
                            <textarea name="resolution_note" class="form-control" rows="4"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Resolve Report</button>
                        <a href="{{ route('admin.moderation.reports.index') }}" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
