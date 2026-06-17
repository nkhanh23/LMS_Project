<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Order;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\InstructorRiskScore;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    //Check role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function hasLearnerAccess(): bool
    {
        if ($this->isAdmin()) {
            return false;
        }

        if ($this->isUser()) {
            return true;
        }

        return $this->enrollments()->where('status', 'active')->exists()
            || $this->orders()->where('status', 'completed')->exists();
    }

    public function preferredDashboardRoute(): string
    {
        if ($this->isAdmin()) {
            return 'admin.dashboard';
        }

        if ($this->isInstructor() && ! $this->hasLearnerAccess()) {
            return 'instructor.dashboard';
        }

        return 'user.dashboard';
    }

    //Review

    public function courseReviews()
    {
        return $this->hasMany(CourseReviews::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    // kiểm tra user có quyền truy cập vào khóa học hay không
    public function hasAccessToCourse(Course $course)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->id === $course->instructor_id) {
            return true;
        }

        $hasEnrollment = $this->enrollments()
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();

        if ($hasEnrollment) {
            return true;
        }

        // fallback tạm trong giai đoạn chuyển dữ liệu
        return $this->orders()
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->whereNotIn('refund_status', ['approved', 'processed'])
            ->whereNull('access_revoked_at')
            ->exists();
    }

    // kiểm tra user có quyền truy cập vào quiz hay không
    public function hasAccessToQuiz(Quiz $quiz)
    {
        // Quizzes are always linked to a course
        return $this->hasAccessToCourse($quiz->course);
    }

    // kiểm tra user có phải là giảng viên đã được phê duyệt hay không
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Kiểm tra user có phải là giảng viên đã được phê duyệt hay không
    public function isApprovedInstructor(): bool
    {
        return $this->role === 'instructor'
            && $this->instructor_approval_status === 'approved'
            && $this->status === '1';
    }

    public function isSuspendedInstructor(): bool
    {
        return $this->role === 'instructor'
            && $this->instructor_approval_status === 'suspended';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //Admin Audit Log
    public function adminAuditLogs()
    {
        return $this->hasMany(AdminAuditLog::class, 'admin_id', 'id');
    }

    //Content Report
    public function contentReports()
    {
        return $this->hasMany(ContentReport::class, 'reporter_id', 'id');
    }

    //Enrollment
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function activeEnrollments()
    {
        return $this->hasMany(Enrollment::class)->where('status', 'active');
    }

    //Course Progress
    public function courseProgress()
    {
        return $this->hasMany(CourseProgress::class);
    }

    public function activeCourseProgress()
    {
        return $this->hasMany(CourseProgress::class)->where('status', 'active');
    }

    public function setting()
    {
        return $this->hasOne(UserSetting::class);
    }

    // Instructor Risk Score
    public function riskScore()
    {
        return $this->hasOne(InstructorRiskScore::class, 'instructor_id');
    }

    // Lấy thông tin Risk Level Badge
    public function getRiskLevelAttribute()
    {
        $score = $this->riskScore->risk_score ?? 0;

        if ($score >= 100) {
            return ['class' => 'bg-danger', 'label' => 'Rất cao', 'score' => $score];
        } elseif ($score >= 60) {
            return ['class' => 'bg-warning text-dark', 'label' => 'Cao', 'score' => $score];
        } elseif ($score >= 30) {
            return ['class' => 'bg-info text-dark', 'label' => 'Trung bình', 'score' => $score];
        }

        return ['class' => 'bg-success', 'label' => 'Thấp', 'score' => $score];
    }

    //
    public function aiChatSessions()
    {
        return $this->hasMany(AiChatSession::class);
    }

    public function aiChatMessages()
    {
        return $this->hasMany(AiChatMessage::class);
    }

    public function getAvailableBalanceAttribute()
    {
        // 1. Tính tổng doanh thu Net từ bảng orders (trạng thái completed)
        $totalRevenue = DB::table('orders')
            ->where('instructor_id', $this->id)
            ->where('status', 'completed')
            ->sum('net_amount');

        // 2. Tính tổng tiền ĐÃ RÚT HOẶC ĐANG CHỜ duyệt
        $totalWithdrawnOrPending = DB::table('payout_requests')
            ->where('instructor_id', $this->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        // 3. Trừ đi để ra số dư khả dụng
        return max(0, $totalRevenue - $totalWithdrawnOrPending);
    }
}
