@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <h4 class="mb-4">Chi tiết khóa học</h4>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Kiểm tra chất lượng khóa học</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($qualityChecks as $check)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $check['check_key'] }}</span>
                                @if ($check['status'] === 'pass')
                                    <span class="badge bg-success">PASS</span>
                                @else
                                    <span class="badge bg-danger">FAIL</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
@endsection
