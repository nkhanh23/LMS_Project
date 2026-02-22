@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb')

        <!--end breadcrumb-->
        <div class="row">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-body p-4">

                        <div style="display: flex; align-items:center; justify-content:space-between">
                            <h5 class="mb-4">Cập nhật danh mục</h5>
                            <a href="{{ route('admin.category.index') }}" class="btn btn-primary px-4">Quay lại</a>
                        </div>

                        <form class="row g-3" method="POST" action="{{ route('admin.category.update', $category->id) }}"
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

                            <div class="col-md-6">
                                <label for="name" class="form-label">Tên danh mục</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Tên danh mục" value="{{ $category->name }}">
                            </div>
                            <div class="col-md-6">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug"
                                    value="{{ $category->slug }}">
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Hình ảnh</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>

                            <div class="col-md-6">
                                <img src="{{ $category->image ? asset($category->image) : '' }}" id="photoReview"
                                    width="60px" height="60px" style="margin-top: 15px; display: block;">
                            </div>
                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
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
    <script src="{{ asset('customjs/admin/category.js') }}"></script>
    <script src="{{ asset('customjs/admin/photoReviewCate.js') }}"></script>
@endpush
