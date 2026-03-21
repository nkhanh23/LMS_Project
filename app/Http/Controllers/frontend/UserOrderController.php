<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRefundRequestStoreRequest;
use App\Services\OrderService;
use App\Services\RefundService;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    protected $refundService;
    protected $orderService;

    public function index()
    {
        $orders = $this->orderService->getUserOrders(Auth::id());

        return view('backend.user.order.index', compact('orders'));
    }
    public function __construct(RefundService $refundService, OrderService $orderService)
    {
        $this->refundService = $refundService;
        $this->orderService = $orderService;
    }

    public function show(int $orderId)
    {
        $order = $this->orderService->getUserOrderDetail(Auth::id(), $orderId);

        return view('backend.user.order.view', compact('order'));
    }

    public function requestRefund(UserRefundRequestStoreRequest $request, int $orderId)
    {
        $this->refundService->createUserRefundRequest(
            $orderId,
            Auth::user(),
            $request->validated()
        );

        return redirect()
            ->back()
            ->with('success', 'Đã gửi yêu cầu refund thành công.');
    }
}
