import sys
import whisper

# Получаем путь к аудиофайлу из аргументов
audio_path = sys.argv[1]

# Загружаем модель Whisper
model = whisper.load_model("base")

# Распознаем речь
result = model.transcribe(audio_path)

# Выводим распознанный текст
print(result["text"])
