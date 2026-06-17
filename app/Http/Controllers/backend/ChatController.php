<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $routeName = $request->route()?->getName();

        // Lấy danh sách hội thoại của user hiện tại (là student hoặc instructor)
        $conversations = Conversation::where('student_id', $userId)
            ->orWhere('instructor_id', $userId)
            ->with(['student', 'instructor', 'messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->get();

        if (str_starts_with((string) $routeName, 'instructor.')) {
            return view('backend.instructor.chat.index', compact('conversations'));
        }

        $chatLayout = match (true) {
            str_starts_with((string) $routeName, 'user.') => 'backend.user.master',
            default => 'frontend.master',
        };

        return view('frontend.pages.chat.index', compact('conversations', 'chatLayout'));
    }

    public function redirectToRoleChat()
    {
        $user = Auth::user();

        return match ($user?->role) {
            'instructor' => redirect()->route('instructor.chat.index', request()->query()),
            'user' => redirect()->route('user.chat.index', request()->query()),
            default => redirect()->route('frontend.home'),
        };
    }

    // Bắt đầu hoặc lấy phiên chat
    public function getConversation($otherUserId)
    {
        $currentUserId = Auth::id();

        // Tìm cuộc hội thoại giữa 2 người này (không quan trọng ai là student/instructor)
        $conversation = Conversation::where(function ($q) use ($currentUserId, $otherUserId) {
            $q->where('student_id', $currentUserId)->where('instructor_id', $otherUserId);
        })->orWhere(function ($q) use ($currentUserId, $otherUserId) {
            $q->where('student_id', $otherUserId)->where('instructor_id', $currentUserId);
        })->first();

        // Nếu chưa có thì tạo mới (Giả định người được click là Instructor nếu user hiện tại không phải là Instructor)
        // Tuy nhiên logic tốt nhất là dựa trên role. Ở đây ta có logic đơn giản: 
        // Nếu không tìm thấy, ta tạo mới với user hiện tại là student (mặc định)
        if (!$conversation) {
            $conversation = Conversation::create([
                'student_id' => $currentUserId,
                'instructor_id' => $otherUserId
            ]);
        }

        $conversation->load(['student', 'instructor']);

        $messages = $conversation->messages()->with('sender:id,name,photo')->get();

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    // Gửi tin nhắn
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $conversation = Conversation::findOrFail($conversationId);
        $userId = Auth::id();

        // Kiểm tra quyền
        if ($userId !== $conversation->student_id && $userId !== $conversation->instructor_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'message' => $request->message,
            'is_read' => false
        ]);

        // Phát sóng sự kiện cho người kia
        try {
            $pendingBroadcast = broadcast(new MessageSent($message));
            $pendingBroadcast->toOthers();
            unset($pendingBroadcast);
        } catch (\Throwable $error) {
            Log::warning('Chat message broadcast failed.', [
                'message_id' => $message->id,
                'conversation_id' => $conversation->id,
                'error' => $error->getMessage(),
            ]);
        }

        // trả về json để frontend cập nhật UI
        return response()->json([
            'status' => 'success',
            'message' => $message->load('sender:id,name,photo')
        ]);
    }
}
