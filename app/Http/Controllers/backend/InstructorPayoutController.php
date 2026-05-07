<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InstructorPayoutController extends Controller
{
    protected $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index()
    {
        $instructorId = Auth::id();
        $availableBalance = $this->payoutService->getAvailableBalance($instructorId);
        $payouts = $this->payoutService->getPayoutHistory($instructorId);

        return view('backend.instructor.payout.index', compact('availableBalance', 'payouts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ], [
            'amount.required' => 'Vui lòng nhập số tiền cần rút.',
            'amount.numeric' => 'Số tiền không hợp lệ.'
        ]);

        try {
            $this->payoutService->requestPayout(Auth::id(), $request->only('amount'));

            return back()->with('success', 'Yêu cầu rút tiền đã được gửi thành công.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
