<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Services\AdminPayoutService;
use App\Repositories\AdminPayoutRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPayoutController extends Controller
{
    protected $payoutService;
    protected $payoutRepository;

    public function __construct(AdminPayoutService $payoutService, AdminPayoutRepository $payoutRepository)
    {
        $this->payoutService = $payoutService;
        $this->payoutRepository = $payoutRepository;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $payouts = $this->payoutRepository->getAllPayouts($status);
        return view('backend.admin.payout.index', compact('payouts', 'status'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'transaction_reference' => 'required|string|max:255',
            'admin_note' => 'nullable|string|max:1000'
        ]);

        try {
            $this->payoutService->processPayout($id, 'approved', $request->all(), Auth::id());
            return back()->with('success', 'Đã duyệt yêu cầu rút tiền và gửi email cho Giảng viên.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000'
        ]);

        try {
            $this->payoutService->processPayout($id, 'rejected', $request->all(), Auth::id());
            return back()->with('success', 'Đã từ chối yêu cầu rút tiền và gửi email thông báo.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
