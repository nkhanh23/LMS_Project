<?php

namespace App\Services;

use App\Repositories\PayoutRepository;
use Illuminate\Validation\ValidationException;

class PayoutService
{
    protected $payoutRepository;

    public function __construct(PayoutRepository $payoutRepository)
    {
        $this->payoutRepository = $payoutRepository;
    }

    public function getAvailableBalance(int $instructorId): float
    {
        $totalRevenue = $this->payoutRepository->getTotalNetRevenue($instructorId);
        $totalWithdrawn = $this->payoutRepository->getTotalWithdrawn($instructorId);

        return max(0, $totalRevenue - $totalWithdrawn);
    }

    public function requestPayout(int $instructorId, array $data)
    {
        $availableBalance = $this->getAvailableBalance($instructorId);

        if ($data['amount'] > $availableBalance) {
            throw ValidationException::withMessages([
                'amount' => 'Số tiền yêu cầu vượt quá số dư khả dụng hiện tại.',
            ]);
        }

        if ($data['amount'] < 100000) {
            throw ValidationException::withMessages([
                'amount' => 'Số tiền rút tối thiểu là 100,000 VND.',
            ]);
        }

        $data['instructor_id'] = $instructorId;
        $data['status'] = 'pending';

        return $this->payoutRepository->createPayout($data);
    }

    public function getPayoutHistory(int $instructorId)
    {
        return $this->payoutRepository->getHistory($instructorId);
    }
}
