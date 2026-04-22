<?php

namespace App\Jobs;

use App\Models\AiDocument;
use App\Services\AiDocumentIndexService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAiDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $documentId) {}

    /**
     * Execute the job.
     */
    public function handle(AiDocumentIndexService $indexService): void
    {
        $document = AiDocument::query()->findOrFail($this->documentId);
        $indexService->safeProcess($document);
    }
}
