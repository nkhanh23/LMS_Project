<?php

namespace App\Services;

use App\Models\CourseLecture;
use App\Models\Quiz;
use Exception;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function saveManualQuiz(CourseLecture $lecture, array $data): Quiz
    {
        return DB::transaction(function () use ($lecture, $data) {
            if (empty($data['questions']) || !is_array($data['questions'])) {
                throw new Exception('Quiz phải có ít nhất 1 câu hỏi.');
            }

            foreach ($data['questions'] as $questionData) {
                if (!isset($questionData['options']) || count($questionData['options']) !== 4) {
                    throw new Exception('Mỗi câu hỏi phải có đúng 4 đáp án.');
                }

                if (!isset($questionData['correct_option']) || !in_array((int)$questionData['correct_option'], [0, 1, 2, 3], true)) {
                    throw new Exception('Đáp án đúng không hợp lệ.');
                }
            }

            $quiz = Quiz::updateOrCreate(
                ['lecture_id' => $lecture->id],
                [
                    'course_id' => $lecture->course_id,
                    'section_id' => $lecture->section_id,
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'time_limit' => $data['time_limit'] ?? null,
                    'source_type' => 'manual',
                    'passing_score' => $data['passing_score'] ?? 0,
                    'max_attempts' => $data['max_attempts'] ?? null,
                    'shuffle_questions' => isset($data['shuffle_questions']) ? (bool) $data['shuffle_questions'] : false,
                    'show_result_immediately' => isset($data['show_result_immediately']) ? (bool) $data['show_result_immediately'] : false,
                    'is_active' => true,
                ]
            );

            $quiz->questions()->delete();

            foreach ($data['questions'] as $questionIndex => $questionData) {
                $question = $quiz->questions()->create([
                    'question_text' => trim($questionData['question_text']),
                    'question_type' => 'single_choice',
                    'explanation' => $questionData['explanation'] ?? null,
                    'points' => $questionData['points'] ?? 1,
                    'sort_order' => $questionIndex + 1,
                ]);

                foreach ($questionData['options'] as $optionIndex => $optionText) {
                    $question->options()->create([
                        'option_text' => trim($optionText),
                        'is_correct' => (int) $questionData['correct_option'] === $optionIndex,
                        'sort_order' => $optionIndex + 1,
                    ]);
                }
            }

            return $quiz->load('questions.options');
        });
    }
}
