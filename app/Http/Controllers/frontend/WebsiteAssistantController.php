<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteAssistantRequest;
use App\Services\WebsiteAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class WebsiteAssistantController extends Controller
{
    public function __construct(
        protected WebsiteAssistantService $websiteAssistantService
    ) {}

    public function ask(WebsiteAssistantRequest $request): JsonResponse
    {
        try {
            $result = $this->websiteAssistantService->ask(
                userId: (int) Auth::id(),
                message: $request->string('message')->toString()
            );

            return response()->json([
                'success' => true,
                'message' => 'Yeu cau da duoc ghi nhan.',
                'data' => $result,
            ]);
        } catch (Throwable $e) {
            report($e);

            $message = $e->getMessage() === 'AI đang quá tải, vui lòng thử lại sau.'
                ? 'AI đang quá tải, vui lòng thử lại sau.'
                : 'Khong the xu ly cau hoi luc nay.';

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 500);
        }
    }

    public function history(WebsiteAssistantRequest $request): JsonResponse
    {
        try {
            $data = $this->websiteAssistantService->getHistory((int) Auth::id());

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Khong the tai lich su chat luc nay.',
            ], 500);
        }
    }

    public function newSession(WebsiteAssistantRequest $request): JsonResponse
    {
        try {
            $data = $this->websiteAssistantService->createNewSession((int) Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Da tao phien chat moi.',
                'data' => $data,
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Khong the tao phien chat moi luc nay.',
            ], 500);
        }
    }
}
