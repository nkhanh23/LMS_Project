<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLearningAnalyticsController extends Controller
{
    protected $service;

    public function __construct(AdminLearningAnalyticsService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['course_id', 'user_id']);

        $data = [
            'courseStats' => $this->service->getCourseCompletionStats($filters),
            'userStats' => $this->service->getUserLearningStats($filters),
        ];

        return view('backend.admin.learning-analytics.index', $data);
    }
}
