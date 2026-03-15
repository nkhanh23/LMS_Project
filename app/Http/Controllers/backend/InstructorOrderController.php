<?php

namespace App\Http\Controllers\backend;

use App\Exports\InstructorOrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InstructorSalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class InstructorOrderController extends Controller
{
    protected $salesService;
    public function __construct(InstructorSalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    public function index(Request $request)
    {
        $instructorId = auth()->id();

        $filters = [
            'from_date' => $request->from_date,
            'to_date'   => $request->to_date,
            'course_id' => $request->course_id,
        ];

        $orders = $this->salesService->getOrdersData($instructorId, $filters);
        $courses = $this->salesService->getInstructorCourses($instructorId);
        return view('backend.instructor.order.index', compact('orders', 'courses'));
    }

    public function show(int $id)
    {
        $instructorId = auth()->id();

        $order = $this->salesService->getOrderDetail($instructorId, $id);

        return view('backend.instructor.order.edit', compact('order'));
    }

    public function exportCsv(Request $request)
    {
        $instructorId = auth()->id();

        $filters = [
            'from_date' => $request->from_date,
            'to_date'   => $request->to_date,
            'course_id' => $request->course_id,
        ];

        $headersRow = $this->salesService->getOrdersExportHeaders();
        $rows = $this->salesService->getOrdersExportData($instructorId, $filters);

        $fileName = 'instructor-orders-' . now()->format('Y-m-d_H-i-s') . '.csv';

        $responseHeaders = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return response()->stream(function () use ($headersRow, $rows) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, $headersRow);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, $responseHeaders);
    }

    public function exportExcel(Request $request)
    {
        $instructorId = auth()->id();

        $filters = [
            'from_date' => $request->from_date,
            'to_date'   => $request->to_date,
            'course_id' => $request->course_id,
        ];

        $headers = $this->salesService->getOrdersExportHeaders();
        $rows = $this->salesService->getOrdersExportData($instructorId, $filters);

        $fileName = 'instructor-orders-' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(
            new InstructorOrdersExport($headers, $rows),
            $fileName
        );
    }
}
