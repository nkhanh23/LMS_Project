<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\InstructorSalesService;
use Illuminate\Http\Request;

class InstructorRevenueController extends Controller
{
    protected $salesService;
    public function __construct(InstructorSalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    public function dashboard(Request $request)
    {
        $instructorId = auth()->id();

        $filters = [
            'from_date' => $request->from_date,
            'to_date'   => $request->to_date,
        ];

        $data = $this->salesService->getDashboardData($instructorId, $filters);

        return view('backend.instructor.revenue.dashboard', $data);
    }
}
