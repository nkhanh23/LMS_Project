<?php

namespace App\Services;

use App\Repositories\GovernanceQueueRepository;

class GovernanceQueueService
{
    protected $repository;

    public function __construct(GovernanceQueueRepository $repository)
    {
        $this->repository = $repository;
    }
    public function getDashboardData(): array
    {
        return [
            'stats' => $this->repository->getQueueStats(),
            'queueItems' => $this->repository->getUnifiedQueue(10),
        ];
    }
}
