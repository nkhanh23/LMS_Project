@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', ['title' => 'Khóa học', 'sub_title' => 'Cập nhật khóa học'])

        <!--end breadcrumb-->
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body p-4">

                        <div style="display: flex; align-items:center; justify-content:space-between">
                            <h5 class="mb-4">Cập nhật khóa học</h5>
                            <a href="{{ route('instructor.course.index') }}" class="btn btn-primary px-4">Danh sách khóa
                                học</a>
                        </div>

                        <form class="row g-3" method="post" action="{{ route('instructor.course.update', $course->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <input type="hidden" name="instructor_id" value="{{ auth()->user()->id }}" />



                            <div class="col-md-6">
                                <label for="name" class="form-label">Tên khóa học</label>
                                <input type="text" class="form-control" name="course_name" id="name"
                                    placeholder="Enter the course name"
                                    value="{{ old('course_name', $course->course_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" name="course_name_slug" id="slug"
                                    placeholder="Enter the slug"
                                    value="{{ old('course_name_slug', $course->course_name_slug) }}" required>
                            </div>

                            <div class="col-md-12">
                                <label for="course_title" class="form-label">Tiêu đề </label>
                                <input type="text" class="form-control" name="course_title" id="course_title"
                                    placeholder="Enter the course title"
                                    value="{{ old('course_title', $course->course_title) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label">Danh mục cha</label>
                                <select class="form-select" name="category_id" id="category"
                                    data-placeholder="Chọn danh mục" required>
                                    <option value="" disabled selected>Chọn danh mục</option>
                                    @foreach ($all_categories as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $course->category_id ? 'selected' : '' }}>{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="subcategory" class="form-label">Danh mục con</label>
                                <select class="form-select" name="subcategory_id" id="subcategory"
                                    data-placeholder="Chọn danh mục con" data-selected="{{ $course->subcategory_id }}"
                                    required>
                                    <option value="" disabled selected>Chọn danh mục con</option>
                                </select>
                            </div>


                            <div class="col-md-6">
                                <label for="image" class="form-label">Hình ảnh</label>
                                <input type="file" class="form-control" name="image" id="Photo" accept="image/*">

                                <div style="margin-top: 10px">
                                    <img src="{{ asset($course->course_image) }}" id="photoPreview" class="img-fluid"
                                        style="margin-top: 15px; {{ $course->course_image ? '' : 'display:none' }}" />
                                </div>

                            </div>

                            <div class="col-md-6">
                                <label for="resources" class="form-label">Nguồn</label>
                                <input class="form-control" type="number" name="resources" id="resources"
                                    placeholder="Enter the Number of Download Resorce"
                                    value="{{ old('resources', $course->resources) }}" />
                            </div>



                            <div class="col-md-12">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control editor" name="description" id="description" required> {{ old('description', $course->description) }} </textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="video_url" class="form-label">YouTube Video URL</label>
                                <input type="url" class="form-control" name="video_url" id="video_url"
                                    placeholder="Enter the YouTube video URL"
                                    value="{{ old('video_url', $course->video_url) }}" required>
                                <iframe id="videoPreview"
                                    style="margin-top: 15px; display: none; width: 100%; height: 400px;" frameborder="0"
                                    allowfullscreen></iframe>
                            </div>


                            <div class="col-md-6">
                                <label for="label" class="form-label">Nhãn khóa học</label>
                                <select class="form-select" name="label" id="label"
                                    data-placeholder="Choose one thing">

                                    <option selected disabled>select</option>

                                    <option value="beginer"{{ $course->label == 'beginer' ? 'selected' : '' }}>Beginer
                                    </option>
                                    <option value="medium"{{ $course->label == 'medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="advance"{{ $course->label == 'advance' ? 'selected' : '' }}>Advance
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="certificate" class="form-label">Chứng chỉ</label>
                                <select class="form-select" name="certificate" id="certificate"
                                    data-placeholder="Choose one thing">

                                    <option selected disabled>select</option>

                                    <option value="yes" {{ $course->certificate == 'yes' ? 'selected' : '' }}>Có
                                    </option>
                                    <option value="no" {{ $course->certificate == 'no' ? 'selected' : '' }}>Không
                                    </option>


                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="selling_price" class="form-label">Giá</label>
                                <input type="number" class="form-control" name="selling_price" id="selling_price"
                                    placeholder="Enter selling price"
                                    value="{{ old('selling_price', $course->selling_price) }}" />
                            </div>

                            <div class="col-md-6">
                                <label for="discount_price" class="form-label">Khuyến mãi</label>
                                <input type="number" class="form-control" name="discount_price" id="discount_price"
                                    placeholder="Enter discount price"
                                    value="{{ old('discount_price', $course->discount_price) }}" />
                            </div>


                            <div class="col-md-6">
                                <label for="duration" class="form-label">Thời lượng (giờ)</label>
                                <input type="number" step="0.01" class="form-control" name="duration"
                                    id="duration" placeholder="Enter Course Duration"
                                    value="{{ old('duration', $course->duration) }}" />
                            </div>


                            <div class="col-md-12">
                                <label for="prerequisites" class="form-label">Course Prerequisites</label>
                                <textarea class="form-control editor" name="prerequisites" id="prerequisites"> {{ old('prerequisites', $course->prerequisites) }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="course_goal" class="form-label"
                                    style="display: flex; align-items:center; justify-content:space-between">
                                    Mục tiêu khóa học
                                    <button type="button" id="addGoalInput" class="btn btn-primary">+</button>
                                </label>
                                <div id="goalContainer">
                                    @foreach ($course_goals as $data)
                                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">

                                            <input type="text" class="form-control" name="course_goals[]"
                                                placeholder="Enter Course Goal" value="{{ $data->goal_name }}" />
                                            <button type="button" class="btn btn-danger removeGoalInput">-</button>


                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mt-3">
                                <div class="form-check form-check-success">
                                    <input type="hidden" name="bestseller" value="no">
                                    <input class="form-check-input" type="checkbox" id="flexCheckSuccess"
                                        style="cursor: pointer" value="yes"
                                        {{ $course->bestseller == 'yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckSuccess">bestseller</label>
                                </div>

                                <div class="form-check form-check-danger">
                                    <input type="hidden" name="featured" value="no">
                                    <input class="form-check-input" type="checkbox" id="flexCheckDanger"
                                        style="cursor: pointer" value="yes"
                                        {{ $course->featured == 'yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckDanger">featured</label>
                                </div>

                                <div class="form-check form-check-warning">
                                    <input type="hidden" name="highestrated" value="no">
                                    <input class="form-check-input" type="checkbox" id="flexCheckWarning"
                                        style="cursor: pointer" value="yes"
                                        {{ $course->highestrated == 'yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexCheckWarning">highestrated</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4 w-100">Cập nhật</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <!--end row-->
    </div>
@endsection

@push('script')
    <script src="{{ asset('customjs/instructor/course.js') }}"></script>

    <script>
        // Auto-load subcategories on page load and pre-select the saved value
        $(document).ready(function() {
            var categoryId = $('#category').val();
            var selectedSubcategoryId = parseInt($('#subcategory').data('selected'));

            if (categoryId) {
                $.ajax({
                    url: '/instructor/get-subcategories/' + categoryId,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#subcategory').empty();
                        $('#subcategory').append(
                        '<option value="" disabled>Chọn danh mục con</option>');
                        $.each(data, function(key, value) {
                            var selected = (parseInt(value.id) === selectedSubcategoryId) ?
                                ' selected' : '';
                            $('#subcategory').append('<option value="' + parseInt(value.id) +
                                '"' + selected + '>' + value.name + '</option>');
                        });
                    },
                    error: function() {
                        alert('Lỗi khi tải danh mục con');
                    }
                });
            }
        });
    </script>
    <script src="{{ asset('customjs/instructor/photoReview.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoUrlField = document.getElementById('video_url');
            const videoPreview = document.getElementById('videoPreview');

            // Initialize iframe with existing video URL from database
            const initialVideoUrl = videoUrlField.value; // Get the initial value from the input
            if (initialVideoUrl) {
                const videoId = extractYouTubeID(initialVideoUrl);
                if (videoId) {
                    videoPreview.src = `https://www.youtube.com/embed/${videoId}`;
                    videoPreview.style.display = 'block';
                }
            }

            // Update iframe on input change
            videoUrlField.addEventListener('input', function() {
                const videoUrl = this.value;
                if (videoUrl) {
                    const videoId = extractYouTubeID(videoUrl);
                    if (videoId) {
                        videoPreview.src = `https://www.youtube.com/embed/${videoId}`;
                        videoPreview.style.display = 'block';
                    } else {
                        alert('Invalid YouTube URL');
                        videoPreview.style.display = 'none';
                        videoPreview.src = '';
                    }
                } else {
                    videoPreview.style.display = 'none';
                    videoPreview.src = '';
                }
            });

            // Function to extract YouTube video ID
            function extractYouTubeID(url) {
                const regex =
                    /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
                const match = url.match(regex);
                return match ? match[1] : null;
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".form-check-input").forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    let hiddenInput = this.previousElementSibling; // Hidden input
                    hiddenInput.value = this.checked ? "yes" :
                        "no"; // Set value based on checked state
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            CKEDITOR.replace('description', {
                height: 360
            });
        });
    </script>
@endpush
