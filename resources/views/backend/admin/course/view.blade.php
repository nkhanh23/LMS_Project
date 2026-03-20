@extends('backend.admin.master')



@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Khóa học',
            'sub_title' => 'Chi tiết khóa học',
        ])
        <!--end breadcrumb-->
        <div style="display: flex; align-items:center; justify-content:space-between">
            <h6 class="mb-0 text-uppercase">Chi tiết khóa học</h6>

            <a href="{{ route('admin.course.index') }}" class="btn btn-primary px-5">Quay lại</a>

        </div>

        <hr />

        <div class="row g-4">

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item align-items-center">
                                <h6> Tên khóa học</h6>

                                <span class="">
                                    {{ $course->course_name }}
                                </span>
                            </li>
                            <li class="list-group-item  align-items-center">
                                <h6>Course Title</h6>


                                <span class="">
                                    {{ $course->course_title }}
                                </span>
                            </li>
                            <li class="list-group-item align-items-center">
                                <h6>Danh mục</h6>

                                <span class="">{{ $course->category->name }}</span>
                            </li>
                            <li class="list-group-item align-items-center">
                                <h6>
                                    Danh mục con
                                </h6>

                                <span class="">
                                    {{ $course->subCategory->name }}
                                </span>
                            </li>
                            <li class="list-group-item align-items-center">
                                <h6>Giảng viên</h6>

                                <span class="">
                                    {{ $course->user->name }}
                                </span>
                            </li>

                            <li class="list-group-item align-items-center">
                                <h6>Trạng thái</h6>

                                <span class="">
                                    @if ($course->status == 0)
                                        Không hoạt động
                                    @else
                                        Hoạt động
                                    @endif

                                </span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h6>Giá bán</h6>

                                <span class="" style="font-size: 17px">
                                    ${{ $course->selling_price }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h6>Giá giảm</h6>

                                <span class="" style="font-size: 17px">
                                    ${{ $course->discount_price }}
                                </span>
                            </li>


                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item  align-items-center">
                                <h6>Video giới thiệu</h6>


                                @if (!empty($course->video_url))
                                    @php
                                        // Tìm video id từ youtube url (hỗ trợ watch?v=... hoặc youtu.be/...)
                                        preg_match(
                                            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
                                            $course->video_url,
                                            $matches,
                                        );
                                        $video_id = isset($matches[1]) ? $matches[1] : '';
                                    @endphp
                                    @if ($video_id)
                                        <iframe width="100%" height="300" style="border-radius: 3px"
                                            src="https://www.youtube.com/embed/{{ $video_id }}" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                    @else
                                        <p>URL video không hợp lệ</p>
                                    @endif
                                @else
                                    <p>Không có video</p>
                                @endif

                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h6>Thư viện</h6>

                                <span class="" style="font-size: 20px">{{ $course->resources }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h6>Giấy chứng nhận</h6>

                                <span class="" style="font-size: 20px">
                                    @if ($course->certificate == 'yes')
                                        Yes
                                    @else
                                        No
                                    @endif
                                </span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
