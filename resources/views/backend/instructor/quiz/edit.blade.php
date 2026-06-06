@extends('backend.instructor.master')

@section('content')
    <div class="page-content">
        <!--breadcrumb-->
        @include('backend.section.breadcrumb', ['title' => 'Quiz', 'sub_title' => 'Chỉnh sửa Quiz'])

        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Trình chỉnh sửa Quiz</h4>
                            <p class="text-muted mb-0">
                                Bài học: {{ $lecture->lecture_title }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('instructor.quiz.store_or_update', $lecture->id) }}" method="POST"
                        id="quizForm">
                        @csrf

                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Thông tin Quiz</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Tiêu đề Quiz</label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ old('title', $lecture->quiz?->title ?? $lecture->lecture_title) }}"
                                        placeholder="Nhập tiêu đề quiz">
                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Nhập mô tả cho quiz">{{ old('description', $lecture->quiz?->description ?? '') }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Thời gian làm bài (phút)</label>
                                        <input type="number" name="time_limit" class="form-control" min="1"
                                            value="{{ old('time_limit', $lecture->quiz?->time_limit ?? 15) }}">
                                        @error('time_limit')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Số câu đúng để đạt</label>
                                        <input type="number" name="passing_score" class="form-control" min="0"
                                            value="{{ old('passing_score', $lecture->quiz?->passing_score ?? 0) }}">
                                        @error('passing_score')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Số lần thử tối đa</label>
                                        <input type="number" name="max_attempts" class="form-control" min="1"
                                            value="{{ old('max_attempts', $lecture->quiz?->max_attempts ?? '') }}"
                                            placeholder="Không giới hạn">
                                        @error('max_attempts')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end justify-content-center flex-column gap-2">
                                        <div class="form-check form-switch w-100">
                                            <input class="form-check-input" type="checkbox" name="shuffle_questions"
                                                id="shuffle_questions" value="1"
                                                {{ old('shuffle_questions', $lecture->quiz?->shuffle_questions ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuffle_questions">Trộn câu hỏi</label>
                                        </div>
                                        <div class="form-check form-switch w-100">
                                            <input class="form-check-input" type="checkbox" name="show_result_immediately"
                                                id="show_result_immediately" value="1"
                                                {{ old('show_result_immediately', $lecture->quiz?->show_result_immediately ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_result_immediately">Hiện kết quả</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <small class="text-muted italic">* Để trống "Số lần thử tối đa" nếu muốn cho phép làm bài không giới hạn.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Danh sách câu hỏi</h5>
                                <button type="button" class="btn btn-primary btn-sm" id="addQuestionBtn">
                                    Thêm câu hỏi
                                </button>
                            </div>

                            <div class="card-body">
                                <div id="questionsWrapper">
                                    @php
                                        $oldQuestions = old('questions');
                                        $quizQuestions = $lecture->quiz ? $lecture->quiz->questions : collect();
                                    @endphp

                                    @if ($oldQuestions)
                                        @foreach ($oldQuestions as $index => $question)
                                            @include('backend.instructor.quiz.partials.question-item', [
                                                'index' => $index,
                                                'question' => $question,
                                                'isOldInput' => true,
                                            ])
                                        @endforeach
                                    @elseif($quizQuestions && $quizQuestions->count())
                                        @foreach ($quizQuestions as $index => $question)
                                            @include('backend.instructor.quiz.partials.question-item', [
                                                'index' => $index,
                                                'question' => $question,
                                                'isOldInput' => false,
                                            ])
                                        @endforeach
                                    @else
                                        @include('backend.instructor.quiz.partials.question-item', [
                                            'index' => 0,
                                            'question' => null,
                                            'isOldInput' => false,
                                        ])
                                    @endif
                                </div>

                                @error('questions')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-success">
                                    Lưu Quiz
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Hidden template --}}
        <div id="questionTemplate" class="d-none">
            @include('backend.instructor.quiz.partials.question-item', [
                'index' => '__INDEX__',
                'question' => null,
                'isOldInput' => true,
            ])
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function() {
            let questionIndex = document.querySelectorAll('.question-item').length;

            document.getElementById('addQuestionBtn').addEventListener('click', function() {
                let template = document.getElementById('questionTemplate').innerHTML;
                template = template.replaceAll('__INDEX__', questionIndex);

                document.getElementById('questionsWrapper').insertAdjacentHTML('beforeend', template);
                questionIndex++;
                updateQuestionNumbers();
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-question-btn')) {
                    e.target.closest('.question-item').remove();
                    updateQuestionNumbers();
                }
            });

            function updateQuestionNumbers() {
                document.querySelectorAll('.question-item').forEach((item, idx) => {
                    const title = item.querySelector('.question-number');
                    if (title) {
                        title.innerText = 'Câu hỏi #' + (idx + 1);
                    }
                });
            }
        })();
    </script>
@endpush
