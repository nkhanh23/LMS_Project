<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\InstructorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminInstructorRequestController extends Controller
{
    public function index()
    {
        $requests = InstructorRequest::with('user')
            ->latest()
            ->paginate(10);

        return view('backend.admin.instructor-request.index', compact('requests'));
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
        $requestItem = InstructorRequest::findOrFail($id);

        $validated = $request->validate([
            'admin_note' => 'required|string',
        ]);

        $requestItem->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Đã từ chối yêu cầu.');
    }
}
