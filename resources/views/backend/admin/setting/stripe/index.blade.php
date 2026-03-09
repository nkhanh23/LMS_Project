@extends('backend.admin.master')

@section('content')

    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Cấu hình Stripe',
            'sub_title' => 'Cấu hình Stripe',
        ])
        <!--end breadcrumb-->


        <div class="card col-md-10">

            <div class="card-body">

                <div class="card-body p-4">

                    <div style="display: flex; align-items:center; justify-content:space-between">
                        <h5 class="mb-4">Cấu hình Stripe (Cổng thanh toán)</h5>

                    </div>

                    <form class="row g-3" method="post" action="{{ route('admin.stripe-setting.update') }}">
                        @csrf

                        <!-- Validation Error Message -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops! Something went wrong.</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="col-md-12">
                            <label for="publish_key" class="form-label">Publishable Key</label>
                            <input type="text" class="form-control" name="publish_key" id="publish_key"
                                placeholder="Nhập publishable key"
                                value="{{ old('publish_key', $stripeSettings->publish_key ?? '') }}" required>
                        </div>

                        <div class="col-md-12">
                            <label for="secret_key" class="form-label">Secret Key</label>
                            <input type="text" class="form-control" name="secret_key" id="secret_key"
                                placeholder="Nhập secret key"
                                value="{{ old('secret_key', $stripeSettings->secret_key ?? '') }}" required>
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

@endsection
