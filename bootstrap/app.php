<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureInstructorApproved;
use App\Http\Middleware\EnsureCourseEnrollment;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'instructor.approved' => EnsureInstructorApproved::class,
            'course.enrollment' => EnsureCourseEnrollment::class,
        ]);

        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();
            if ($user) {
                if ($user->isAdmin()) {
                    return route('admin.dashboard');
                } elseif ($user->isApprovedInstructor()) {
                    return route('instructor.dashboard');
                } else {
                    return route('user.dashboard');
                }
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
