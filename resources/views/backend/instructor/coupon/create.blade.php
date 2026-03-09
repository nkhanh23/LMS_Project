@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Tạo mã giảm giá',
            'sub_title' => 'Thêm mã giảm giá',
        ])
        <!--end breadcrumb-->


        <div class="card col-md-8">

            <div class="card-body">

                <div class="card-body p-4">

                    <div style="display: flex; align-items:center; justify-content:space-between">
                        <h5 class="mb-4">Create Coupon</h5>
                        <a href="{{ route('instructor.coupon.index') }}" class="btn btn-primary">Back</a>

                    </div>

                    <form class="row g-3" method="post" action="{{ route('instructor.coupon.store') }}">

                        @csrf

                        @if ($errors->any())
                            <ul class="" style="color: red; list-style:none">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="col-md-6">
                            <label for="coupon_code" class="form-label">Tên mã giảm giá</label>
                            <input type="text" class="form-control" name="coupon_code" id="coupon_code"
                                value="{{ old('coupon_code') }}" placeholder="Enter coupon name">
                        </div>
                        <div class="col-md-6">
                            <label for="coupon_discount" class="form-label">Mức giảm giá</label>
                            <input type="price" class="form-control" name="coupon_discount" id="coupon_discount"
                                value="{{ old('coupon_discount') }}" placeholder="Enter coupon discount">
                        </div>
                        <div class="col-md-6">
                            <label for="coupon_validity" class="form-label">Ngày hết hạn</label>
                            <input type="date" class="form-control" name="discount_validity" id="coupon_validity"
                                value="{{ old('discount_validity') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select id="status" name="status" class="form-select" value="{{ old('status') }}">

                                <option selected value='1'>Có</option>
                                <option value="0">Không</option>

                            </select>
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
@endsection
