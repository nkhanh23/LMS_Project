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

        // Lấy điểm rủi ro trước đó để so sánh (tránh gửi thông báo trùng)
        $previousScore = InstructorRiskScore::where('instructor_id', $instructorId)
            ->value('risk_score') ?? 0;

        $result = InstructorRiskScore::updateOrCreate(
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

        // Gửi cảnh báo rủi ro cho tất cả Admin nếu score >= 60 VÀ tăng so với trước
        if ($riskScore >= 60 && $riskScore > $previousScore) {
            $this->notifyAdminsOfRisk($instructorId, $riskScore);
        }

        return $result;
    }

    /**
     * Gửi thông báo cảnh báo rủi ro tới tất cả Admin.
     */
    protected function notifyAdminsOfRisk(int $instructorId, int $riskScore): void
    {
        try {
            $instructor = \App\Models\User::find($instructorId);
            if (!$instructor) {
                return;
            }

            $admins = \App\Models\User::where('role', 'admin')->get();

            if ($admins->isEmpty()) {
                return;
            }

            \Illuminate\Support\Facades\Notification::send(
                $admins,
                new \App\Notifications\FraudRiskAlertNotification($instructor, $riskScore)
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi gửi cảnh báo rủi ro: ' . $e->getMessage());
        }
    }
}
