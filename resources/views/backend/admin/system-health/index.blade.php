@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3">Hệ thống</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-server"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Giám sát sức khỏe (System Health)</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- 1. Background Jobs / Queue Stats -->
        <h6 class="mb-3 text-uppercase">1. Trạng thái Queue & Tiến trình ngầm</h6>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 bg-primary bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Pending Queues</p>
                                <h4 class="my-1 text-white">{{ $healthData['queue']['pending_jobs'] }}</h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class='bx bx-time-five'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div
                    class="card radius-10 {{ $healthData['queue']['failed_jobs'] > 0 ? 'bg-danger' : 'bg-success' }} bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Failed Jobs</p>
                                <h4 class="my-1 text-white">{{ $healthData['queue']['failed_jobs'] }}</h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class='bx bx-error-circle'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Transcript AI</p>
                                <h4 class="my-1 text-info">{{ $healthData['process']['transcript']['processing'] }} Đang
                                    chạy</h4>
                                <p class="mb-0 font-13 text-danger"><i class="bx bx-x"></i>
                                    {{ $healthData['process']['transcript']['failed'] }} Failed</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-info text-white ms-auto"><i
                                    class='bx bx-microphone'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Document Indexing (RAG)</p>
                                <h4 class="my-1 text-warning">{{ $healthData['process']['ai_document']['processing'] }} Đang
                                    chạy</h4>
                                <p class="mb-0 font-13 text-danger"><i class="bx bx-x"></i>
                                    {{ $healthData['process']['ai_document']['failed'] }} Failed</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                    class='bx bx-file'></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- 3. Failed Jobs Log -->
        @if (count($healthData['queue']['recent_failed']) > 0)
            <div class="card radius-10 mt-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">Lịch sử Failed Jobs gần đây</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Connection</th>
                                    <th>Queue</th>
                                    <th>Failed At</th>
                                    <th>Exception (Snippet)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($healthData['queue']['recent_failed'] as $failed)
                                    <tr>
                                        <td>{{ $failed->connection }}</td>
                                        <td>{{ $failed->queue }}</td>
                                        <td>{{ \Carbon\Carbon::parse($failed->failed_at)->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            <span class="d-inline-block text-truncate text-danger"
                                                style="max-width: 400px;" title="{{ $failed->exception }}">
                                                {{ \Illuminate\Support\Str::limit($failed->exception, 80) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
