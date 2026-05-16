<?php
namespace App\Services;

use App\Models\Topic;
use App\Models\QuestionTemplate;
use Illuminate\Support\Facades\Http;

class AITemplateService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY');
        $this->model = env('OPENROUTER_MODEL', 'google/gemma-3-27b-it:free');
    }

    public function convertToTemplates(string $rawText, string $examType = 'general'): array
    {
        $prompt = $this->buildPrompt($rawText, $examType);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(180)->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an exam question analyst. Convert exam questions into reusable templates with variable placeholders. Always respond with valid JSON only. No markdown, no explanation.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ]
            ],
            'max_tokens' => 4000,
        ]);

        $content = $response->json('choices.0.message.content');
        $content = preg_replace('/```json|```/', '', $content);
        $content = trim($content);

        return json_decode($content, true) ?? [];
    }

    public function saveTemplates(array $templates, string $examType): int
    {
        $saved = 0;
        foreach ($templates as $t) {
            $topic = Topic::firstOrCreate(
                ['slug' => \Str::slug($t['topic'] ?? 'general')],
                ['name' => $t['topic'] ?? 'General', 'exam_type' => $examType]
            );

            QuestionTemplate::create([
                'topic_id' => $topic->id,
                'template' => $t['template'],
                'variables' => $t['variables'] ?? [],
                'answer_formula' => $t['answer_formula'] ?? ['type' => 'static', 'value' => $t['answer'] ?? ''],
                'type' => $t['type'] ?? 'multiple_choice',
                'difficulty' => $t['difficulty'] ?? 'medium',
                'exam_type' => $examType,
                'is_active' => true,
            ]);

            $saved++;
        }
        return $saved;
    }

    private function buildPrompt(string $rawText, string $examType): string
    {
        return <<<PROMPT
You are given raw exam questions from a {$examType} exam. Convert them into reusable templates with variable placeholders like {a}, {b}, {speed}, {time}.

Rules:
- Replace specific numbers with variable placeholders
- Keep text/concept questions as-is if they can't be templated
- For math questions, provide the formula as answer_formula
- difficulty: easy, medium, or hard
- type: multiple_choice, true_false, or identification

Raw questions:
{$rawText}

Respond ONLY with a JSON array:
[
  {
    "topic": "Mathematics",
    "template": "A train travels {speed} km/h for {time} hours. What is the distance?",
    "variables": {
      "speed": {"type": "int", "min": 40, "max": 120},
      "time": {"type": "int", "min": 1, "max": 8}
    },
    "answer_formula": {
      "type": "eval",
      "expression": "{speed} * {time}",
      "explanation": "Distance = Speed × Time = {speed} × {time} = {answer} km"
    },
    "type": "multiple_choice",
    "difficulty": "medium"
  }
]
PROMPT;
    }
}