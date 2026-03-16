@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        @include('backend.section.breadcrumb', [
            'title' => 'Thảo luận',
            'sub_title' => 'Chi tiết thảo luận',
        ])

        <div class="row">
            <div class="col-md-12">

                @if (session('success'))
                    <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                        <div class="text-white">{{ session('success') }}</div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                        <div class="text-white">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                            <div>
                                <h5 class="mb-1">Chi tiết thảo luận</h5>
                                @if ($discussion->parent_id)
                                    <span class="badge bg-info">Đây là reply discussion</span>
                                @else
                                    <span class="badge bg-primary">Đây là root discussion</span>
                                @endif
                            </div>

                            <a href="{{ route('instructor.lecture-discussions.index') }}" class="btn btn-primary px-4">
                                Danh sách thảo luận
                            </a>
                        </div>

                        {{-- Thông tin discussion hiện tại --}}
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Thông tin discussion hiện tại</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <strong>ID:</strong> {{ $discussion->id }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Loại:</strong> {{ $discussion->parent_id ? 'Reply' : 'Root' }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Người dùng:</strong> {{ $discussion->user->name ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Trạng thái:</strong>
                                        @if ($discussion->is_approved)
                                            <span class="badge bg-success">Đã duyệt</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Ẩn</span>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <strong>Email:</strong> {{ $discussion->user->email ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Ngày tạo:</strong> {{ $discussion->created_at }}
                                    </div>

                                    <div class="col-md-6">
                                        <strong>Khóa học:</strong> {{ $discussion->course->course_name ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Lecture:</strong>
                                        {{ $discussion->lecture->lecture_title ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nội dung discussion hiện tại --}}
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Nội dung discussion hiện tại</h6>
                            </div>
                            <div class="card-body">
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                        <div>
                                            <strong>{{ $discussion->user->name ?? 'N/A' }}</strong>
                                            <small class="text-muted"> - {{ $discussion->created_at }}</small>
                                        </div>

                                        <div>
                                            @if ($discussion->is_approved)
                                                <span class="badge bg-success">Đã duyệt</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Ẩn</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div style="white-space: pre-line;">
                                        {{ $discussion->content }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nếu là reply thì hiển thị parent --}}
                        @if ($discussion->parent)
                            <div class="card border mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Discussion cha</h6>
                                    <a href="{{ route('instructor.lecture-discussions.show', $discussion->parent->id) }}"
                                        class="btn btn-sm btn-info">
                                        Xem parent
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="border rounded p-3">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                            <div>
                                                <strong>{{ $discussion->parent->user->name ?? 'N/A' }}</strong>
                                                <small class="text-muted"> - ID: {{ $discussion->parent->id }}</small>
                                            </div>
                                        </div>

                                        <div style="white-space: pre-line;">
                                            {{ $discussion->parent->content }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Hành động --}}
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Hành động</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    @if ($discussion->is_approved)
                                        <form
                                            action="{{ route('instructor.lecture-discussions.unapprove', $discussion->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-warning">Ẩn discussion</button>
                                        </form>
                                    @else
                                        <form
                                            action="{{ route('instructor.lecture-discussions.approve', $discussion->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-success">Duyệt discussion</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('instructor.lecture-discussions.destroy', $discussion->id) }}"
                                        method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-delete">Xóa discussion</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Replies con --}}
                        @if ($discussion->replies && $discussion->replies->count())
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Replies con ({{ $discussion->replies->count() }})</h6>
                                </div>
                                <div class="card-body">
                                    @foreach ($discussion->replies as $reply)
                                        <div class="border rounded p-3 mb-3">
                                            <div
                                                class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                                <div>
                                                    <strong>{{ $reply->user->name ?? 'N/A' }}</strong>
                                                    <small class="text-muted"> - {{ $reply->created_at }}</small>
                                                </div>

                                                <div>
                                                    @if ($reply->is_approved)
                                                        <span class="badge bg-success">Đã duyệt</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Ẩn</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-3" style="white-space: pre-line;">
                                                {{ $reply->content }}
                                            </div>

                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('instructor.lecture-discussions.show', $reply->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    Xem
                                                </a>

                                                @if ($reply->is_approved)
                                                    <form
                                                        action="{{ route('instructor.lecture-discussions.unapprove', $reply->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="btn btn-sm btn-warning">Ẩn</button>
                                                    </form>
                                                @else
                                                    <form
                                                        action="{{ route('instructor.lecture-discussions.approve', $reply->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="btn btn-sm btn-success">Duyệt</button>
                                                    </form>
                                                @endif

                                                <form
                                                    action="{{ route('instructor.lecture-discussions.destroy', $reply->id) }}"
                                                    method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger btn-delete">Xóa</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Form reply --}}
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Phản hồi của instructor</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('instructor.lecture-discussions.reply', $discussion->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Nội dung phản hồi</label>
                                        <textarea name="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                let form = $(this).closest('.delete-form');

                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Dữ liệu bị xóa sẽ không thể khôi phục!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, xóa nó!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
