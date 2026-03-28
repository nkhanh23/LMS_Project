<?php

namespace Tests\Feature;

use App\Models\ContentReport;
use App\Models\Course;
use App\Models\RefundRequest;
use App\Models\User;
use App\Services\InstructorRiskScoreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorRiskScoreServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $instructor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new InstructorRiskScoreService();
        $this->instructor = User::create([
            'name' => 'Test Instructor',
            'email' => 'instructor@test.com',
            'role' => 'instructor',
            'password' => bcrypt('password')
        ]);
    }

    /** @test */
    public function it_calculates_risk_score_correctly_for_confirmed_reports()
    {
        // 1 confirmed report = 30 points
        ContentReport::create([
            'reported_user_id' => $this->instructor->id,
            'reporter_id' => 1,
            'status' => 'resolved',
            'reason_code' => 'abuse'
        ]);

        $riskScore = $this->service->recalculate($this->instructor->id);

        $this->assertEquals(30, $riskScore->risk_score);
        $this->assertEquals(1, $riskScore->confirmed_reports_count);
    }

    /** @test */
    public function it_calculates_risk_score_correctly_for_refund_requests()
    {
        // 1 refund request = 10 points
        RefundRequest::create([
            'instructor_id' => $this->instructor->id,
            'user_id' => 1,
            'order_id' => 1,
            'status' => 'pending',
            'requested_amount' => 100
        ]);

        $riskScore = $this->service->recalculate($this->instructor->id);

        $this->assertEquals(10, $riskScore->risk_score);
        $this->assertEquals(1, $riskScore->refund_requests_count);
    }

    /** @test */
    public function it_calculates_risk_score_correctly_for_rejected_courses()
    {
        // 1 rejected course = 20 points
        Course::create([
            'instructor_id' => $this->instructor->id,
            'course_title' => 'Rejected Course',
            'approval_status' => 'rejected'
        ]);

        $riskScore = $this->service->recalculate($this->instructor->id);

        $this->assertEquals(20, $riskScore->risk_score);
        $this->assertEquals(1, $riskScore->rejected_courses_count);
    }

    /** @test */
    public function it_calculates_combined_risk_score_correctly()
    {
        // 1 report (30) + 1 refund (10) + 1 rejected course (20) = 60
        ContentReport::create(['reported_user_id' => $this->instructor->id, 'reporter_id' => 1, 'status' => 'resolved', 'reason_code' => 'abuse']);
        RefundRequest::create(['instructor_id' => $this->instructor->id, 'user_id' => 1, 'order_id' => 1, 'status' => 'pending', 'requested_amount' => 100]);
        Course::create(['instructor_id' => $this->instructor->id, 'course_title' => 'Rejected Course', 'approval_status' => 'rejected']);

        $riskScore = $this->service->recalculate($this->instructor->id);

        $this->assertEquals(60, $riskScore->risk_score);
    }
}
