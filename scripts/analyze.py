import sys
import json
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from sentence_transformers import SentenceTransformer
from deep_translator import GoogleTranslator
import requests

# Инициализация моделей
embedding_model = SentenceTransformer('all-MiniLM-L6-v2')
tfidf_vectorizer = TfidfVectorizer()

def chunk_content(content, max_length=5000):
    chunks = []
    while len(content) > max_length:
        split_point = content.rfind(' ', 0, max_length)
        if split_point == -1:
            split_point = max_length
        chunks.append(content[:split_point].strip())
        content = content[split_point:].strip()
    if content:
        chunks.append(content)
    return chunks

def translate_content(content_chunks):
    translated_chunks = []
    for chunk in content_chunks:
        try:
            translated_chunk = GoogleTranslator(source='ru', target='en').translate(chunk)
            translated_chunks.append(translated_chunk)
        except requests.exceptions.RequestException as e:
            print(f"[ERROR] Translation API error: {e}", file=sys.stderr)
            translated_chunks.append(f"[ERROR] Could not translate chunk")
        except Exception as e:
            print(f"[ERROR] Unexpected error during translation: {e}", file=sys.stderr)
            translated_chunks.append(f"[ERROR] Unexpected error occurred")
    return translated_chunks

def analyze_text(content, skills):
    try:
        if not content or not content.strip():
            return json.dumps({"error": "Content is empty"})
        if not skills or not isinstance(skills, list):
            return json.dumps({"error": "Skills list is empty or invalid"})

        content_chunks = chunk_content(content, max_length=5000)
        translated_chunks = translate_content(content_chunks)
        content = " ".join(translated_chunks)

        skill_texts = [skill["name"] for skill in skills if "name" in skill and skill.get("name")]
        if not skill_texts:
            return json.dumps({"error": "No valid skill names found"})

        all_texts = [content] + skill_texts
        tfidf_matrix = tfidf_vectorizer.fit_transform(all_texts)
        text_vector = tfidf_matrix[0]
        tfidf_similarities = cosine_similarity(text_vector, tfidf_matrix[1:])[0]

        content_embedding = embedding_model.encode(content, convert_to_numpy=True)
        skill_embeddings = np.array([embedding_model.encode(skill_text, convert_to_numpy=True) for skill_text in skill_texts])

        if len(skill_embeddings) != len(tfidf_similarities):
            return json.dumps({"error": "Mismatch in the number of embeddings and TF-IDF similarities"})

        similarities = cosine_similarity([content_embedding], skill_embeddings)[0]

        results = []
        for i, skill in enumerate(skills):
            if i >= len(similarities) or i >= len(tfidf_similarities):
                continue
            relevance = (similarities[i] + tfidf_similarities[i]) / 2 * 100
            if relevance > 10:
                results.append({"id": skill["id"], "name": skill["name"], "depth": round(relevance)})

        sorted_results = sorted(results, key=lambda x: x["depth"], reverse=True)
        return json.dumps(sorted_results, ensure_ascii=False)

    except Exception as e:
        print(f"[ERROR] {str(e)}", file=sys.stderr)
        return json.dumps({"error": str(e)})

if __name__ == "__main__":
    try:
        input_data = json.loads(sys.stdin.read())

        if input_data is None:
            print(json.dumps({"error": "Input data is None"}))
            sys.exit(1)

        content = input_data.get("content", "").strip()
        skills = input_data.get("skills", [])

        if not content or not skills:
            print(json.dumps({"error": "Content or skills are missing in input"}))
            sys.exit(1)

        output = analyze_text(content, skills)
        print(output)

    except Exception as e:
        print(json.dumps({"error": str(e)}))
