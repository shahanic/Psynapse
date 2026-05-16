<?php
namespace Database\Seeders;

use App\Models\Topic;
use App\Models\QuestionTemplate;
use Illuminate\Database\Seeder;

class TopicAndTemplateSeeder extends Seeder
{
    public function run()
    {
        // Topics
        $math = Topic::create(['name' => 'Mathematics', 'slug' => 'math', 'exam_type' => 'dost']);
        $english = Topic::create(['name' => 'English', 'slug' => 'english', 'exam_type' => 'dost']);
        $logic = Topic::create(['name' => 'Abstract Reasoning', 'slug' => 'abstract', 'exam_type' => 'dost']);
        $verbal = Topic::create(['name' => 'Verbal Ability', 'slug' => 'verbal', 'exam_type' => 'csc']);

        // Template 1: Speed-Distance-Time
        QuestionTemplate::create([
            'topic_id' => $math->id,
            'template' => 'A car travels at {speed} km/h for {time} hours. How far does it travel?',
            'variables' => [
                'speed' => ['type' => 'int', 'min' => 40, 'max' => 120],
                'time' => ['type' => 'int', 'min' => 1, 'max' => 8],
            ],
            'answer_formula' => [
                'type' => 'eval',
                'expression' => '{speed} * {time}',
                'explanation' => 'Distance = Speed × Time = {speed} × {time} = {answer} km',
            ],
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'exam_type' => 'dost',
        ]);

        // Template 2: Percentage
        QuestionTemplate::create([
            'topic_id' => $math->id,
            'template' => 'What is {percent}% of {number}?',
            'variables' => [
                'percent' => ['type' => 'int', 'min' => 5, 'max' => 95],
                'number' => ['type' => 'int', 'min' => 100, 'max' => 1000],
            ],
            'answer_formula' => [
                'type' => 'eval',
                'expression' => 'round(({percent} / 100) * {number}, 2)',
                'explanation' => '{percent}% of {number} = ({percent}/100) × {number} = {answer}',
            ],
            'type' => 'multiple_choice',
            'difficulty' => 'medium',
            'exam_type' => 'dost',
        ]);

        // Template 3: Simple Interest
        QuestionTemplate::create([
            'topic_id' => $math->id,
            'template' => 'Find the simple interest on a principal of ₱{principal} at {rate}% per year for {years} years.',
            'variables' => [
                'principal' => ['type' => 'int', 'min' => 1000, 'max' => 50000],
                'rate' => ['type' => 'int', 'min' => 2, 'max' => 15],
                'years' => ['type' => 'int', 'min' => 1, 'max' => 10],
            ],
            'answer_formula' => [
                'type' => 'eval',
                'expression' => 'round(({principal} * {rate} * {years}) / 100, 2)',
                'explanation' => 'SI = (P × R × T) / 100 = ({principal} × {rate} × {years}) / 100 = ₱{answer}',
            ],
            'type' => 'multiple_choice',
            'difficulty' => 'medium',
            'exam_type' => 'csc',
        ]);

        // Template 4: True/False
        QuestionTemplate::create([
            'topic_id' => $math->id,
            'template' => '{a} × {b} = {wrong_answer}',
            'variables' => [
                'a' => ['type' => 'int', 'min' => 2, 'max' => 20],
                'b' => ['type' => 'int', 'min' => 2, 'max' => 20],
                'wrong_answer' => ['type' => 'int', 'min' => 5, 'max' => 500],
            ],
            'answer_formula' => [
                'type' => 'eval',
                'expression' => '({a} * {b} == {wrong_answer}) ? "True" : "False"',
                'explanation' => '{a} × {b} = ' . '{a}*{b}' . ', not {wrong_answer}.',
            ],
            'type' => 'true_false',
            'difficulty' => 'easy',
            'exam_type' => 'general',
        ]);

        // Template 5: Synonym (English)
        QuestionTemplate::create([
            'topic_id' => $english->id,
            'template' => 'What is the synonym of "{word}"?',
            'variables' => [
                'word' => ['type' => 'choice', 'options' => ['happy', 'sad', 'fast', 'smart', 'brave']],
            ],
            'answer_formula' => [
                'type' => 'lookup',
                'key' => 'word',
                'map' => [
                    'happy' => 'joyful',
                    'sad' => 'sorrowful',
                    'fast' => 'swift',
                    'smart' => 'intelligent',
                    'brave' => 'courageous',
                ],
                'explanation' => 'The synonym of "{word}" is "{answer}".',
            ],
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'exam_type' => 'csc',
        ]);
    }
}