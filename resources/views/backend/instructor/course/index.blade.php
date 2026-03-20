@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', ['title' => 'Khóa học', 'sub_title' => 'Tất cả khóa học'])
        <!--end breadcrumb-->
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-uppercase">Tất cả khóa học</h6>
            <a href="{{ route('instructor.course.create') }}" class="btn btn-primary">Thêm khóa học</a>
        </div>
        <hr />

        <div class="card">
            <div class="card-body">
                <form action="{{ route('instructor.course.index') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên khóa học..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">Tất cả danh mục</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3" id="sub_category_container">
                        <label class="form-label">Danh mục con</label>
                        <select name="sub_category_id" id="sub_category_id" class="form-select"
                            {{ request('category_id') ? '' : 'disabled' }}>
                            <option value="">Tất cả danh mục con</option>
                            @foreach ($subcategories as $subcat)
                                <option value="{{ $subcat->id }}"
                                    {{ request('sub_category_id') == $subcat->id ? 'selected' : '' }}>{{ $subcat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Khoảng giá (VND/USD): <span id="amount-show"
                                class="text-danger"></span></label>
                        <div id="slider-range" class="mt-2 mb-3"></div>

                        <input type="hidden" name="min_amount" id="min_amount" value="{{ $filters['min_amount'] ?? 0 }}">
                        <input type="hidden" name="max_amount" id="max_amount"
                            value="{{ $filters['max_amount'] ?? 10000000 }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Lọc</button>
                        <a href="{{ route('instructor.course.index') }}" class="btn btn-secondary">Xóa lọc</a>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Thumbnail</th>
                                <th>Tên khóa học</th>
                                <th>Danh mục</th>
                                <th>Danh mục con</th>
                                <th>Giá</th>
                                <th>Giá khuyến mãi</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_courses as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if ($item->course_image)
                                            <img src="{{ asset($item->course_image) }}" width="140" height="70" />
                                        @else
                                            <span>Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->course_name }}
                                    </td>

                                    <td>{{ $item->category->name }}</td>
                                    <td>
                                        {{ $item->subcategory->name }}
                                    </td>
                                    <td>
                                        {{ $item->selling_price }}
                                    </td>

                                    <td>
                                        {{ $item->discount_price }}
                                    </td>
                                    <td>
                                        @if ($item->approval_status === 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif($item->approval_status === 'pending_review')
                                            <span class="badge bg-warning text-dark">Pending Review</span>
                                        @elseif($item->approval_status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif($item->approval_status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($item->approval_status === 'hidden')
                                            <span class="badge bg-dark">Hidden</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->approval_note ?? '---' }}</td>

                                    <td>
                                        <a href="{{ route('instructor.course.edit', $item->id) }}" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('instructor.course.destroy', $item->id) }}"
                                            class="btn btn-danger delete-course" data-id="{{ $item->id }}"
                                            style="margin-left: 10px">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                            </svg>
                                        </a>

                                        <form id="delete-form" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <a href="{{ route('instructor.course-section.show', $item->id) }}"
                                            class="btn btn-success" style="margin-left:10px" title="Show Sections">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                                <path
                                                    d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                                                <path
                                                    d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                                            </svg>
                                        </a>
                                        @if (in_array($item->approval_status, ['draft', 'rejected'], true))
                                            <form method="POST"
                                                action="{{ route('instructor.course.submit-review', $item->id) }}"
                                                style="display: inline-block; margin-left:10px;">
                                                @csrf
                                                <button class="btn btn-warning btn-sm" title="Submit for review">
                                                    <i class="bx bx-send"></i> Submit
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $all_courses->appends(request()->query())->links() }}
                </div>
            </div>
        </div>


    </div>
@endsection

@push('script')
    {{-- Delete Course Script --}}
    <script>
        $(document).on('click', '.delete-course', function(e) {
            e.preventDefault();

            let courseId = $(this).data('id');
            let deleteUrl = "{{ route('instructor.course.destroy', ':id') }}".replace(':id', courseId);

            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa?',
                text: "Bạn sẽ không thể khôi phục lại!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Có!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete-form').attr('action', deleteUrl).submit();
                }
            });
        });
    </script>
    {{-- Price Range Script --}}
    <script>
        $(document).ready(function() {
            // Lấy giá trị min/max từ hidden input (đã có từ URL nếu user đang ở trạng thái lọc)
            let currentMin = parseInt($('#min_amount').val()) || 0;
            let currentMax = parseInt($('#max_amount').val()) || 10000; // Thay 10000 bằng Max Price hệ thống

            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 10000000, // Cấu hình Max Limit của thanh trượt
                step: 10, // Bước nhảy của thanh trượt
                values: [currentMin, currentMax],
                slide: function(event, ui) {
                    // Khi kéo trượt, update Text hiển thị cho user
                    $("#amount-show").text("VNĐ " + ui.values[0].toLocaleString() + " - VNĐ " + ui
                        .values[1].toLocaleString());
                    // Gắn value vào hidden input để đẩy lên URL
                    $("#min_amount").val(ui.values[0]);
                    $("#max_amount").val(ui.values[1]);
                }
            });

            // Khởi tạo text hiển thị khi trang vừa load xong
            $("#amount-show").text("VNĐ " + $("#slider-range").slider("values", 0).toLocaleString() +
                " - VNĐ " + $("#slider-range").slider("values", 1).toLocaleString());
        });
    </script>
    {{-- Dependent Category/Subcategory Script --}}
    <script>
        $(document).ready(function() {
            $('#category_id').on('change', function() {
                var categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: "{{ url('/instructor/get-subcategories') }}/" + categoryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#sub_category_id').empty();
                            $('#sub_category_id').append(
                                '<option value="">Tất cả danh mục con</option>');
                            $.each(data, function(key, value) {
                                $('#sub_category_id').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                            $('#sub_category_id').prop('disabled', false);
                        },
                    });
                } else {
                    $('#sub_category_id').empty();
                    $('#sub_category_id').append('<option value="">Tất cả danh mục con</option>');
                    $('#sub_category_id').prop('disabled', true);
                }
            });
        });
    </script>
@endpush
