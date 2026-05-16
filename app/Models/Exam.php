<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['user_id', 'topic_id', 'title', 'file_path', 'extracted_text', 'status'];

    public function user() { return $this->belongsTo(User::class); }
    public function topic() { return $this->belongsTo(Topic::class); }
    public function questions() { return $this->hasMany(Question::class); }
}
