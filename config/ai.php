<?php

/*
| Подключение нейросети (необязательно). Любой OpenAI-совместимый API:
| задайте AI_API_KEY в файле .env, и конструктор начнёт генерировать
| тексты нейросетью. Без ключа или при ошибке работает офлайн-генератор.
*/
return [
    'key' => env('AI_API_KEY'),
    'base_url' => env('AI_BASE_URL', 'https://api.openai.com/v1'),
    'model' => env('AI_MODEL', 'gpt-4o-mini'),
];
