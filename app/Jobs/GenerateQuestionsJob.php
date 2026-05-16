<?php

namespace App\Jobs;

use App\Models\Exam;
use App\Models\Question;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateQuestionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 2;

    protected $exam;
    protected $questionType;
    protected $questionCount;

    public function __construct(Exam $exam, string $questionType, int $questionCount)
    {
        $this->exam = $exam;
        $this->questionType = $questionType;
        $this->questionCount = $questionCount;
    }

    public function handle()
    {
        try {
            $ai = new AIService();
            $questions = $ai->generateQuestions(
                $this->exam->extracted_text,
                $this->questionType,
                $this->questionCount
            );

            foreach ($questions as $q) {
                Question::create([
                    'exam_id' => $this->exam->id,
                    'question' => $q['question'] ?? '',
                    'choices' => json_encode($q['choices'] ?? []),
                    'answer' => $q['answer'] ?? '',
                    'explanation' => $q['explanation'] ?? '',
                    'type' => $this->questionType,
                ]);
            }

            $this->exam->update(['status' => 'ready']);
        } catch (\Exception $e) {
            $this->exam->update(['status' => 'failed']);
            \Log::error('Question generation failed: ' . $e->getMessage());
        }
    }
}
