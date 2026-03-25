<?php

namespace Tests\Feature;

use App\Models\ContentReport;
use App\Models\Course;
use App\Models\InstructorRiskScore;
use App\Models\Order;
use App\Models\RefundRequest;
use App\Models\User;
use App\Models\ModerationPolicy;
use App\Models\ModerationActionTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorRiskScoreIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $instructor;

    /** @test */
    public function simple_test()
    {
        $this->assertTrue(true);
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password')
        ]);

        $this->instructor = User::create([
            'name' => 'Instructor',
            'email' => 'instructor@test.com',
            'role' => 'instructor',
            'password' => bcrypt('password')
        ]);
    }

    /** @test */
    public function resolving_report_triggers_recalculation()
    {
        $course = Course::create([
            'instructor_id' => $this->instructor->id,
            'course_title' => 'Test Course'
        ]);

        $review = \App\Models\CourseReviews::create([
            'course_id' => $course->id,
            'user_id' => $this->admin->id,
            'rating' => 5,
            'comment' => 'Good',
            'status' => 'active'
        ]);

        $report = ContentReport::create([
            'reporter_id' => $this->admin->id,
            'reported_user_id' => $this->instructor->id,
            'reportable_type' => 'course_review',
            'reportable_id' => $review->id,
            'course_id' => $course->id,
            'reason_code' => 'abuse',
            'status' => 'pending'
        ]);

        $policy = ModerationPolicy::create(['name' => 'Test Policy', 'is_active' => true]);
        $template = ModerationActionTemplate::create([
            'name' => 'Resolve',
            'code' => 'lock_instructor',
            'is_active' => true
        ]);

        $this->actingAs($this->admin)
            ->post("/admin/moderation/reports/{$report->id}/resolve", [
                'policy_id' => $policy->id,
                'action_template_id' => $template->id,
                'resolution_note' => 'Resolved'
            ]);

        $this->assertDatabaseHas('instructor_risk_scores', [
            'instructor_id' => $this->instructor->id,
            'confirmed_reports_count' => 1
        ]);
    }

    /** @test */
    public function rejecting_course_triggers_recalculation()
    {
        $course = Course::create([
            'instructor_id' => $this->instructor->id,
            'course_title' => 'Test Course',
            'approval_status' => 'submitted'
        ]);

        $this->actingAs($this->admin)
            ->post("/admin/course-approvals/{$course->id}/reject", [
                'review_note' => 'Rejected'
            ]);

        $this->assertDatabaseHas('instructor_risk_scores', [
            'instructor_id' => $this->instructor->id,
            'rejected_courses_count' => 1
        ]);
    }

    /** @test */
    public function processing_refund_triggers_recalculation()
    {
        $order = Order::create([
            'user_id' => $this->admin->id,
            'instructor_id' => $this->instructor->id,
            'gross_amount' => 100,
            'status' => 'completed',
            'payment_id' => 1
        ]);

        $refundRequest = RefundRequest::create([
            'order_id' => $order->id,
            'user_id' => $this->admin->id,
            'instructor_id' => $this->instructor->id,
            'type' => 'refund',
            'status' => 'pending',
            'requested_amount' => 100
        ]);

        $this->actingAs($this->admin)
            ->post("/admin/orders/refund-requests/{$refundRequest->id}/approve", [
                'approved_amount' => 100,
                'admin_note' => 'Approved'
            ]);

        $this->assertDatabaseHas('instructor_risk_scores', [
            'instructor_id' => $this->instructor->id,
            'refund_requests_count' => 1
        ]);
    }
}
