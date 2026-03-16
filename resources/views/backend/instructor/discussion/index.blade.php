@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', ['title' => 'Thảo luận', 'sub_title' => 'Tất cả thảo luận'])
        <!--end breadcrumb-->
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-uppercase">Tất cả thảo luận</h6>
        </div>
        <hr />
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('instructor.lecture-discussions.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="course_id" id="course_id" class="form-select">
                                <option value="">Tất cả khóa học</option>
                                @foreach ($filterData['courses'] as $course)
                                    <option value="{{ $course->id }}"
                                        {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="lecture_id" id="lecture_id" class="form-select">
                                <option value="">Tất cả bài học</option>
                                @if (request('course_id'))
                                    @foreach ($filterData['lectures'] as $lecture)
                                        <option value="{{ $lecture->id }}"
                                            {{ request('lecture_id') == $lecture->id ? 'selected' : '' }}>
                                            {{ $lecture->lecture_title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="is_approved" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1" {{ request('is_approved') === '1' ? 'selected' : '' }}>Đã duyệt
                                </option>
                                <option value="0" {{ request('is_approved') === '0' ? 'selected' : '' }}>Ẩn/Chưa duyệt
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                                placeholder="Tìm nội dung">
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Lọc</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>User</th>
                                <th>Course</th>
                                <th>Lecture</th>
                                <th>Nội dung</th>
                                <th>Loại</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($discussions as $index => $discussion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $discussion->user->name ?? 'N/A' }}</td>
                                    <td>{{ $discussion->course->course_name ?? 'N/A' }}</td>
                                    <td>{{ $discussion->lecture->lecture_title ?? 'N/A' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($discussion->content, 80) }}</td>
                                    <td>{{ $discussion->parent_id ? 'Reply' : 'Root' }}</td>
                                    <td>
                                        @if ($discussion->is_approved)
                                            <span class="badge bg-success">Đã duyệt</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>{{ $discussion->created_at }}</td>
                                    <td>
                                        <a href="{{ route('instructor.lecture-discussions.show', $discussion->id) }}"
                                            class="btn btn-sm btn-info">
                                            Xem
                                        </a>

                                        @if ($discussion->is_approved)
                                            <form
                                                action="{{ route('instructor.lecture-discussions.unapprove', $discussion->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-warning">Ẩn</button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ route('instructor.lecture-discussions.approve', $discussion->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-success">Duyệt</button>
                                            </form>
                                        @endif

                                        <form
                                            action="{{ route('instructor.lecture-discussions.destroy', $discussion->id) }}"
                                            method="POST" style="display:inline-block;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Chưa có bình luận nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $discussions->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- Xóa thảo luận --}}
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

    {{-- Lấy danh sách bài học theo khóa học --}}
    <script>
        $(document).ready(function() {
            $('#course_id').on('change', function() {
                let courseId = $(this).val();
                let lectureSelect = $('#lecture_id');

                lectureSelect.html('<option value="">Tất cả lecture</option>');

                if (!courseId) {
                    return;
                }

                $.ajax({
                    url: "{{ route('instructor.lecture-discussions.lectures-by-course') }}",
                    type: "GET",
                    data: {
                        course_id: courseId
                    },
                    success: function(response) {
                        if (response.status && response.data.length > 0) {
                            $.each(response.data, function(index, lecture) {
                                lectureSelect.append(
                                    `<option value="${lecture.id}">${lecture.lecture_title}</option>`
                                );
                            });
                        }
                    },
                    error: function() {
                        lectureSelect.html('<option value="">Tất cả lecture</option>');
                        alert('Không thể tải danh sách lecture.');
                    }
                });
            });
        });
    </script>
@endpush
