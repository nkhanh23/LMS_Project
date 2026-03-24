<?php

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCourseEnrollment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');
        $course = Course::where('course_name_slug', $slug)->first();

        if (!$course) {
            abort(404);
        }

        $user = $request->user();

        if (!$user || !$user->hasAccessToCourse($course)) {
            abort(403, 'Bạn chưa có quyền truy cập khóa học này.');
        }

        return $next($request);
    }
}
