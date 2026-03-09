@extends('backend.admin.master')

<style>
    .form-check-input {
        width: 2.5rem;
        /* Adjust the width */
        height: 1.5rem;
        /* Adjust the height */
        transform: scale(1.3);
        /* Scale the entire switch */
    }
</style>

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Khóa học',
            'sub_title' => 'Danh sách khóa học',
        ])
        <!--end breadcrumb-->

        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Ảnh</th>
                                <th>Tên khóa học</th>
                                <th>Giảng viên</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Hiển thị</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_courses as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if ($item->course_image)
                                            <img src="{{ asset($item->course_image) }}" width="140" height="80" />
                                        @else
                                            <span>Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->course_name }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td>
                                        @if ($item->discount_price)
                                            <span>${{ $item->discount_price }}</span>
                                        @else
                                            <span>${{ $item->selling_price }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.course.show', $item->id) }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                <path
                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                            </svg>

                                        </a>

                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" style="cursor: pointer" type="checkbox"
                                                role="switch" id="flexSwitchCheckDefault{{ $item->id }}"
                                                data-course-id="{{ $item->id }}"
                                                {{ $item->status == 1 ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>
        </div>


    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.form-check-input').on('change', function() {
                const courseId = $(this).data('course-id');

                const status = $(this).is(':checked') ? 1 : 0; //(1: Active, 0: Inactive)
                const row = $(this).closest('tr'); // Tìm dòng cha của checkbox

                $.ajax({
                    url: '{{ route('admin.course.status') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        course_id: courseId,
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            // Cập nhật trạng thái
                            const statusBadge = row.find('td:nth-child(6) .badge');
                            if (status === 1) {
                                statusBadge
                                    .removeClass('bg-danger')
                                    .addClass('bg-primary')
                                    .text('Active');
                            } else {
                                statusBadge
                                    .removeClass('bg-primary')
                                    .addClass('bg-danger')
                                    .text('Inactive');
                            }

                            // Hiển thị thông báoSweetAlert Toast Notification
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        } else {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Error: ' + response.message,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Lỗi: ' + error,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });
        });
    </script>
@endpush
