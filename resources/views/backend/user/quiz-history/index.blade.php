@extends('backend.user.master')

@section('content')
    <div class="space-y-6">
        <div class="mb-6">
            <h3 class="pixel-text font-bold text-xl text-white">
                Lịch sử làm quiz <span class="text-brand">_QUIZ_HISTORY</span>
            </h3>
            <p class="text-xs text-text-secondary mt-1 font-pixel">
                Xem lại kết quả các bài kiểm tra bạn đã thực hiện.
            </p>
        </div>

        <div class="bg-cyber-surface border-2 border-black pixel-shadow overflow-hidden">
            @if ($attempts->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-black text-brand uppercase text-[10px] pixel-text border-b-2 border-black">
                            <tr>
                                <th class="p-4 border-r border-black/40">Quiz</th>
                                <th class="p-4 border-r border-black/40">Khóa học</th>
                                <th class="p-4 border-r border-black/40">Điểm</th>
                                <th class="p-4 border-r border-black/40">Kết quả</th>
                                <th class="p-4 border-r border-black/40">Ngày làm</th>
                                <th class="p-4 text-right">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y-2 divide-black">
                            @foreach ($attempts as $attempt)
                                @php
                                    $quiz = $attempt->quiz;
                                    $course = $quiz?->lecture?->course;
                                    $score = $attempt->score ?? 0;
                                    $passing = $quiz->passing_score ?? 0;
                                    $passed = $score >= $passing;
                                @endphp

                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="p-4 border-r border-black/40">
                                        <div class="font-bold text-sm text-text-primary">
                                            {{ $quiz->title ?? ($quiz->name ?? 'Quiz') }}
                                        </div>
                                        <div class="text-xs text-text-secondary mt-1">
                                            {{ $quiz?->lecture?->lecture_title ?? ($quiz?->lecture?->title ?? 'Không rõ bài học') }}
                                        </div>
                                    </td>

                                    <td class="p-4 text-xs text-text-secondary border-r border-black/40">
                                        {{ $course->course_name ?? 'Không rõ khóa học' }}
                                    </td>

                                    <td class="p-4 border-r border-black/40">
                                        <span class="text-brand font-bold">
                                            {{ $score }}
                                        </span>
                                        <span class="text-text-secondary text-xs">
                                            / {{ $attempt->total_score ?? 100 }}
                                        </span>
                                    </td>

                                    <td class="p-4 border-r border-black/40">
                                        @if ($passed)
                                            <span
                                                class="px-2 py-1 bg-brand text-black border border-black font-bold text-[10px] uppercase pixel-text">
                                                Đạt
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-pink-500 text-white border border-black font-bold text-[10px] uppercase pixel-text">
                                                Chưa đạt
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-xs text-text-secondary border-r border-black/40 font-pixel">
                                        {{ optional($attempt->created_at)->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="p-4 text-right">
                                        <a href="{{ route('user.quiz-history.show', $attempt->id) }}"
                                            class="inline-flex px-3 py-1.5 bg-cyber-dark border border-black text-white text-xs font-bold pixel-button-hover uppercase pixel-text">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t-2 border-black bg-black/20">
                    {{ $attempts->links() }}
                </div>
            @else
                <div class="p-10 text-center">
                    <i class="fas fa-clipboard-question text-5xl text-brand mb-4"></i>

                    <h3 class="pixel-text font-bold text-xl text-white mb-2">
                        Bạn chưa làm quiz nào <span class="text-brand">_EMPTY</span>
                    </h3>

                    <p class="text-xs text-text-secondary mt-2">
                        Khi bạn làm quiz trong khóa học, kết quả sẽ được lưu tại đây.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
