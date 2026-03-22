@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!--breadcrumb-->
            @include('backend.section.breadcrumb', [
                'title' => 'Lịch sử hoạt động',
                'sub_title' => 'Danh sách hoạt động',
            ])
            <div style="display: flex; align-items:center; justify-content:space-between">
                <h6 class="mb-0 text-uppercase">Danh sách hoạt động</h6>
            </div>

            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Action</label>
                    <input type="text" name="action" class="form-control" value="{{ request('action') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Target type</label>
                    <input type="text" name="target_type" class="form-control" value="{{ request('target_type') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary me-2">Lọc</button>
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Admin</th>
                                    <th>Action</th>
                                    <th>Target</th>
                                    <th>Note</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>#{{ $log->id }}</td>
                                        <td>{{ $log->admin->name ?? 'N/A' }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->target_type }} #{{ $log->target_id }}</td>
                                        <td>{{ $log->note ?: '---' }}</td>
                                        <td>{{ $log->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Chưa có audit log nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
