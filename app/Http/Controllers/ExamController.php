<?php
namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = auth()->user()->exams()->latest()->get();
        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $topics = Topic::all();
        return view('exams.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'topic_id' => 'required|exists:topics,id',
            'question_type' => 'required|string',
            'question_count' => 'required|integer|min:5|max:50',
            'difficulty' => 'required|string',
        ]);

        $exam = Exam::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'topic_id' => $request->topic_id,
            'status' => 'ready',
        ]);

        return redirect('/exams/' . $exam->id . '?type=' . $request->question_type . '&difficulty=' . $request->difficulty . '&count=' . $request->question_count)
            ->with('success', 'Exam ready!');
    }

    public function show(Exam $exam, Request $request)
    {
        $type = $request->get('type', 'multiple_choice');
        $difficulty = $request->get('difficulty', 'medium');
        $count = $request->get('count', 10);

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

        return view('exams.show', compact('exam', 'questions'));
    }

    public function destroy(Exam $exam)
    {
        if ($exam->user_id !== auth()->id()) abort(403);
        $exam->delete();
        return redirect('/exams')->with('success', 'Exam deleted.');
    }
}
