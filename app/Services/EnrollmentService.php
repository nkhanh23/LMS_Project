<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use App\Repositories\EnrollmentRepository;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function grantFromOrder(Order $order): Enrollment
    {
        return DB::transaction(function () use ($order) {
            $enrollment = Enrollment::updateOrCreate(
                [
                    'user_id' => $order->user_id,
                    'course_id' => $order->course_id,
                ],
                [
                    'order_id' => $order->id,
                    'source' => 'order',
                    'status' => 'active',
                    'access_granted_at' => $order->paid_at ?? now(),
                    'revoked_at' => null,
                    'revoked_reason' => null,
                ]
            );

            if (!$enrollment->courseProgress) {
                $totalLectures = \App\Models\CourseLecture::where('course_id', $order->course_id)->count();

                $enrollment->courseProgress()->create([
                    'user_id' => $order->user_id,
                    'course_id' => $order->course_id,
                    'total_lectures' => $totalLectures,
                    'completed_lectures' => 0,
                    'completion_percent' => 0,
                ]);
            }

            return $enrollment->fresh(['courseProgress']);
        });
    }

    public function revokeFromOrder(Order $order, string $reason = 'refund'): void
    {
        $enrollment = Enrollment::where('order_id', $order->id)->first();

        if (!$enrollment) {
            return;
        }

        $enrollment->update([
            'status' => $reason === 'refund' ? 'refunded' : 'revoked',
            'revoked_at' => now(),
            'revoked_reason' => $reason,
        ]);
    }

    public function getActiveEnrollment(int $userId, int $courseId): ?Enrollment
    {
        return Enrollment::with('courseProgress')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->first();
    }

    public function grantManual(User $user, Course $course, ?string $source = 'admin'): Enrollment
    {
        return Enrollment::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'source' => $source,
                'status' => 'active',
                'access_granted_at' => now(),
            ]
        );
    }
}
