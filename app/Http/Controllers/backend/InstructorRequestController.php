<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\InstructorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorRequestController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        $latestRequest = InstructorRequest::where('user_id', $user->id)
            ->latest()
            ->first();

        return view('backend.user.become-instructor.index', compact('user', 'latestRequest'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'instructor') {
            return back()->with('error', 'Bạn đã là instructor.');
        }

        $hasPending = InstructorRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Bạn đã có yêu cầu chờ duyệt.');
        }

        $validated = $request->validate([
            'headline' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'bio' => 'required|string',
            'experience' => 'required|string',
        ]);

        InstructorRequest::create([
            'user_id' => $user->id,
            'headline' => $validated['headline'] ?? null,
            'phone' => $validated['phone'] ?? $user->phone,
            'bio' => $validated['bio'],
            'experience' => $validated['experience'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Yêu cầu đăng ký instructor đã được gửi tới hệ thống.');
    }
}
