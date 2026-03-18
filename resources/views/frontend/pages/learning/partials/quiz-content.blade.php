@php
    $totalQuestions = $quiz->questions->count();
    $timeLimit = $quiz->time_limit ?? 15;
    $passingScore = $quiz->passing_score ?? 0;
    $showResult = isset($quizAttempt) && $quizAttempt !== null;
@endphp

<div class="absolute inset-0 flex flex-col bg-cyber-dark z-10 p-6 md:p-10 overflow-y-auto custom-scrollbar">
    <div class="max-w-4xl mx-auto w-full">

        {{-- Quiz Result Screen --}}
        @if($showResult)
            <div id="quiz-result" class="animate-in fade-in duration-700">
                <div class="bg-cyber-surface border-4 border-black p-8 md:p-12 pixel-shadow relative overflow-hidden mb-10">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="fa-solid fa-trophy text-9xl"></i>
                    </div>

                    <div class="relative z-10 text-center">
                        <h4 class="text-brand font-black uppercase tracking-widest text-sm mb-2">Kết quả kiểm tra</h4>
                        <h2 class="text-5xl md:text-6xl font-black text-white uppercase tracking-tighter mb-6">
                            {{ $quizAttempt->score }}%
                        </h2>
                        
                        <div class="inline-flex items-center gap-3 px-6 py-2 bg-black/40 border-2 border-slate-800 mb-8">
                            <span class="text-slate-400 font-bold uppercase text-xs">Điểm đạt:</span>
                            <span class="text-brand font-black">{{ $quizAttempt->correct_answers }}/{{ $quizAttempt->total_questions }}</span>
                        </div>

                        <div class="flex justify-center gap-4">
                            @if($quizAttempt->correct_answers >= $passingScore)
                                <div class="text-green-400 font-black uppercase tracking-widest border-2 border-green-400/30 px-6 py-2 bg-green-400/5">
                                    <i class="fa-solid fa-check-circle mr-2"></i> ĐÃ VÀO HỆ THỐNG
                                </div>
                            @else
                                <div class="text-red-400 font-black uppercase tracking-widest border-2 border-red-400/30 px-6 py-2 bg-red-400/5">
                                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> CHƯA ĐẠT YÊU CẦU
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-8 mb-10">
                    <h3 class="text-2xl font-black text-white uppercase tracking-tight border-b-4 border-brand pb-2 inline-block">Chi tiết đáp án</h3>
                    
                    @foreach($quiz->questions as $index => $question)
                        @php
                            $userAnswer = $quizAttempt->answers->where('question_id', $question->id)->first();
                            $selectedOptionId = $userAnswer ? $userAnswer->selected_option_id : null;
                            $isCorrect = $userAnswer ? $userAnswer->is_correct : false;
                        @endphp
                        <div class="bg-cyber-surface border-4 border-black p-6 md:p-8 pixel-shadow-sm relative">
                            <div class="flex justify-between items-start mb-6 gap-4">
                                <h4 class="text-lg font-bold text-white leading-tight">
                                    <span class="text-brand mr-2">#{{ $index + 1 }}</span> {{ $question->question_text }}
                                </h4>
                                @if($isCorrect)
                                    <span class="shrink-0 bg-green-500/20 text-green-400 text-[10px] font-black px-2 py-1 border border-green-500/30 uppercase">Chính xác</span>
                                @else
                                    <span class="shrink-0 bg-red-500/20 text-red-400 text-[10px] font-black px-2 py-1 border border-red-500/30 uppercase">Sai rồi</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 gap-3 mb-6">
                                @foreach($question->options as $oIdx => $option)
                                    @php
                                        $isUserSelected = $selectedOptionId == $option->id;
                                        $isCorrectOption = $option->is_correct;
                                        
                                        $borderClass = 'border-slate-800';
                                        $bgClass = 'bg-black/40';
                                        $textClass = 'text-slate-400';
                                        
                                        if ($isCorrectOption) {
                                            $borderClass = 'border-green-500';
                                            $bgClass = 'bg-green-500/10';
                                            $textClass = 'text-white';
                                        } elseif ($isUserSelected && !$isCorrectOption) {
                                            $borderClass = 'border-red-500';
                                            $bgClass = 'bg-red-500/10';
                                            $textClass = 'text-white';
                                        } elseif ($isUserSelected) {
                                            $borderClass = 'border-brand';
                                            $bgClass = 'bg-brand/10';
                                            $textClass = 'text-white';
                                        }
                                    @endphp
                                    <div class="flex items-center p-4 {{ $bgClass }} border-2 {{ $borderClass }} justify-between">
                                        <div class="flex items-center gap-4">
                                            <span class="size-7 flex items-center justify-center border-2 {{ $isCorrectOption ? 'border-green-500 text-green-500' : ($isUserSelected && !$isCorrectOption ? 'border-red-500 text-red-500' : 'border-slate-700 text-slate-500') }} font-black text-xs">
                                                {{ chr(65 + $oIdx) }}
                                            </span>
                                            <span class="font-bold {{ $textClass }}">{{ $option->option_text }}</span>
                                        </div>
                                        @if($isCorrectOption)
                                            <i class="fa-solid fa-check text-green-500"></i>
                                        @elseif($isUserSelected && !$isCorrectOption)
                                            <i class="fa-solid fa-xmark text-red-500"></i>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if($question->explanation)
                                <div class="bg-brand/5 border-l-4 border-brand p-4">
                                    <span class="block text-[10px] font-black text-brand uppercase mb-1">Giải thích hệ thống:</span>
                                    <p class="text-slate-300 text-sm font-medium italic">{{ $question->explanation }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if(!$quiz->max_attempts || $userAttemptsCount < $quiz->max_attempts)
                    <div class="flex justify-center mb-10">
                        <button onclick="window.location.reload()" class="px-10 py-4 bg-brand text-black font-black uppercase tracking-widest border-4 border-black hover:translate-x-1 hover:-translate-y-1 transition-transform pixel-shadow-sm">
                            LÀM LẠI BÀI KIỂM TRA
                        </button>
                    </div>
                @else
                    <div class="flex justify-center mb-10">
                        <div class="px-10 py-4 bg-slate-800 text-slate-500 font-black uppercase tracking-widest border-4 border-black cursor-not-allowed">
                            ĐÃ HẾT LƯỢT LÀM BÀI
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Quiz Intro Screen --}}
        <div id="quiz-intro"
            class="bg-cyber-surface border-4 border-black p-8 md:p-12 pixel-shadow relative overflow-hidden {{ $showResult ? 'hidden' : '' }}">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-brain text-9xl"></i>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="size-16 bg-brand flex items-center justify-center border-4 border-black pixel-shadow-sm">
                        <i class="fa-solid fa-terminal text-black text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-brand font-black uppercase tracking-widest text-sm">System Challenge</h4>
                        <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tighter">
                            {{ $quiz->title ?? 'KIỂM TRA KIẾN THỨC HỆ THỐNG' }}
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div class="bg-black/40 border-2 border-black p-4">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Tổng câu hỏi</span>
                        <span class="text-2xl font-black text-cyber-cyan">{{ $totalQuestions }}</span>
                    </div>
                    <div class="bg-black/40 border-2 border-black p-4">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Thời gian</span>
                        <span class="text-2xl font-black text-cyber-cyan">{{ $timeLimit }} Phút</span>
                    </div>
                    <div class="bg-black/40 border-2 border-black p-4">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Số câu đúng để đạt</span>
                        <span class="text-2xl font-black text-cyber-cyan">{{ $passingScore }}</span>
                    </div>
                </div>

                <div class="prose prose-invert max-w-none text-slate-400 mb-10 font-medium">
                    <p>{{ $quiz->description ?? 'Hãy hoàn thành bài kiểm tra để đánh giá mức độ hiểu bài của bạn.' }}
                    </p>
                </div>

                {{-- Attempts Info --}}
                <div class="mb-8">
                    @if($quiz->max_attempts)
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-rotate-right text-brand sm:text-lg"></i>
                            <span class="text-slate-300 font-bold uppercase text-xs sm:text-sm tracking-widest">
                                Lần thử: <span class="text-white">{{ $userAttemptsCount }}</span> / <span class="text-white">{{ $quiz->max_attempts }}</span>
                            </span>
                        </div>
                        @if($userAttemptsCount >= $quiz->max_attempts && !$showResult)
                            <div class="mt-4 p-4 bg-red-500/10 border-l-4 border-red-500 text-red-400 text-sm font-bold flex items-center gap-3">
                                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                                <span>Bạn đã hết số lần làm bài cho thử thách này.</span>
                            </div>
                        @endif
                    @else
                        <div class="flex items-center gap-3 text-slate-400">
                            <i class="fa-solid fa-infinity text-brand"></i>
                            <span class="font-bold uppercase text-xs tracking-widest">Không giới hạn số lần thử</span>
                        </div>
                    @endif
                </div>

                <div class="flex flex-wrap gap-4">
                    @if($quiz->max_attempts && $userAttemptsCount >= $quiz->max_attempts)
                         @if(!$showResult)
                            <button disabled
                                class="px-10 py-4 bg-slate-800 text-slate-500 font-black uppercase tracking-widest border-4 border-black cursor-not-allowed">
                                KHÔNG THỂ BẮT ĐẦU
                            </button>
                         @else
                            <button onclick="window.location.reload()"
                                class="px-10 py-4 bg-cyber-surface text-white font-black uppercase tracking-widest border-4 border-black hover:translate-x-1 hover:-translate-y-1 transition-transform pixel-shadow-sm">
                                XEM LẠI KẾT QUẢ
                            </button>
                         @endif
                    @else
                        <button id="start-quiz-btn"
                            class="px-10 py-4 bg-brand text-black font-black uppercase tracking-widest border-4 border-black hover:translate-x-1 hover:-translate-y-1 transition-transform pixel-shadow-sm">
                            BẮT ĐẦU KIỂM TRA
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quiz Questions --}}
        <div id="quiz-question-container" class="hidden animate-in fade-in duration-500">
            <form id="quiz-submit-form" action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
                @csrf

                <div class="flex justify-between items-center mb-8">
                    <div class="flex items-center gap-4">
                        <span id="quiz-progress-text" class="text-sm font-black text-brand uppercase tracking-widest">
                            CÂU HỎI 01/{{ str_pad($totalQuestions, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div class="w-48 h-2 bg-black border border-slate-800 rounded-full overflow-hidden">
                            <div id="quiz-progress-bar" class="h-full bg-brand"
                                style="width: {{ $totalQuestions > 0 ? 100 / $totalQuestions : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clock text-brand"></i>
                        <span id="quiz-timer" class="font-mono font-bold text-white text-xl">
                            {{ str_pad($timeLimit, 2, '0', STR_PAD_LEFT) }}:00
                        </span>
                    </div>
                </div>

                <div id="questions-wrapper">
                    @foreach ($quiz->questions as $qIndex => $question)
                        <div class="quiz-question-item {{ $qIndex === 0 ? '' : 'hidden' }}"
                            data-question-index="{{ $qIndex }}">
                            <div class="bg-cyber-surface border-4 border-black p-8 md:p-10 mb-8 relative">
                                <h3 class="text-xl md:text-2xl font-bold text-white leading-snug">
                                    {{ $question->question_text }}
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 gap-4 mb-10">
                                @foreach ($question->options as $oIndex => $option)
                                    <label class="group cursor-pointer block" for="opt-{{ $option->id }}">
                                        <input type="radio" name="answers[{{ $question->id }}]"
                                            id="opt-{{ $option->id }}"
                                            value="{{ $option->id }}" class="sr-only peer quiz-option-input">
                                        <div
                                            class="quiz-option-content flex items-center p-5 bg-black/40 border-2 border-slate-800 group-hover:border-brand transition-all justify-between">
                                            <div class="flex items-center gap-4">
                                                <span
                                                    class="quiz-option-letter size-8 flex items-center justify-center border-2 border-slate-700 font-black text-slate-500 group-hover:text-brand transition-colors">
                                                    {{ chr(65 + $oIndex) }}
                                                </span>
                                                <span
                                                    class="quiz-option-text font-bold text-slate-300 group-hover:text-white transition-colors">
                                                    {{ $option->option_text }}
                                                </span>
                                            </div>
                                            <div
                                                class="quiz-option-circle size-6 border-2 border-slate-700 rounded-full flex items-center justify-center transition-all">
                                                <div
                                                    class="quiz-option-dot size-2 bg-black rounded-full opacity-0">
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" id="prev-question-btn"
                        class="px-8 py-3 bg-cyber-dark text-slate-500 font-bold uppercase border-2 border-black opacity-50 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left mr-2"></i> TRƯỚC ĐÓ
                    </button>

                    <div class="flex gap-4">
                        <button type="button" id="next-question-btn"
                            class="px-10 py-3 bg-brand text-black font-black uppercase tracking-widest border-4 border-black hover:translate-x-1 hover:-translate-y-1 transition-transform pixel-shadow-sm">
                            TIẾP THEO <i class="fa-solid fa-chevron-right ml-2 text-xs"></i>
                        </button>

                        <button type="submit" id="submit-quiz-btn"
                            class="hidden px-10 py-3 bg-green-500 text-black font-black uppercase tracking-widest border-4 border-black hover:translate-x-1 hover:-translate-y-1 transition-transform pixel-shadow-sm">
                            NỘP BÀI
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (() => {
        const intro = document.getElementById('quiz-intro');
        const container = document.getElementById('quiz-question-container');
        const startBtn = document.getElementById('start-quiz-btn');
        const timerDisplay = document.getElementById('quiz-timer');
        const questions = document.querySelectorAll('.quiz-question-item');
        const prevBtn = document.getElementById('prev-question-btn');
        const nextBtn = document.getElementById('next-question-btn');
        const submitBtn = document.getElementById('submit-quiz-btn');
        const progressText = document.getElementById('quiz-progress-text');
        const progressBar = document.getElementById('quiz-progress-bar');

        const totalQuestions = questions.length;
        const durationMinutes = {{ (int) $timeLimit }};
        let currentIndex = 0;
        let timerInterval = null;

        function showQuestion(index) {
            questions.forEach((item, idx) => {
                item.classList.toggle('hidden', idx !== index);
            });

            progressText.textContent =
                `CÂU HỎI ${String(index + 1).padStart(2, '0')}/${String(totalQuestions).padStart(2, '0')}`;
            progressBar.style.width = `${((index + 1) / totalQuestions) * 100}%`;

            if (index === 0) {
                prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
                prevBtn.disabled = true;
            } else {
                prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                prevBtn.disabled = false;
            }

            if (index === totalQuestions - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        function startTimer(duration) {
            let timer = duration;
            timerInterval = setInterval(() => {
                const minutes = String(Math.floor(timer / 60)).padStart(2, '0');
                const seconds = String(timer % 60).padStart(2, '0');
                timerDisplay.textContent = `${minutes}:${seconds}`;

                timer--;

                if (timer < 0) {
                    clearInterval(timerInterval);
                    timerDisplay.textContent = "00:00";
                    document.getElementById('quiz-submit-form').submit();
                }
            }, 1000);
        }

        startBtn?.addEventListener('click', function() {
            intro.classList.add('hidden');
            container.classList.remove('hidden');
            showQuestion(0);
            startTimer(durationMinutes * 60);
        });

        nextBtn?.addEventListener('click', function() {
            if (currentIndex < totalQuestions - 1) {
                currentIndex++;
                showQuestion(currentIndex);
            }
        });

        prevBtn?.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                showQuestion(currentIndex);
            }
        });
    })();
</script>
