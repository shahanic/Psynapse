<?php
namespace App\Services;

use App\Models\Question;
use App\Models\QuestionTemplate;
use Illuminate\Support\Str;

class QuestionGeneratorService
{
    // How many instances to generate per template per batch
    const BATCH_SIZE = 20;

    public function generateFromTemplate(QuestionTemplate $template, int $count = null): int
    {
        $count = $count ?? self::BATCH_SIZE;
        $generated = 0;

        for ($i = 0; $i < $count; $i++) {
            $vars = $this->resolveVariables($template->variables ?? []);
            $questionText = $this->fillTemplate($template->template, $vars);
            $answer = $this->resolveAnswer($template->answer_formula, $vars);
            $choices = $this->generateChoices($template->type, $answer, $vars);
            $hash = md5($questionText . $answer);

            // Skip duplicates
            if (Question::where('hash', $hash)->exists()) continue;

            Question::create([
                'topic_id' => $template->topic_id,
                'template_id' => $template->id,
                'type' => $template->type,
                'difficulty' => $template->difficulty,
                'exam_type' => $template->exam_type,
                'question' => $questionText,
                'choices' => $choices,
                'answer' => $answer,
                'explanation' => $this->generateExplanation($template->answer_formula, $vars, $answer),
                'meta' => $vars,
                'hash' => $hash,
            ]);

            $generated++;
        }

        return $generated;
    }

    private function resolveVariables(array $variableDefs): array
    {
        $resolved = [];
        foreach ($variableDefs as $name => $def) {
            $resolved[$name] = match($def['type']) {
                'int' => rand($def['min'], $def['max']),
                'float' => round(rand($def['min'] * 10, $def['max'] * 10) / 10, 1),
                'choice' => $def['options'][array_rand($def['options'])],
                default => $def['default'] ?? 0,
            };
        }
        return $resolved;
    }

    private function fillTemplate(string $template, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }

    private function resolveAnswer(array $formula, array $vars): string
    {
        $type = $formula['type'] ?? 'static';

        return match($type) {
            'eval' => $this->safeEval($formula['expression'], $vars),
            'static' => $formula['value'],
            'lookup' => $vars[$formula['key']] ?? '',
            default => '',
        };
    }

    private function safeEval(string $expression, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $expression = str_replace('{' . $key . '}', $value, $expression);
        }
        try {
            $result = eval('return ' . $expression . ';');
            return is_float($result) ? round($result, 2) : (string)$result;
        } catch (\Throwable $e) {
            return '0';
        }
    }

    private function generateChoices(string $type, string $answer, array $vars): array
    {
        if ($type === 'true_false') return ['True', 'False'];
        if ($type === 'identification') return [];

        // Generate 3 wrong answers near the correct one
        $isNumeric = is_numeric($answer);
        $choices = [$answer];

        if ($isNumeric) {
            $base = (float)$answer;
            $offsets = [
                round($base * 0.8, 2),
                round($base * 1.2, 2),
                round($base + rand(1, 5), 2),
            ];
            foreach ($offsets as $offset) {
                if ($offset != $base) $choices[] = (string)$offset;
            }
        } else {
            $choices = [$answer, 'None of the above', 'All of the above', 'Cannot be determined'];
        }

        $choices = array_unique($choices);
        shuffle($choices);
        return array_slice($choices, 0, 4);
    }

    private function generateExplanation(array $formula, array $vars, string $answer): string
    {
        if (isset($formula['explanation'])) {
            $exp = $formula['explanation'];
            foreach ($vars as $key => $value) {
                $exp = str_replace('{' . $key . '}', $value, $exp);
            }
            $exp = str_replace('{answer}', $answer, $exp);
            return $exp;
        }
        return 'The correct answer is ' . $answer . '.';
    }

    public function generateAll(int $perTemplate = null): array
    {
        $templates = QuestionTemplate::where('is_active', true)->get();
        $total = 0;
        $results = [];

        foreach ($templates as $template) {
            $count = $this->generateFromTemplate($template, $perTemplate);
            $total += $count;
            $results[] = ['template_id' => $template->id, 'generated' => $count];
        }

        return ['total' => $total, 'details' => $results];
    }
}