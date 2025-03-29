<?php

namespace App\Jobs;

use App\Models\Content;
use App\Services\ContentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TranscribeVideoJob implements ShouldQueue
{

    public int $tries = 3;
    public int $timeout = 1000;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Content $content)
    {
    }

    public function handle(ContentService $contentService): void
    {
        $text = $contentService->transcriptVideo($this->content);
        $this->content->transcript()->create(['text' => $text]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->content->update([
            'processing_error' => $exception->getMessage()
        ]);
    }
}
