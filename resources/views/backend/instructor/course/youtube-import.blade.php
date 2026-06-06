@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        @include('backend.section.breadcrumb', ['title' => 'Khóa học', 'sub_title' => 'Import YouTube Playlist'])

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="mb-0">Import YouTube Playlist</h5>
                            <a href="{{ route('instructor.course.index') }}" class="btn btn-primary px-4">Danh sách khóa học</a>
                        </div>

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="row g-3" method="post" action="{{ route('instructor.youtube-playlist-import.store') }}">
                            @csrf

                            <div class="col-md-6">
                                <label for="youtube_api_key" class="form-label">YouTube API Key</label>
                                <input type="password" class="form-control" name="youtube_api_key" id="youtube_api_key"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label for="playlist_url" class="form-label">Link danh sách phát</label>
                                <input type="url" class="form-control" name="playlist_url" id="playlist_url"
                                    placeholder="https://www.youtube.com/playlist?list=..."
                                    value="{{ old('playlist_url') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="course_name" class="form-label">Tên khóa học</label>
                                <input type="text" class="form-control" name="course_name" id="course_name"
                                    value="{{ old('course_name') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="course_title" class="form-label">Tiêu đề khóa học</label>
                                <input type="text" class="form-control" name="course_title" id="course_title"
                                    value="{{ old('course_title') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label">Danh mục cha</label>
                                <select class="form-select" name="category_id" id="category" required>
                                    <option value="" disabled selected>Chọn danh mục</option>
                                    @foreach ($all_categories as $item)
                                        <option value="{{ $item->id }}" @selected((string) old('category_id') === (string) $item->id)>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="subcategory" class="form-label">Danh mục con</label>
                                <select class="form-select" name="subcategory_id" id="subcategory"
                                    data-selected="{{ old('subcategory_id') }}" required>
                                    <option value="" disabled selected>Chọn danh mục con</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="section_title" class="form-label">Tên chương</label>
                                <input type="text" class="form-control" name="section_title" id="section_title"
                                    placeholder="YouTube Playlist" value="{{ old('section_title') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="selling_price" class="form-label">Giá</label>
                                <input type="number" class="form-control" name="selling_price" id="selling_price"
                                    value="{{ old('selling_price', 0) }}" min="0" required>
                            </div>

                            <div class="col-md-3">
                                <label for="discount_price" class="form-label">Khuyến mãi</label>
                                <input type="number" class="form-control" name="discount_price" id="discount_price"
                                    value="{{ old('discount_price') }}" min="0">
                            </div>

                            <div class="col-md-4">
                                <label for="label" class="form-label">Nhãn khóa học</label>
                                <select class="form-select" name="label" id="label">
                                    <option value="">Chọn nhãn</option>
                                    <option value="beginer" @selected(old('label') === 'beginer')>Beginer</option>
                                    <option value="medium" @selected(old('label') === 'medium')>Medium</option>
                                    <option value="advance" @selected(old('label') === 'advance')>Advance</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="certificate" class="form-label">Chứng chỉ</label>
                                <select class="form-select" name="certificate" id="certificate">
                                    <option value="no" @selected(old('certificate', 'no') === 'no')>Không</option>
                                    <option value="yes" @selected(old('certificate') === 'yes')>Có</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="max_videos" class="form-label">Số video tối đa</label>
                                <input type="number" class="form-control" name="max_videos" id="max_videos"
                                    value="{{ old('max_videos', 100) }}" min="1" max="200">
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" id="description" rows="5">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary px-4 w-100">Import playlist</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('customjs/instructor/course.js') }}"></script>
@endpush
