<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Order;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    //Tất cả các cột đều được bảo vệ
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

    //Review

    public function courseReviews()
    {
        return $this->hasMany(CourseReviews::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    /**
     * Check if user has access to a course (admin, instructor, or purchased)
     *
     * @param Course $course
     * @return bool
     */
    public function hasAccessToCourse(Course $course)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->id === $course->instructor_id) {
            return true;
        }

        return $this->orders()
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->whereNotIn('refund_status', ['approved', 'processed'])
            ->whereNull('access_revoked_at')
            ->exists();
    }

    /**
     * Check if user has access to a quiz
     *
     * @param Quiz $quiz
     * @return bool
     */
    public function hasAccessToQuiz(Quiz $quiz)
    {
        // Quizzes are always linked to a course
        return $this->hasAccessToCourse($quiz->course);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Check if user is approved instructor
     *
     * @return bool
     */
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
}
