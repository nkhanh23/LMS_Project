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
                            <h5 class="mb-4">Cập nhật danh mục con</h5>
                            <a href="{{ route('admin.subcategory.index') }}" class="btn btn-primary px-4">Quay lại</a>
                        </div>

                        <form class="row g-3" method="POST"
                            action="{{ route('admin.subcategory.update', $subCategory->id) }}"
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
                                <label for="category_id" class="form-label">Danh mục cha</label>
                                <select name="category_id" id="category_id" class="form-select">
                                    <option selected disabled>Chọn danh mục cha</option>
                                    @foreach ($all_categories as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $subCategory->category_id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Tên danh mục con</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Tên danh mục con" value="{{ $subCategory->name }}">
                            </div>
                            <div class="col-md-6">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug"
                                    value="{{ $subCategory->slug }}">
                            </div>
                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Xác nhận</button>
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
