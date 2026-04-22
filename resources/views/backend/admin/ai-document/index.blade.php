@extends('backend.admin.master')

@section('content')
    <div class="page-content">

        @include('backend.section.breadcrumb', ['title' => 'Tài liệu AI', 'sub_title' => 'Quản lý tài liệu AI'])

        @if (session('success'))
            <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                <div class="text-white">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3 text-uppercase">Thêm tài liệu AI</h6>
                        <hr>
                        <!-- Form theo yêu cầu -->
                        <form method="POST" action="{{ route('admin.ai-documents.store') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="course_id" value="{{ $course->id ?? old('course_id') }}">
                            <input type="hidden" name="lecture_id" value="{{ $lecture->id ?? old('lecture_id') }}">

                            <div class="mb-3">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload file PDF / DOCX / TXT / MD</label>
                                <input type="file" name="file" class="form-control" accept=".pdf,.docx,.txt,.md">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hoặc nhập content thủ công</label>
                                <textarea name="content" class="form-control" rows="8">{{ old('content') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Lưu tài liệu AI</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 text-uppercase">Danh sách tài liệu</h6>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tiêu đề</th>
                                        <th>Khóa học</th>
                                        <th>Bài giảng</th>
                                        <th>Loại file</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($documents as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ optional($item->course)->title ?? $item->course_id }}</td>
                                            <td>{{ optional($item->lecture)->title ?? $item->lecture_id ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ strtoupper($item->source_type) }}</span>
                                            </td>
                                            <td>
                                                @if($item->index_status === 'indexed')
                                                    <span class="badge bg-success">Indexed</span>
                                                @elseif($item->index_status === 'failed')
                                                    <span class="badge bg-danger" title="{{ $item->index_error }}">Failed</span>
                                                @elseif($item->index_status === 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @else
                                                    <span class="badge bg-info text-dark">{{ ucfirst($item->index_status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(Route::has('admin.ai-documents.reindex'))
                                                <form action="{{ route('admin.ai-documents.reindex', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Re-index">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                                          <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                                          <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                                @endif

                                                @if(Route::has('admin.ai-documents.destroy'))
                                                <form action="{{ route('admin.ai-documents.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Chưa có tài liệu nào</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            @if(method_exists($documents, 'links'))
                                {{ $documents->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
