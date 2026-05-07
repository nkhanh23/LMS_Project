<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\InstructorDashboardService;

class InstructorController extends Controller
{
    protected $dashboardService;

    public function __construct(InstructorDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function login()
    {
        return view('backend.instructor.login.index');
    }

    public function dashboard()
    {
        $instructorId = Auth::user()->id;
        $data = $this->dashboardService->getDashboardData($instructorId);

        return view('backend.instructor.dashboard.index', $data);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/instructor/login');
    }
}
