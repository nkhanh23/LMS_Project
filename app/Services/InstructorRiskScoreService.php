<?php

namespace App\Services;

use App\Models\ContentReport;
use App\Models\Course;
use App\Models\InstructorRiskScore;
use App\Models\RefundRequest;

class InstructorRiskScoreService
{
    public function recalculate(int $instructorId): InstructorRiskScore
    {
        $confirmedReports = ContentReport::where('reported_user_id', $instructorId)
            ->where('status', 'resolved')
            ->count();

        $refundRequests = RefundRequest::where('instructor_id', $instructorId)->count();

        $rejectedCourses = Course::where('instructor_id', $instructorId)
            ->where('approval_status', 'rejected')
            ->count();

        $warningsCount = 0;

        $riskScore = ($confirmedReports * 30)
            + ($refundRequests * 10)
            + ($rejectedCourses * 20)
            + ($warningsCount * 15);

        return InstructorRiskScore::updateOrCreate(
            ['instructor_id' => $instructorId],
            [
                'risk_score' => $riskScore,
                'confirmed_reports_count' => $confirmedReports,
                'refund_requests_count' => $refundRequests,
                'rejected_courses_count' => $rejectedCourses,
                'warnings_count' => $warningsCount,
                'calculated_at' => now(),
            ]
        );
    }
}
