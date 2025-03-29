FROM sail-8.3/app

# Обновляем пакеты и устанавливаем необходимые библиотеки
RUN apt-get update && apt-get install -y \
    python3 python3-pip python3-venv ffmpeg \
    build-essential python3-dev libsndfile1 \
    libsndfile1-dev libatlas-base-dev \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем виртуальное окружение Python
RUN python3 -m venv /opt/venv

# Активируем виртуальное окружение
ENV PATH="/opt/venv/bin:$PATH"

# Обновляем pip и устанавливаем библиотеки для анализа текста
RUN pip install --upgrade pip && \
    pip install torch torchvision torchaudio faster-whisper \
    numpy pandas scikit-learn \
    sentence-transformers  deep-translator==1.11.4 \
    && rm -rf /root/.cache/pip

# Копируем Python-скрипт в контейнер
WORKDIR /var/www/html
COPY scripts/analyze.py scripts/analyze.py

# Компилируем Python-скрипт (опционально)
RUN python3 -m compileall scripts/analyze.py

RUN echo "memory_limit=3G" >> /etc/php/8.3/cli/php.ini
RUN echo "upload_max_filesize=3G" >> /etc/php/8.3/cli/php.ini
RUN echo "post_max_size=3G" >> /etc/php/8.3/cli/php.ini
