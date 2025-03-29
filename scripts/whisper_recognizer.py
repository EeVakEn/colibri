import sys
from faster_whisper import WhisperModel

# Получаем путь к аудиофайлу из аргументов
audio_path = sys.argv[1]

model = WhisperModel("tiny", compute_type="int8")

# Распознаем аудио
segments, _ = model.transcribe(audio_path)

# Выводим текст
print(" ".join(segment.text for segment in segments))
