<?php

namespace App\Services;

use App\Jobs\AnalyzeSkillsJob;
use App\Jobs\TranscribeVideoJob;
use App\Models\Content;
use App\Models\Skill;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Bus;

class ContentService
{
    public function processContent(Content $content): void
    {
        if ($content->type === Content::TYPE_VIDEO && $content->video && file_exists($content->path)) {
            Bus::chain([
                new TranscribeVideoJob($content),
                new AnalyzeSkillsJob($content),
            ])->dispatch();
        } else {
            dispatch(new AnalyzeSkillsJob($content));
        }
    }

    public function transcriptVideo(Content $content): string
    {
        $uuid = Uuid::uuid4()->toString();
        $audioPath = Storage::disk('public')->path("tmp/$uuid.wav");
        $pythonScript = base_path('scripts/whisper_recognizer.py');

        // Извлекаем аудио
        $this->extractAudio($content->path, $audioPath);

        // Распознаём речь
        $text = $this->recognizeSpeech($audioPath, $pythonScript);

        // Удаляем временный файл
        unlink($audioPath);

        return $text;
    }

    protected function extractAudio(string $videoPath, string $audioPath): void
    {
        $process = new Process([
            config('env.ffmpeg_path'), '-i', $videoPath, '-vn', '-acodec', 'libmp3lame',
            '-b:a', '64k', '-ar', '16000', '-ac', '1', $audioPath, '-hide_banner', '-loglevel', 'error'
        ]);
        $process->run();
        $process->wait();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Audio extract error: ' . $process->getErrorOutput());
        }
    }

    protected function recognizeSpeech(string $audioPath, string $pythonScript): string
    {
        $process = new Process([config('env.python_path'), $pythonScript, $audioPath]);
        $process->run();
        $process->wait();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Speech recognition error: ' . $process->getErrorOutput());
        }

        return trim($process->getOutput());
    }

    public function getSkills($content)
    {
        $contentText = $content->description . $content->transcript?->text;
        $skills = Skill::query()->get(['id', 'name', 'description'])->toArray();
        $script = base_path('scripts/analyze.py');
        $process = new Process(['python3', $script]);
        $process->setInput(json_encode([
            'content' => $contentText,
            'skills' => $skills,
        ]));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Text analyze error: ' . $process->getErrorOutput());
        }
        return collect(json_decode($process->getOutput()))->shift(5);
    }
}

