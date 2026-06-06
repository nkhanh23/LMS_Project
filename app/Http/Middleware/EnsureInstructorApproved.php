<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstructorApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized action');
        }

        if ($user->role !== 'instructor') {
            abort(403, 'Unauthorized action');
        }

        if ($user->instructor_approval_status !== 'approved') {
            return redirect()
                ->route('frontend.home')
                ->with('error', 'Tài khoản instructor của bạn chưa được phê duyệt hoặc đã bị tạm ngưng.');
        }

        if ($user->status !== '1') {
            return redirect()
                ->route('frontend.home')
                ->with('error', 'Tài khoản của bạn đang bị khóa.');
        }

        return $next($request);
    }
}
