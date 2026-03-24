<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseProgress;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\DB;

class LearningProgressService
{
    public function markLectureInProgress(Enrollment $enrollment, CourseLecture $lecture, int $watchSeconds = 0): LessonProgress
    {
        $progress = LessonProgress::firstOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'lecture_id' => $lecture->id,
            ],
            [
                'user_id' => $enrollment->user_id,
                'course_id' => $enrollment->course_id,
                'section_id' => $lecture->section_id,
                'status' => 'not_started',
                'watch_seconds' => 0,
                'progress_percent' => 0,
            ]
        );

        $progress->update([
            'status' => $progress->status === 'completed' ? 'completed' : 'in_progress',
            'watch_seconds' => max((int) $progress->watch_seconds, $watchSeconds),
            'progress_percent' => $progress->status === 'completed'
                ? 100
                : min(99, (int) ($progress->progress_percent ?: 5)),
            'started_at' => $progress->started_at ?? now(),
            'last_watched_at' => now(),
        ]);

        $enrollment->update([
            'last_lecture_id' => $lecture->id,
            'last_accessed_at' => now(),
        ]);

        $this->syncCourseProgress($enrollment, $lecture);

        return $progress->fresh();
    }

    public function markLectureCompleted(Enrollment $enrollment, CourseLecture $lecture): LessonProgress
    {
        return DB::transaction(function () use ($enrollment, $lecture) {
            $progress = LessonProgress::firstOrCreate(
                [
                    'enrollment_id' => $enrollment->id,
                    'lecture_id' => $lecture->id,
                ],
                [
                    'user_id' => $enrollment->user_id,
                    'course_id' => $enrollment->course_id,
                    'section_id' => $lecture->section_id,
                    'status' => 'completed',
                    'progress_percent' => 100,
                    'watch_seconds' => 0,
                    'completed_at' => now(),
                    'last_watched_at' => now(),
                    'started_at' => now(),
                ]
            );

            if (!$progress->wasRecentlyCreated) {
                $progress->update([
                    'status' => 'completed',
                    'progress_percent' => 100,
                    'completed_at' => $progress->completed_at ?? now(),
                    'last_watched_at' => now(),
                ]);
            }

            $enrollment->update([
                'last_lecture_id' => $lecture->id,
                'last_accessed_at' => now(),
            ]);

            $this->syncCourseProgress($enrollment, $lecture);

            return $progress;
        });
    }

    public function syncCourseProgress(Enrollment $enrollment, ?CourseLecture $lastLecture = null): CourseProgress
    {
        $totalLectures = CourseLecture::where('course_id', $enrollment->course_id)->count();

        $completedLectures = LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')
            ->count();

        $percent = $totalLectures > 0
            ? (int) floor(($completedLectures / $totalLectures) * 100)
            : 0;

        $courseProgress = CourseProgress::updateOrCreate(
            ['enrollment_id' => $enrollment->id],
            [
                'user_id' => $enrollment->user_id,
                'course_id' => $enrollment->course_id,
                'total_lectures' => $totalLectures,
                'completed_lectures' => $completedLectures,
                'completion_percent' => $percent,
                'last_lecture_id' => $lastLecture?->id ?? $enrollment->last_lecture_id,
                'last_activity_at' => now(),
                'completed_at' => $percent >= 100 ? now() : null,
            ]
        );

        $enrollment->update([
            'completed_at' => $percent >= 100 ? now() : null,
        ]);

        return $courseProgress;
    }

    public function getResumeLecture(Enrollment $enrollment): ?CourseLecture
    {
        $lastLectureId = optional($enrollment->courseProgress)->last_lecture_id ?: $enrollment->last_lecture_id;

        if ($lastLectureId) {
            return CourseLecture::where('course_id', $enrollment->course_id)
                ->where('id', $lastLectureId)
                ->first();
        }

        return CourseLecture::where('course_id', $enrollment->course_id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();
    }

    public function isLectureUnlocked(Course $course, Enrollment $enrollment, CourseLecture $lecture): bool
    {
        if ($course->content_unlock_mode === 'free' || $lecture->is_preview) {
            return true;
        }

        $allLectureIds = CourseLecture::where('course_id', $course->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id')
            ->values();

        $index = $allLectureIds->search($lecture->id);

        if ($index === false || $index === 0) {
            return true;
        }

        $prevLectureId = $allLectureIds[$index - 1];

        return LessonProgress::where('enrollment_id', $enrollment->id)
            ->where('lecture_id', $prevLectureId)
            ->where('status', 'completed')
            ->exists();
    }
}
