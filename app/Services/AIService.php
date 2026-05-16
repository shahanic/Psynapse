<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY');
        $this->model = env('OPENROUTER_MODEL', 'google/gemma-3-27b-it:free');
    }

    public function generateQuestions(string $text, string $type = 'multiple_choice', int $count = 10): array
    {
        $prompt = $this->buildPrompt($text, $type, $count);

        $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $this->apiKey,
    'Content-Type' => 'application/json',
])->timeout(180)->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an exam question generator. Always respond with valid JSON only. No explanation, no markdown, just raw JSON.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 4000,
        ]);

        $content = $response->json('choices.0.message.content');

        // Clean response in case model adds markdown
        $content = preg_replace('/```json|```/', '', $content);
        $content = trim($content);

        return json_decode($content, true) ?? [];
    }

    private function buildPrompt(string $text, string $type, int $count): string
    {
        $typeInstructions = match($type) {
            'multiple_choice' => 'multiple choice questions with 4 options (A, B, C, D). Mark the correct answer.',
            'true_false' => 'true or false questions.',
            'identification' => 'identification questions where the answer is a specific word or phrase.',
            'fill_blank' => 'fill in the blank questions with one blank per question.',
            default => 'multiple choice questions with 4 options.'
        };

        return "Based on this study material, generate exactly {$count} {$typeInstructions}

Study material:
{$text}

Respond ONLY with a JSON array in this exact format:
[
  {
    \"question\": \"Question text here?\",
    \"choices\": [\"A. Option1\", \"B. Option2\", \"C. Option3\", \"D. Option4\"],
    \"answer\": \"A. Option1\",
    \"explanation\": \"Brief explanation of why this is correct.\"
  }
]

For true/false and identification, choices can be an empty array [].";
    }
}