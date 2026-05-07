<?php

namespace App\Repositories;

use App\Models\PayoutRequest;

class AdminPayoutRepository
{
    public function getAllPayouts(string $status = null, int $perPage = 15)
    {
        $query = PayoutRequest::with('instructor');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): PayoutRequest
    {
        return PayoutRequest::with('instructor')->findOrFail($id);
    }

    public function updateStatus(PayoutRequest $payout, array $data): bool
    {
        return $payout->update($data);
    }
}
