<?php
return [
// https://openrouter.ai/settings/keys
    'key' => env('AI_KEY'),
    'router' => 'https://openrouter.ai/api/v1/chat/completions',
//    'model' => 'deepseek/deepseek-r1-zero:free',
//    'model' => 'mistralai/mistral-small-24b-instruct-2501:free',
    'model' => 'mistralai/mistral-small-3.1-24b-instruct:free',
    'prompt' => 'Task:
        We have educational content and a list of competencies and skills related to this content. You need to:
        1. Analyze the text of the content and find matches with the skills and competencies.
        2. For each match, determine the degree of correspondence (depth) and the level of knowledge on a scale from 1 to 100, where:
           - 1 — minimal correspondence (mention of a skill or competency with very little context).
           - 100 — maximal correspondence (full description of a skill or competency with detailed examples and explanations).

        Important Notes:
        - Put scores if it is really educational content.
        - Only assign competencies and skills that are explicitly mentioned or strongly implied in the text.
        - Do not assign competencies or skills that are not relevant to the content.
        - Provide a brief explanation for each assigned skill or competency to justify the depth score.

        Content:
        {content}

        List of competencies and skills:
        {skills}

        Requirements:
        1. Analyze the text of the transcription and find matches with the skills and competencies.
        2. Determine the degree of correspondence (depth) for each match.
        3. The response format must be JSON without extra explanations.

        The response should not contain any extra text, ONLY JSON.

        Example response:
        [
            {
                "id": 4,
                "name": "Machine Learning",
                "depth": 23
            },
            ...
        ]'
];
