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

class AnalyzeSkillsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //public int $tries = 3;
    public int $timeout = 1000;
    public function __construct(protected Content $content) {}

    public function handle(ContentService $contentService): void
    {
        $skills = $contentService->getSkills($this->content);
        $this->content->skills()->sync($skills->mapWithKeys(fn($item) => [$item->id => ['depth' => $item->depth]])->toArray());
        $this->content->update(['processed_at'=> now()]);
    }

    public function failed(\Throwable $exception): void
    {
       $this->content->update([
            'processing_error' => $exception->getMessage()
        ]);
    }
}

