@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', [
            'title' => 'Cấu hình Mail',
            'sub_title' => 'Cấu hình Mail',
        ])
        <!--end breadcrumb-->


        <div class="card col-md-8">

            <div class="card-body">

                <div class="card-body p-4">

                    <div style="display: flex; align-items:center; justify-content:space-between">
                        <h5 class="mb-4">Cấu hình SMTP (Mail)</h5>




                    </div>

                    <form class="row g-3" method="post" action="{{ route('admin.mail-setting.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Validation Error Message -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops! Có lỗi xảy ra.</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <label for="mail" class="form-label">Mail Mailer</label>
                            <input type="text" class="form-control" name="mailer" id="mail" placeholder="smtp"
                                value="{{ old('mailer', $mailSettings->mailer ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="host" class="form-label">Mail Host</label>
                            <input type="text" class="form-control" name="host" id="host"
                                placeholder="sandbox.smtp.mailtrap.io" value="{{ old('host', $mailSettings->host ?? '') }}">
                        </div>


                        <div class="col-md-6">
                            <label for="port" class="form-label">MAIL PORT</label>
                            <input type="text" class="form-control" name="port" id="port" placeholder="2525"
                                value="{{ old('port', $mailSettings->port ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="form-label">Mail Username</label>
                            <input type="text" class="form-control" name="username" id="username"
                                placeholder="Enter mail username"
                                value="{{ old('username', $mailSettings->username ?? '') }}">
                        </div>


                        <div class="col-md-6">
                            <label for="password" class="form-label">MAIL PASSWORD</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter the mail password"
                                value="{{ old('password', $mailSettings->password ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="encryption" class="form-label">MAIL ENCRYPTION</label>
                            <input type="text" class="form-control" name="encryption" id="encryption" placeholder="tls"
                                value="{{ old('encryption', $mailSettings->encryption ?? '') }}">
                        </div>

                        <div class="col-md-12">
                            <label for="from_address" class="form-label">MAIL FROM ADDRESS</label>
                            <input type="text" class="form-control" name="from_address" id="name"
                                placeholder="info@lms.com"
                                value="{{ old('from_address', $mailSettings->from_address ?? '') }}">
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
