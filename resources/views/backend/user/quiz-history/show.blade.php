@extends('backend.user.master')

@section('content')
    <div class="space-y-6">
        @php
            $quiz = $attempt->quiz;
            $course = $quiz?->lecture?->course;
            $score = $attempt->score ?? 0;
            $passing = $quiz->passing_score ?? 0;
            $passed = $score >= $passing;
        @endphp

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h3 class="pixel-text font-bold text-xl text-white">
                    Chi tiết kết quả quiz <span class="text-brand">_QUIZ_RESULT</span>
                </h3>
                <p class="text-xs text-text-secondary mt-1 font-pixel">
                    {{ $quiz->title ?? ($quiz->name ?? 'Quiz') }}
                </p>
            </div>

            <a href="{{ route('user.quiz-history') }}"
                class="px-4 py-2 bg-cyber-dark border-2 border-black text-white font-bold text-sm pixel-shadow-sm pixel-button-hover pixel-text uppercase">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-cyber-surface border-2 border-black p-4 pixel-shadow flex flex-col justify-between group">
                <div class="text-text-secondary text-[10px] font-bold uppercase pixel-text mb-2">Điểm</div>
                <div class="text-3xl font-bold text-brand font-pixel">{{ $score }}</div>
            </div>

            <div class="bg-cyber-surface border-2 border-black p-4 pixel-shadow flex flex-col justify-between group">
                <div class="text-text-secondary text-[10px] font-bold uppercase pixel-text mb-2">Điểm đạt</div>
                <div class="text-3xl font-bold text-cyber-cyan font-pixel">{{ $passing }}</div>
            </div>

            <div class="bg-cyber-surface border-2 border-black p-4 pixel-shadow flex flex-col justify-between group">
                <div class="text-text-secondary text-[10px] font-bold uppercase pixel-text mb-2">Trạng thái</div>
                <div class="text-2xl font-bold {{ $passed ? 'text-brand' : 'text-pink-400' }} font-pixel">
                    {{ $passed ? 'ĐẠT' : 'CHƯA ĐẠT' }}
                </div>
            </div>

            <div class="bg-cyber-surface border-2 border-black p-4 pixel-shadow flex flex-col justify-between group">
                <div class="text-text-secondary text-[10px] font-bold uppercase pixel-text mb-2">Ngày làm</div>
                <div class="text-xl font-bold text-white font-pixel">
                    {{ optional($attempt->created_at)->format('d/m/Y') }}
                </div>
            </div>
        </div>

        <div class="bg-cyber-surface border-2 border-black pixel-shadow mt-8">
            <div class="p-4 border-b-2 border-black bg-black/40">
                <h3 class="pixel-text font-bold text-lg text-white">
                    Danh sách câu trả lời
                </h3>
                <p class="text-text-secondary text-[10px] mt-1 font-pixel uppercase">
                    Khóa học: {{ $course->course_name ?? 'Không rõ khóa học' }}
                </p>
            </div>

            <div class="p-6 space-y-4">
                @forelse($attempt->answers as $index => $answer)
                    @php
                        $question = $answer->question;
                        $selectedOption = $answer->selectedOption;
                        $isCorrect = (bool) ($answer->is_correct ?? false);
                    @endphp

                    <div class="border-2 border-black bg-cyber-dark p-4 relative group hover:bg-black/40 transition-colors">
                        <div class="absolute top-0 right-0">
                            @if ($isCorrect)
                                <span class="inline-block px-2 py-1 bg-brand text-black border-l-2 border-b-2 border-black font-bold text-[10px] uppercase pixel-text">
                                    Đúng
                                </span>
                            @else
                                <span class="inline-block px-2 py-1 bg-pink-500 text-white border-l-2 border-b-2 border-black font-bold text-[10px] uppercase pixel-text">
                                    Sai
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex flex-col gap-2 pr-16">
                            <div>
                                <div class="text-[10px] font-bold text-text-secondary uppercase pixel-text mb-1">
                                    Câu {{ $index + 1 }}
                                </div>

                                <h4 class="font-bold text-sm text-white">
                                    {{ $question->question_text ?? ($question->title ?? 'Không có nội dung câu hỏi') }}
                                </h4>
                            </div>

                        </div>

                        <div class="mt-4 pt-3 border-t border-black/40 text-xs">
                            <div class="text-text-secondary mb-1">Bạn chọn:</div>
                            <div class="text-brand font-bold">
                                {{ $selectedOption->option_text ?? ($selectedOption->title ?? 'Không chọn đáp án') }}
                            </div>
                        </div>

                        @if (!empty($answer->explanation))
                            <div class="mt-3 p-3 bg-black border border-black text-xs text-text-secondary">
                                <span class="text-cyber-cyan font-bold block mb-1">Giải thích:</span>
                                {{ $answer->explanation }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center p-8">
                        <p class="text-xs text-text-secondary font-pixel">
                            Không có dữ liệu câu trả lời.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
