<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Payment;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;

class BackendOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = $this->orderService->getAdminOrders($request->all());
        // Lấy lại filter để flash ra View (Giữ trạng thái input của user)
        $filters = $request->only([
            'start_date',
            'end_date',
            'min_amount',
            'max_amount',
            'payment_method'
        ]);
        $all_payments = Payment::latest()->get();
        return view('backend.admin.order.index', compact('all_payments', 'orders', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = $this->orderService->getAdminOrderDetail($id);
        $payment_info = $order->payment;
        $user_info = $order->user;

        return view('backend.admin.order.view', compact('order', 'payment_info', 'user_info'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
