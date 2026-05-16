<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['topic_id', 'template_id', 'exam_id', 'type', 'difficulty', 'exam_type', 'question', 'choices', 'answer', 'explanation', 'meta', 'hash'];
    protected $casts = ['choices' => 'array', 'meta' => 'array'];

    public function topic() { return $this->belongsTo(Topic::class); }
    public function template() { return $this->belongsTo(QuestionTemplate::class); }
    public function exam() { return $this->belongsTo(Exam::class); }
}