@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Nhà tài trợ',
            'sub_title' => 'Thêm nhà tài trợ',
        ])
        <!--end breadcrumb-->


        <div class="card col-md-8">

            <div class="card-body">

                <div class="card-body p-4">

                    <div style="display: flex; align-items:center; justify-content:space-between">
                        <h5 class="mb-4">Thêm nhà tại trợ</h5>
                        <a href="{{ route('admin.partner.index') }}" class="btn btn-primary">Quay lại</a>

                    </div>

                    <form class="row g-3" method="post" action="{{ route('admin.partner.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @php $errorList = $errors->all(); @endphp
                                    @for ($i = 0; $i < count($errorList); $i++)
                                        <li>{{ $errorList[$i] }}</li>
                                    @endfor
                                </ul>
                            </div>
                        @endif


                        <div class="col-md-6">
                            <label for="partner_name" class="form-label">Tên nhà tài trợ</label>
                            <input type="text" class="form-control" name="name" id="partner_name"
                                placeholder="Nhập tên nhà tài trợ">
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4 w-100">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>





    </div>
@endsection
