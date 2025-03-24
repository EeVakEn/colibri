<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Skill;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Process\Process;

class SpeechRecognitionService implements ShouldQueue
{
    use Queueable;

    public function processVideo(Content $content): string
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

    protected function extractAudio(string $videoPath, string $audioPath)
    {
        $process = new Process([
            config('env.ffmpeg_path'), '-i', $videoPath, '-vn', '-acodec', 'pcm_s16le',
            '-ar', '16000', '-ac', '1', $audioPath, '-hide_banner', '-loglevel', 'error'
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Audio extract error: ' . $process->getErrorOutput());
        }
    }

    protected function recognizeSpeech(string $audioPath, string $pythonScript): string
    {
        $process = new Process([config('env.python_path'), $pythonScript, $audioPath]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Speech recognition error: ' . $process->getErrorOutput());
        }

        return trim($process->getOutput());
    }

    public function analyzeTranscription($content)
    {
        $contentText = $content->transcript->text;
        $skills = Skill::query()->get(['id', 'name', 'description'])->toArray();

        $process = new Process(['python3', 'scripts/analyze.py']);
        $process->setInput(json_encode([
            'content' => $contentText,
            'skills' => $skills,
        ]));
        $process->run();

        if (!$process->isSuccessful()) {
            dd($process->getErrorOutput());
        }
        return collect(json_decode($process->getOutput()))->shift(5);
    }

}

