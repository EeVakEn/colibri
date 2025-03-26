import sys
import json
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from sentence_transformers import SentenceTransformer
from deep_translator import GoogleTranslator  # Import the GoogleTranslator from deep-translator

# Инициализация моделей
embedding_model = SentenceTransformer('all-MiniLM-L6-v2')  # Компактная модель для эмбеддингов
tfidf_vectorizer = TfidfVectorizer()

def chunk_content(content, max_length=5000):
    """Разбивает контент на чанки по max_length символов."""
    chunks = []
    while len(content) > max_length:
        # Найти последний пробел перед лимитом
        split_point = content.rfind(' ', 0, max_length)
        if split_point == -1:
            # Если пробелов нет, просто разбиваем по max_length
            split_point = max_length
        chunks.append(content[:split_point].strip())
        content = content[split_point:].strip()
    if content:
        chunks.append(content)
    return chunks

def analyze_text(content, skills):
    try:
        # 1. Проверка на пустые данные
        if not content or not content.strip():
            return json.dumps({"error": "Content is empty"})

        if not skills or not isinstance(skills, list):
            return json.dumps({"error": "Skills list is empty or invalid"})

        # 2. Перевод контента с использованием chunking
        content_chunks = chunk_content(content, max_length=5000)
        translated_chunks = []

        for chunk in content_chunks:
            translated_chunk = GoogleTranslator(source='ru', target='en').translate(chunk)
            translated_chunks.append(translated_chunk)

        # Объединяем переведенные чанки в один текст
        content = " ".join(translated_chunks)
        print(f"[DEBUG] Translated content: {content}", file=sys.stderr)

        # Формируем список скиллов и их описаний
        skill_texts = []
        for skill in skills:
            if skill.get("name") and skill.get("description"):
                skill_texts.append(f"{skill['name']} {skill['description']}")
            else:
                return json.dumps({"error": "Missing name or description for some skills"})

        if not skill_texts:
            return json.dumps({"error": "No valid skill names or descriptions found"})

        # 3. TF-IDF анализ
        all_texts = [content] + skill_texts
        tfidf_matrix = tfidf_vectorizer.fit_transform(all_texts)

        if tfidf_matrix.shape[0] <= 1:
            return json.dumps({"error": "TF-IDF matrix is empty or invalid"})

        text_vector = tfidf_matrix[0]  # Вектор контента
        tfidf_similarities = cosine_similarity(text_vector, tfidf_matrix[1:])[0]

        # 4. Эмбеддинги
        content_embedding = embedding_model.encode(content, convert_to_numpy=True)
        skill_embeddings = np.array([embedding_model.encode(skill_text, convert_to_numpy=True) for skill_text in skill_texts])

        # Проверка на совпадение размерностей
        if len(skill_embeddings) != len(tfidf_similarities):
            return json.dumps({"error": "Mismatch in the number of embeddings and TF-IDF similarities"})

        similarities = cosine_similarity([content_embedding], skill_embeddings)[0]

        # 5. Объединение результатов
        results = []
        for i, skill in enumerate(skills):
            if i >= len(similarities) or i >= len(tfidf_similarities):
                continue  # Если индекс выходит за пределы, пропускаем
            relevance = (similarities[i] + tfidf_similarities[i]) / 2 * 100
            print(f"[DEBUG] {skill['name']} -> {relevance:.2f}%", file=sys.stderr)

            if relevance > 10:
                results.append({"id": skill["id"], "name": skill["name"], "depth": round(relevance)})

        # Сортируем результаты по глубине (от большего к меньшему)
        sorted_results = sorted(results, key=lambda x: x["depth"], reverse=True)

        # Возвращаем отсортированные результаты
        return json.dumps(sorted_results, ensure_ascii=False)

    except Exception as e:
        print(f"[ERROR] {str(e)}", file=sys.stderr)
        return json.dumps({"error": str(e)})

# Чтение входных данных
if __name__ == "__main__":
    try:
        input_data = json.loads(sys.stdin.read())

        # Проверка на наличие входных данных
        if input_data is None:
            print(json.dumps({"error": "Input data is None"}))
            sys.exit(1)

        content = input_data.get("content", "").strip()
        skills = input_data.get("skills", [])

        # Проверка на пустые данные
        if not content or not skills:
            print(json.dumps({"error": "Content or skills are missing in input"}))
            sys.exit(1)

        output = analyze_text(content, skills)
        print(output)  # JSON-вывод

    except Exception as e:
        print(json.dumps({"error": str(e)}))
