<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuestionTemplate extends Model
{
    protected $fillable = ['topic_id', 'template', 'variables', 'answer_formula', 'type', 'difficulty', 'exam_type', 'is_active'];
    protected $casts = ['variables' => 'array', 'answer_formula' => 'array'];

    public function topic() { return $this->belongsTo(Topic::class); }
    public function questions() { return $this->hasMany(Question::class, 'template_id'); }
}