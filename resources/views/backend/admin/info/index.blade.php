@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Thông tin',
            'sub_title' => 'Tất cả thông tin',
        ])

        <!--end breadcrumb-->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-title text-center mt-3">
                        <h3>Thông tin box 1</h3>
                    </div>
                    <div class="card-body">
                        <form class="row g-3" action="{{ route('admin.info.update', $firstInfo->id ?? '1') }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12">
                                <label for="icon" class="form-label">Icon</label>
                                <textarea class="form-control" name="icon" id="icon" cols="30" rows="10" placeholder="Nhập svg icon"
                                    rows="5">{{ $firstInfo->icon ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="title" class="form-label">Tiêu đề</label>
                                <input class="form-control" name="title" id="title" placeholder="Nhập tiêu đề"
                                    value="{{ $firstInfo->title ?? '' }}">
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Nhập mô tả">{{ $firstInfo->description ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-title text-center mt-3">
                        <h3>Thông tin box 2</h3>
                    </div>
                    <div class="card-body">
                        <form class="row g-3" action="{{ route('admin.info.update', $secondInfo->id ?? '2') }}"
                            method="post">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12">
                                <label for="icon" class="form-label">Icon</label>
                                <textarea class="form-control" name="icon" id="icon" cols="30" rows="10" placeholder="Nhập svg icon"
                                    rows="5">{{ $secondInfo->icon ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="title" class="form-label">Tiêu đề</label>
                                <input class="form-control" name="title" id="title" placeholder="Nhập tiêu đề"
                                    value="{{ $secondInfo->title ?? '' }}">
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Nhập mô tả">{{ $secondInfo->description ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-title text-center mt-3">
                        <h3>Thông tin box 3</h3>
                    </div>
                    <div class="card-body">
                        <form class="row g-3" action="{{ route('admin.info.update', $thirdInfo->id ?? '3') }}"
                            method="post">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12">
                                <label for="icon" class="form-label">Icon</label>
                                <textarea class="form-control" name="icon" id="icon" cols="30" rows="10" placeholder="Nhập svg icon"
                                    rows="5">{{ $thirdInfo->icon ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="title" class="form-label">Tiêu đề</label>
                                <input class="form-control" name="title" id="title" placeholder="Nhập tiêu đề"
                                    value="{{ $thirdInfo->title ?? '' }}">
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Nhập mô tả">{{ $thirdInfo->description ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
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
