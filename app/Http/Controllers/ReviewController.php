<?php
namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    private function getQuestions(Exam $exam, string $type = 'multiple_choice', string $difficulty = 'medium', int $count = 10)
    {
        $questions = Question::where('topic_id', $exam->topic_id)
            ->where('type', $type)
            ->where('difficulty', $difficulty)
            ->inRandomOrder()
            ->take($count)
            ->get();

        if ($questions->isEmpty()) {
            $questions = Question::where('topic_id', $exam->topic_id)
                ->inRandomOrder()
                ->take($count)
                ->get();
        }

        if ($questions->isEmpty()) {
            $questions = Question::inRandomOrder()->take($count)->get();
        }

        return $questions;
    }

    public function mcq(Exam $exam, Request $request)
    {
        $questions = $this->getQuestions($exam, 'multiple_choice', $request->get('difficulty', 'medium'), $request->get('count', 10));
        return view('review.mcq', compact('exam', 'questions'));
    }

    public function submitMcq(Request $request, Exam $exam)
    {
        $questions = Question::whereIn('id', array_keys($request->input('answers', [])))->get();
        $answers = $request->input('answers', []);
        $results = [];
        $score = 0;

        foreach ($questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $userAnswer === $question->answer;
            if ($isCorrect) $score++;
            $results[] = [
                'question' => $question->question,
                'choices' => $question->choices,
                'correct_answer' => $question->answer,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'explanation' => $question->explanation,
            ];
        }

        $total = count($questions);
        $percentage = $total > 0 ? round(($score / $total) * 100) : 0;

        // Save result
        Result::create([
            'user_id' => auth()->id(),
            'exam_id' => $exam->id,
            'score' => $score,
            'total' => $total,
            'percentage' => $percentage,
            'type' => 'multiple_choice',
            'difficulty' => $exam->difficulty ?? 'medium',
            'answers' => $results,
        ]);

        return view('review.result', compact('exam', 'results', 'score', 'percentage', 'questions'));
    }

    public function flashcard(Exam $exam, Request $request)
    {
        $questions = $this->getQuestions($exam, 'multiple_choice', $request->get('difficulty', 'medium'), $request->get('count', 10));
        return view('review.flashcard', compact('exam', 'questions'));
    }

    public function mock(Exam $exam, Request $request)
    {
        $questions = $this->getQuestions($exam, 'multiple_choice', $request->get('difficulty', 'medium'), 30);
        return view('review.mock', compact('exam', 'questions'));
    }

    public function submitMock(Request $request, Exam $exam)
    {
        return $this->submitMcq($request, $exam);
    }
}