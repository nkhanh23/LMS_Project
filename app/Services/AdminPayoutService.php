<?php

namespace App\Services;

use App\Repositories\AdminPayoutRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\PayoutProcessedMail;
use App\Models\PayoutRequest;
use Illuminate\Support\Facades\DB;

class AdminPayoutService
{
    protected $payoutRepository;

    public function __construct(AdminPayoutRepository $payoutRepository)
    {
        $this->payoutRepository = $payoutRepository;
    }

    public function processPayout(int $id, string $status, array $data, int $adminId)
    {
        $payout = DB::transaction(function () use ($id, $status, $data, $adminId) {
            $payout = PayoutRequest::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($payout->status !== 'pending') {
                throw new \Exception('Yêu cầu rút tiền này đã được xử lý.');
            }

            $updateData = [
                'status' => $status,
                'admin_note' => $data['admin_note'] ?? null,
                'processed_at' => now(),
            ];

            if ($status === 'approved') {
                $updateData['transaction_reference'] = $data['transaction_reference'] ?? null;
            }

            $this->payoutRepository->updateStatus($payout, $updateData);

            return $payout;
        });

        // Gửi Mail ngoài transaction để tránh rollback nếu mail lỗi
        try {
            Mail::to($payout->instructor->email)->send(new PayoutProcessedMail($payout));
        } catch (\Exception $e) {
            // Log lỗi nếu cần, nhưng vẫn trả về payout đã thành công
            session()->flash('warning', 'Dữ liệu đã lưu nhưng không gửi được email thông báo: ' . $e->getMessage());
        }

        return $payout;
    }
}
