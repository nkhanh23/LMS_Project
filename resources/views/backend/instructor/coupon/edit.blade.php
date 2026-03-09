@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Quản lý mã giảm giá',
            'sub_title' => 'Cập nhật mã giảm giá',
        ])
        <!--end breadcrumb-->


        <div class="card col-md-8">

            <div class="card-body">

                <div class="card-body p-4">

                    <div style="display: flex; align-items:center; justify-content:space-between">
                        <h5 class="mb-4">Cập nhật mã giảm giá</h5>
                        <a href="{{ route('instructor.coupon.index') }}" class="btn btn-primary">Quay lại</a>

                    </div>

                    <form class="row g-3" method="post" action="{{ route('instructor.coupon.update', $coupon->id) }}">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <ul class="text-red-500">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif



                        <div class="col-md-6">
                            <label for="coupon_code" class="form-label">Coupon Name</label>
                            <input type="text" class="form-control" name="coupon_code" id="coupon_code"
                                placeholder="Enter coupon name" value='{{ $coupon->coupon_code }}'>
                        </div>
                        <div class="col-md-6">
                            <label for="coupon_discount" class="form-label">Coupon Discount</label>
                            <input type="price" class="form-control" name="coupon_discount" id="coupon_discount"
                                placeholder="Enter coupon discount" value="{{ $coupon->coupon_discount }}">
                        </div>
                        <div class="col-md-6">
                            <label for="discount_validity" class="form-label">Coupon Validity</label>
                            <input type="date" class="form-control" name="discount_validity" id="discount_validity"
                                value="{{ $coupon->discount_validity }}">
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">



                                <option value='1' {{ $coupon->status == '1' ? 'selected' : '' }}>Yes</option>


                                <option value="0" {{ $coupon->status == '0' ? 'selected' : '' }}>No</option>


                            </select>
                        </div>


                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4 w-100">Update</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>





    </div>
@endsection
