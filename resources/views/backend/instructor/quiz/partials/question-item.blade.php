@php
    $questionText = '';
    $explanation = '';
    $points = 1;
    $options = ['', '', '', ''];
    $correctOption = 0;

    if ($question) {
        if (!empty($isOldInput)) {
            $questionText = $question['question_text'] ?? '';
            $explanation = $question['explanation'] ?? '';
            $points = $question['points'] ?? 1;
            $options = $question['options'] ?? ['', '', '', ''];
            $correctOption = $question['correct_option'] ?? 0;
        } else {
            $questionText = $question->question_text ?? '';
            $explanation = $question->explanation ?? '';
            $points = $question->points ?? 1;

            if (isset($question->options) && $question->options->count()) {
                $options = $question->options->pluck('option_text')->toArray();
                $correct = $question->options->search(function ($item) {
                    return $item->is_correct;
                });
                $correctOption = $correct !== false ? $correct : 0;
            }
        }
    }
@endphp

<div class="question-item border rounded p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 question-number">Câu hỏi #{{ is_numeric($index) ? $index + 1 : '' }}</h6>
        <button type="button" class="btn btn-danger btn-sm remove-question-btn">Xóa</button>
    </div>

    <div class="mb-3">
        <label class="form-label">Nội dung câu hỏi</label>
        <textarea name="questions[{{ $index }}][question_text]" class="form-control" rows="3"
            placeholder="Nhập nội dung câu hỏi">{{ $questionText }}</textarea>
        @error("questions.$index.question_text")
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">Điểm số</label>
            <input type="number" name="questions[{{ $index }}][points]" class="form-control" min="1"
                value="{{ $points }}">
            @error("questions.$index.points")
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Các lựa chọn đáp án</label>

        @foreach ([0, 1, 2, 3] as $optionIndex)
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input type="radio" name="questions[{{ $index }}][correct_option]"
                        value="{{ $optionIndex }}"
                        {{ (string) $correctOption === (string) $optionIndex ? 'checked' : '' }}>
                </div>

                <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]"
                    class="form-control" placeholder="Lựa chọn {{ chr(65 + $optionIndex) }}"
                    value="{{ $options[$optionIndex] ?? '' }}">
            </div>

            @error("questions.$index.options.$optionIndex")
                <small class="text-danger d-block mb-2">{{ $message }}</small>
            @enderror
        @endforeach

        @error("questions.$index.correct_option")
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-0">
        <label class="form-label">Giải thích (Tùy chọn)</label>
        <textarea name="questions[{{ $index }}][explanation]" class="form-control" rows="2"
            placeholder="Giải thích thêm về đáp án đúng">{{ $explanation }}</textarea>
        @error("questions.$index.explanation")
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
