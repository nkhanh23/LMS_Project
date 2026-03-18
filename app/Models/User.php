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

        return $this->orders()->where('course_id', $course->id)->exists();
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
}
