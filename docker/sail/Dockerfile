FROM sail-8.3/app

# Обновляем пакеты и устанавливаем необходимые библиотеки для ffmpeg и Python
RUN apt-get update && apt-get install -y \
    python3 python3-pip python3-venv ffmpeg \
    build-essential python3-dev libsndfile1 \
    libsndfile1-dev libatlas-base-dev \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем виртуальное окружение Python
RUN python3 -m venv /opt/venv

# Активируем виртуальное окружение
ENV PATH="/opt/venv/bin:$PATH"

# Обновляем pip и устанавливаем Python-библиотеки для Whisper внутри виртуального окружения
RUN pip install --upgrade pip && \
    pip install torch torchvision torchaudio openai-whisper --no-cache-dir

# Копируем и компилируем Python-скрипт
WORKDIR /var/www/html

# Проводим компиляцию скрипта (опционально, если нужно)
RUN python3 -m compileall scripts/whisper_recognizer.py
