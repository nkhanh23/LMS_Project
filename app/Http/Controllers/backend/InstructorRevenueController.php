<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Services\InstructorSalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PayoutService;

class InstructorRevenueController extends Controller
{
    protected $salesService;
    protected $payoutService;
    public function __construct(InstructorSalesService $salesService, PayoutService $payoutService)
    {
        $this->salesService = $salesService;
        $this->payoutService = $payoutService;
    }

    public function dashboard(Request $request)
    {
        $instructorId = auth()->id();

        $filters = [
            'from_date' => $request->from_date,
            'to_date'   => $request->to_date,
        ];

        $data = $this->salesService->getDashboardData($instructorId, $filters);
        $data['available_balance'] = $this->payoutService->getAvailableBalance($instructorId);
        $data['payout_history'] = $this->payoutService->getPayoutHistory($instructorId);

        return view('backend.instructor.revenue.dashboard', $data);
    }


    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100000', // Rút tối thiểu 100,000đ
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
        ]);

        $instructor = auth()->user();
        $amountRequested = $request->input('amount');

        // Gọi Accessor tính toán động từ Model User
        if ($instructor->available_balance < $amountRequested) {
            return back()->withErrors(['amount' => 'Số dư khả dụng không đủ để thực hiện giao dịch này.']);
        }

        // Tạo request rút tiền mới với đầy đủ thông tin
        PayoutRequest::create([
            'instructor_id' => $instructor->id,
            'amount' => $amountRequested,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Yêu cầu rút tiền đã được gửi thành công. Đang chờ quản trị viên phê duyệt!');
    }
}
