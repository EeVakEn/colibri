<?php

namespace App\Services;

use App\Models\Content;
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

    public function analyzeTranscription(Content $content)
    {
        $competenciesAndSkills = json_encode([
            'competencies' => [
                [
                    'id' => 1,
                    'name' => 'Управление проектами',
                    'description' => 'Компетенция, связанная с управлением проектами, включая планирование, контроль и координацию всех аспектов проекта.'
                ],
                [
                    'id' => 2,
                    'name' => 'Анализ данных',
                    'description' => 'Компетенция, связанная с анализом данных для принятия обоснованных решений и предсказаний на основе данных.'
                ]
            ],
            'skills' => [
                [
                    'id' => 1,
                    'competency_id' => 1,
                    'name' => 'Разработка плана проекта',
                    'description' => 'Навык разработки планов проектов, включая составление бюджета, сроков и задач для эффективного выполнения.'
                ],
                [
                    'id' => 2,
                    'competency_id' => 1,
                    'name' => 'Контроль выполнения проекта',
                    'description' => 'Навык мониторинга и контроля выполнения проекта в рамках запланированных сроков и бюджета.'
                ],
                [
                    'id' => 3,
                    'competency_id' => 2,
                    'name' => 'Работа с большими данными',
                    'description' => 'Навык работы с большими объемами данных, их обработка и анализ для нахождения закономерностей.'
                ],
                [
                    'id' => 4,
                    'competency_id' => 2,
                    'name' => 'Машинное обучение',
                    'description' => 'Навык применения алгоритмов машинного обучения для анализа данных и построения моделей.'
                ]
            ]
        ], JSON_UNESCAPED_UNICODE   );

        $content = $content->transcript->text;

        $prompt = str_replace(['{content}', '{competencies_and_skills}'], [$content, $competenciesAndSkills], config('ai.prompt'));
//dd($prompt);
        // Подготовка данных для отправки
        $data = json_encode([
            'model' => config('ai.model'),
            'response_format' => [ 'type' => 'json_object'],
            'temperature' => 1,
            'top_p' => 1,
            'top_k' => 1,
            'presence_penalty' => -1,
            'structured_outputs' => 1,
            'prompt'  => $prompt]);
        // Настройка cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('ai.router'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . config('ai.key'),
            'Content-Type: application/json'
        ]);

        // Выполнение запроса
        $response = curl_exec($ch);

        // Проверка ошибок
        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        // Закрытие соединения
        curl_close($ch);

        // Обработка ответа
        $data = json_decode($response, true);
        if (isset($data['error'])) {
            throw new \Exception('Text analysis error: ' . json_encode($data['error']));
        } else {
            $jsonString = $data['choices'][0]['text'];
            preg_match('/\[(?:\s*{[^}]+}\s*,?)*\]/s', $jsonString, $matches);;
            $data = [];
            if (!empty($matches[0])) {
                $json = $matches[0];
                $data = json_decode($json, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('JSON decode error');
                }
                return $data;
            } else {
                throw new \Exception('JSON decode error');
            }
        }

    }
}

