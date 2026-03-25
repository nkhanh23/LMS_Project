<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseQualityCheck;

class CourseQualityChecklistService
{
    public function evaluate(Course $course): array
    {
        $lectureCount = method_exists($course, 'lectures') ? $course->lectures()->count() : 0;
        $sectionCount = method_exists($course, 'sections') ? $course->sections()->count() : 0;

        $checks = [
            $this->makeCheck(
                'thumbnail',
                !empty($course->course_image),
                'Khóa học chưa có ảnh đại diện'
            ),
            $this->makeCheck(
                'description_length',
                strlen(strip_tags((string)$course->description)) >= 100,
                'Mô tả khóa học phải có ít nhất 100 ký tự'
            ),
            $this->makeCheck(
                'category',
                !empty($course->category_id),
                'Khóa học chưa có category'
            ),
            $this->makeCheck(
                'subcategory',
                !empty($course->subcategory_id),
                'Khóa học chưa có subcategory'
            ),
            $this->makeCheck(
                'sections',
                $sectionCount >= 1,
                'Khóa học cần ít nhất 1 section'
            ),
            $this->makeCheck(
                'lectures',
                $lectureCount >= 3,
                'Khóa học cần ít nhất 3 lectures'
            ),
            $this->makeCheck(
                'price',
                $course->selling_price !== null && $course->selling_price >= 0,
                'Giá khóa học không hợp lệ'
            ),
        ];

        return $checks;
    }

    public function sync(Course $course, ?int $adminId = null): array
    {
        $checks = $this->evaluate($course);

        foreach ($checks as $check) {
            CourseQualityCheck::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'check_key' => $check['check_key'],
                ],
                [
                    'status' => $check['status'],
                    'message' => $check['message'],
                    'reviewed_by' => $adminId,
                ]
            );
        }

        return $checks;
    }

    public function canApprove(Course $course): bool
    {
        $checks = $this->evaluate($course);

        foreach ($checks as $check) {
            if ($check['status'] === 'fail') {
                return false;
            }
        }

        return true;
    }

    protected function makeCheck(string $key, bool $passed, string $failMessage): array
    {
        return [
            'check_key' => $key,
            'status' => $passed ? 'pass' : 'fail',
            'message' => $passed ? null : $failMessage,
        ];
    }
}
