<?php

namespace App\Repositories;

use App\Models\CourseProgress;
use Illuminate\Support\Facades\DB;

class AdminLearningAnalyticsRepository
{
    public function getCourseCompletionStats(array $filters = [])
    {
        $query = CourseProgress::query()
            ->select(
                'course_id',
                DB::raw('COUNT(*) as enrolled_users'),
                DB::raw('AVG(completion_percent) as avg_completion'),
                DB::raw('SUM(CASE WHEN completion_percent = 100 THEN 1 ELSE 0 END) as completed_users')
            )
            ->with('course')
            ->groupBy('course_id');

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        return $query->get();
    }

    public function getUserLearningStats(array $filters = [])
    {
        $query = CourseProgress::query()
            ->with(['user', 'course', 'lastLecture'])
            ->latest('last_activity_at');

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->paginate(20);
    }
}
