<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminResolveReportRequest;
use App\Models\ContentReport;
use App\Repositories\ContentReportRepository;
use App\Services\ModerationService;
use App\Models\ModerationPolicy;
use App\Models\ModerationActionTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $policies = ModerationPolicy::where('is_active', true)->get();
        $actionTemplates = ModerationActionTemplate::where('is_active', true)->get();

        return view('backend.admin.moderation.show', compact(
            'report',
            'policies',
            'actionTemplates'
        ));
    }

    public function resolve(
        Request $request,
        ContentReport $report,
        ModerationService $moderationService
    ) {
        $request->validate([
            'policy_id' => ['required', 'exists:moderation_policies,id'],
            'action_template_id' => ['required', 'exists:moderation_action_templates,id'],
            'resolution_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $moderationService->resolveReport($report, $request->only([
            'policy_id',
            'action_template_id',
            'resolution_note',
        ]), Auth::id());

        return redirect()->back()->with('success', 'Đã xử lý report thành công');
    }
}
