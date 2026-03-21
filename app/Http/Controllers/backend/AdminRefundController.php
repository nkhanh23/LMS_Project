<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminManualRefundRequest;
use App\Http\Requests\AdminManualCancelRequest;
use App\Http\Requests\AdminReviewRefundRequest;
use App\Services\OrderService;
use App\Services\RefundService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRefundController extends Controller
{
    protected $refundService;
    protected $orderService;

    public function __construct(RefundService $refundService, OrderService $orderService)
    {
        $this->refundService = $refundService;
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $refundRequests = $this->refundService->getRefundRequests($request->all());

        return view('backend.admin.order.refund-requests', compact('refundRequests'));
    }

    public function showOrder(int $orderId)
    {
        $order = $this->orderService->getAdminOrderDetail($orderId);

        return view('backend.admin.order.show', compact('order'));
    }

    public function approve(AdminReviewRefundRequest $request, int $refundRequestId)
    {
        $this->refundService->approveRefundRequest(
            $refundRequestId,
            Auth::user(),
            $request->validated()
        );

        return redirect()->back()->with('success', 'Approve refund thành công.');
    }

    public function reject(AdminReviewRefundRequest $request, int $refundRequestId)
    {
        $this->refundService->rejectRefundRequest(
            $refundRequestId,
            Auth::user(),
            $request->validated()
        );

        return redirect()->back()->with('success', 'Reject refund thành công.');
    }

    public function manualRefund(AdminManualRefundRequest $request, int $orderId)
    {
        $this->refundService->manualRefund(
            $orderId,
            Auth::user(),
            $request->validated()
        );

        return redirect()->back()->with('success', 'Manual refund thành công.');
    }

    public function manualCancel(AdminManualCancelRequest $request, int $orderId)
    {
        $this->refundService->manualCancel(
            $orderId,
            Auth::user(),
            $request->validated()
        );

        return redirect()->back()->with('success', 'Manual cancel thành công.');
    }
}
