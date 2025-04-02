<?php

namespace App\Services;

use App\Jobs\AnalyzeSkillsJob;
use App\Jobs\TranscribeVideoJob;
use App\Models\Content;
use App\Models\Skill;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Bus;

class ContentService
{
    public function processContent(Content $content): void
    {
        try {
            if ($content->type === Content::TYPE_VIDEO && $content->video && file_exists($content->path)) {
                Bus::chain([
                    new TranscribeVideoJob($content),
                    new AnalyzeSkillsJob($content),
                ])->dispatch();
            } else {
                dispatch(new AnalyzeSkillsJob($content));
            }
        } catch (\Throwable $exception) {
            $content->processing_error = $exception->getMessage();
            $content->save();
        }
    }

    public function transcriptVideo(Content $content): string
    {
        $uuid = Uuid::uuid4()->toString();
        $audioPath = Storage::disk('public')->path("tmp/$uuid.wav");
        $pythonScript = base_path('scripts/whisper_recognizer.py');

        $this->extractAudio($content->path, $audioPath);

        $text = $this->recognizeSpeech($audioPath, $pythonScript, $content);

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
            Log::error($process->getOutput() . PHP_EOL . $process->getErrorOutput());
            throw new \RuntimeException('Audio extract error');
        }
    }


    protected function recognizeSpeech(string $audioPath, string $pythonScript, Content $content): string
    {
        $process = new Process([config('env.python_path'), $pythonScript, $audioPath]);
        $process->run();
        $process->wait();
        if (!$process->isSuccessful()) {
            Log::error($process->getOutput() . PHP_EOL . $process->getErrorOutput());
            throw new \RuntimeException('Speech recognition error');
        }

        return trim($process->getOutput());
    }

    public function getSkills($content)
    {
        $contentText = strip_tags($content->content . $content->transcript?->text);
        $skills = Skill::query()->get(['id', 'name', 'description'])->toArray();
        $script = base_path('scripts/analyze.py');
        $process = new Process(['python3', $script]);
        $process->setInput(json_encode([
            'content' => $contentText,
            'skills' => $skills,
        ]));
        $process->run();
        if (!$process->isSuccessful()) {
            Log::error($process->getOutput() . PHP_EOL . $process->getErrorOutput());
            throw new \RuntimeException('Text analyze error');
        }
        return collect(json_decode($process->getOutput()))->shift(5);
    }

    public static function saveVideo($videoFile): array
    {
        if (is_string($videoFile)) {
            $filePath = $videoFile;
        } else {
            $uuid = Uuid::uuid4()->toString();
            $extension = $videoFile->getClientOriginalExtension();
            $uniqueFileName = $uuid . '.' . $extension;
            $filePath = 'videos/' . $uniqueFileName;
            Storage::disk('public')->putFileAs('videos', $videoFile, $uniqueFileName);
        }

        return ['path' => $filePath, 'duration' => self::getDuration(Storage::disk('public')->path($filePath))];
    }

    public static function getDuration($filePath): float
    {
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($filePath);
        return $video->getFormat()->get('duration');
    }
}

