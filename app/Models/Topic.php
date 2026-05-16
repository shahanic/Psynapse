<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['name', 'slug', 'exam_type'];

    public function templates() { return $this->hasMany(QuestionTemplate::class); }
    public function questions() { return $this->hasMany(Question::class); }
}