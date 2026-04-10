@extends('backend.admin.master')

@section('content')
    <div class="page-content">

        <!--breadcrumb-->
        @include('backend.section.breadcrumb', ['title' => 'Cài đặt', 'sub_title' => 'Cấu hình Gemini AI'])
        <!--end breadcrumb-->


        <div class="card col-md-10">

            <div class="card-body">

                <div class="card-body p-4">

                    <div style="display: flex; align-items:center; justify-content:space-between">
                        <h5 class="mb-4">Cấu hình Gemini AI</h5>
                    </div>

                    <form class="row g-3" method="post" action="{{ route('admin.setting.gemini.update') }}">
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

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="col-md-12">
                            <label for="api_key" class="form-label">GEMINI_API_KEY</label>
                            <input type="text" class="form-control" name="api_key" id="api_key"
                                placeholder="Nhập API key mới nếu muốn thay đổi" value="{{ old('api_key') }}">
                            @if ($geminiSettings && $geminiSettings->api_key)
                                <small class="text-muted">
                                    Key hiện tại: {{ $geminiSettings->maskedApiKey() }}
                                </small>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label for="model_name" class="form-label">GEMINI_MODEL_NAME</label>
                            <input type="text" class="form-control" name="model_name" id="model_name"
                                placeholder="Ví dụ: gemini-1.5-flash"
                                value="{{ old('model_name', $geminiSettings->model_name ?? 'gemini-1.5-flash') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="timeout_seconds" class="form-label">TIMEOUT (seconds)</label>
                            <input type="number" class="form-control" name="timeout_seconds" id="timeout_seconds"
                                value="{{ old('timeout_seconds', $geminiSettings->timeout_seconds ?? 30) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="temperature" class="form-label">TEMPERATURE</label>
                            <input type="number" step="0.01" class="form-control" name="temperature" id="temperature"
                                value="{{ old('temperature', $geminiSettings->temperature ?? 0.20) }}" required>
                        </div>

                        <div class="col-md-12">
                            <label for="max_output_tokens" class="form-label">MAX_OUTPUT_TOKENS</label>
                            <input type="number" class="form-control" name="max_output_tokens" id="max_output_tokens"
                                value="{{ old('max_output_tokens', $geminiSettings->max_output_tokens ?? 1024) }}" required>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" name="is_enabled"
                                    id="is_enabled"
                                    {{ old('is_enabled', $geminiSettings->is_enabled ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_enabled">
                                    Bật chatbot AI (RAG)
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4 w-100">Cập nhật cấu hình</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>

    </div>
@endsection
