<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\InstructorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminInstructorRequestController extends Controller
{
    public function index(Request $request)
    {
        $requestStatus = $request->input('request_status');
        $requestKeyword = $request->input('request_keyword');

        $instructorStatus = $request->input('instructor_status');
        $instructorKeyword = $request->input('instructor_keyword');

        $requests = InstructorRequest::query()
            ->with('user')
            ->when($requestStatus, function ($query) use ($requestStatus) {
                $query->where('status', $requestStatus);
            })
            ->when($requestKeyword, function ($query) use ($requestKeyword) {
                $query->where(function ($q) use ($requestKeyword) {
                    $q->where('headline', 'like', '%' . $requestKeyword . '%')
                        ->orWhere('phone', 'like', '%' . $requestKeyword . '%')
                        ->orWhere('bio', 'like', '%' . $requestKeyword . '%')
                        ->orWhereHas('user', function ($userQuery) use ($requestKeyword) {
                            $userQuery->where('name', 'like', '%' . $requestKeyword . '%')
                                ->orWhere('email', 'like', '%' . $requestKeyword . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'requests_page')
            ->appends($request->query());

        $instructors = User::query()
            ->where('role', 'instructor')
            ->when($instructorStatus, function ($query) use ($instructorStatus) {
                $query->where('instructor_approval_status', $instructorStatus);
            })
            ->when($instructorKeyword, function ($query) use ($instructorKeyword) {
                $query->where(function ($q) use ($instructorKeyword) {
                    $q->where('name', 'like', '%' . $instructorKeyword . '%')
                        ->orWhere('email', 'like', '%' . $instructorKeyword . '%')
                        ->orWhere('phone', 'like', '%' . $instructorKeyword . '%');
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'instructors_page')
            ->appends($request->query());

        return view('backend.admin.instructor-request.index', compact(
            'requests',
            'instructors',
            'requestStatus',
            'requestKeyword',
            'instructorStatus',
            'instructorKeyword'
        ));
    }

    public function approve($id)
    {
        $requestItem = InstructorRequest::with('user')->findOrFail($id);

        if ($requestItem->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        DB::transaction(function () use ($requestItem) {
            $requestItem->user->update([
                'role' => 'instructor',
                'phone' => $requestItem->phone ?? $requestItem->user->phone,
                'bio' => $requestItem->bio ?? $requestItem->user->bio,
                'experience' => $requestItem->experience ?? $requestItem->user->experience,
                'status' => '1',
                'instructor_approval_status' => 'approved',
                'instructor_review_note' => null,
                'instructor_reviewed_by' => auth()->id(),
                'instructor_reviewed_at' => now(),
            ]);

            $requestItem->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        });

        return back()->with('success', 'Đã phê duyệt instructor.');
    }

    public function reject(Request $request, $id)
    {
        $requestItem = InstructorRequest::with('user')->findOrFail($id);

        $validated = $request->validate([
            'admin_note' => 'required|string|max:2000',
        ]);

        if ($requestItem->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        DB::transaction(function () use ($requestItem, $validated) {
            $requestItem->update([
                'status' => 'rejected',
                'admin_note' => $validated['admin_note'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            $requestItem->user->update([
                'instructor_approval_status' => 'pending',
                'instructor_review_note' => $validated['admin_note'],
                'instructor_reviewed_by' => auth()->id(),
                'instructor_reviewed_at' => now(),
            ]);
        });

        return back()->with('success', 'Đã từ chối yêu cầu.');
    }

    public function approveInstructor($id)
    {
        $user = User::findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->update([
                'role' => 'instructor',
                'status' => '1',
                'instructor_approval_status' => 'approved',
                'instructor_review_note' => null,
                'instructor_reviewed_by' => auth()->id(),
                'instructor_reviewed_at' => now(),
            ]);

            InstructorRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->first()?->update([
                    'status' => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ]);
        });

        return back()->with('success', 'Đã approve instructor.');
    }

    public function suspendInstructor(Request $request, $id)
    {
        $validated = $request->validate([
            'review_note' => 'required|string|max:2000',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'instructor_approval_status' => 'suspended',
            'instructor_review_note' => $validated['review_note'],
            'instructor_reviewed_by' => auth()->id(),
            'instructor_reviewed_at' => now(),
            'status' => '0',
        ]);

        return back()->with('success', 'Đã suspend instructor.');
    }
}
