# C2C Образовательная Платформа

Этот проект представляет собой образовательную C2C (consumer-to-consumer) платформу, разрабатываемую в рамках дипломной работы ОмГТУ. Платформа позволяет пользователям обмениваться знаниями, создавая и анализируя контент.

## 🛠️ Технологии

Проект использует следующие технологии:
- **Backend**: Laravel
- **Frontend**: Inertia.js + Vue 3
- **Routing**: Ziggy
- **Аналитика видео**: Python-скрипты

## 🔍 Функциональность анализа видео

Процесс обработки видео включает в себя несколько этапов:
1. **Извлечение аудиодорожки** из видеофайла.
2. **Транскрибация** с помощью модели Whisper.
3. **Анализ текста**:
    - 3.0 **Перевод** на английский язык.
    - 3.1 **TF-IDF анализ** для определения важности терминов.
    - 3.2 **Embedding** (векторное представление текста).
    - 3.3 **Вычисление косинусного расстояния** между текстами.
    - 3.4 **Присвоение сущностей контента к скиллам**.

Весь анализ выполняется асинхронно с использованием очередей в фоне.

## 🚀 Полезные команды

Для запуска проекта используйте следующие команды:
```sh
# Запуск контейнеров
docker-compose up -d

# Запуск фронтенда
npm run dev

# Генерация маршрутов для Ziggy
php artisan ziggy:generate

# Создание символической ссылки для хранения файлов
php artisan storage:link
```

## 📌 Контакты
Если у вас есть вопросы или предложения, не стесняйтесь связаться со мной!

