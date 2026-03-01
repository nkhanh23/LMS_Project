@extends('backend.admin.master')

@section('content')
    <div class="page-content">

        @include('backend.section.breadcrumb', [
            'title' => 'Slider',
            'sub_title' => 'Thêm Slider',
        ])

        <!--end breadcrumb-->
        <div class="row">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-body p-4">

                        <div style="display: flex; align-items:center; justify-content:space-between">
                            <h5 class="mb-4">Thêm Slider</h5>
                            <a href="{{ route('admin.slider.index') }}" class="btn btn-primary px-4">Danh sách
                                Slider</a>
                        </div>

                        <form class="row g-3" method="post" action="{{ route('admin.slider.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <label for="slider_title" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" name="title" id="slider_title"
                                    placeholder="Tiêu đề" value="{{ old('title') }}" required>
                            </div>

                            <div class="col-md-12">
                                <label for="short_description" class="form-label">Mô tả ngắn</label>
                                <textarea class="form-control" name="short_description" id="short_description" placeholder="Mô tả ngắn"></textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="video_url" class="form-label">Youtube Video URL</label>
                                <input type="url" class="form-control" name="video_url" id="video_url"
                                    placeholder="Youtube Video URL" value="{{ old('video_url') }}" required>
                            </div>

                            <div class="col-md-12">
                                <iframe id="videoPreview"
                                    style="margin-top: 15px; display: none; width: 50%; height: 400px;" frameborder="0"
                                    allowfullscreen></iframe>
                            </div>

                            <div class="col-md-12">
                                <label for="image" class="form-label">Background Image</label>
                                <input type="file" class="form-control" name="image" id="Photo" accept="image/*">
                            </div>
                            <div class="col-md-12">
                                <img src="" id="photoPreview" style="margin-top: 15px; display: none;"
                                    class="img-fluid" />
                            </div>

                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4 w-100">Thêm</button>
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
    <script>
        document.getElementById('video_url').addEventListener('input', function() {
            const videoUrl = this.value; // Get the YouTube URL from the input field
            const videoPreview = document.getElementById('videoPreview'); // Get the iframe element

            if (videoUrl) {
                // Extract YouTube video ID from the URL
                const videoId = extractYouTubeID(videoUrl);
                if (videoId) {
                    // Set the iframe src to embed the YouTube video
                    videoPreview.src = `https://www.youtube.com/embed/${videoId}`;
                    videoPreview.style.display = 'block';
                } else {
                    alert('Invalid YouTube URL');
                    videoPreview.style.display = 'none';
                    videoPreview.src = '';
                }
            } else {
                // Hide the iframe if the input is empty
                videoPreview.style.display = 'none';
                videoPreview.src = '';
            }
        });

        // Function to extract YouTube video ID from URL
        function extractYouTubeID(url) {
            const regex =
                /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
            const match = url.match(regex);
            return match ? match[1] : null;
        }
    </script>

    <script src="{{ asset('customjs/admin/photoReviewCate.js') }}"></script>
@endpush
