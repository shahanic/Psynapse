<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function mcq(Exam $exam)
    {
        $questions = $exam->questions()->get();
        return view('review.mcq', compact('exam', 'questions'));
    }

    public function submitMcq(Request $request, Exam $exam)
    {
        $questions = $exam->questions()->get();
        $answers = $request->input('answers', []);
        $results = [];
        $score = 0;

        foreach ($questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $userAnswer === $question->answer;
            if ($isCorrect) $score++;
            $results[] = [
                'question' => $question->question,
                'choices' => json_decode($question->choices),
                'correct_answer' => $question->answer,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'explanation' => $question->explanation,
            ];
        }

        $percentage = count($questions) > 0 ? round(($score / count($questions)) * 100) : 0;

        return view('review.result', compact('exam', 'results', 'score', 'percentage', 'questions'));
    }

    public function flashcard(Exam $exam)
    {
        $questions = $exam->questions()->get();
        return view('review.flashcard', compact('exam', 'questions'));
    }

    public function mock(Exam $exam)
    {
        $questions = $exam->questions()->inRandomOrder()->get();
        return view('review.mock', compact('exam', 'questions'));
    }

    public function submitMock(Request $request, Exam $exam)
    {
        return $this->submitMcq($request, $exam);
    }
}