<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminResolveReportRequest;
use App\Models\ContentReport;
use App\Repositories\ContentReportRepository;
use App\Services\ModerationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminModerationController extends Controller
{
    protected $contentReportRepository;
    protected $moderationService;

    public function __construct(ContentReportRepository $contentReportRepository, ModerationService $moderationService)
    {
        $this->contentReportRepository = $contentReportRepository;
        $this->moderationService = $moderationService;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'status',
            'reportable_type',
            'reason_code',
            'course_id',
        ]);

        $reports = $this->contentReportRepository
            ->getQuery($filters)
            ->paginate(15)
            ->appends($filters);

        return view('backend.admin.moderation.index', compact('reports', 'filters'));
    }

    public function show(ContentReport $report)
    {
        $report = $this->contentReportRepository->findById($report->id);

        return view('backend.admin.moderation.show', compact('report'));
    }

    public function resolve(AdminResolveReportRequest $request, ContentReport $report)
    {
        try {
            $this->moderationService->resolveReport($report, $request->validated());

            return back()->with('success', 'Xử lý report thành công.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
