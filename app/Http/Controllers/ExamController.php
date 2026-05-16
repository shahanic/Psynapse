<?php

namespace App\Http\Controllers;
use App\Services\AIService;
use App\Models\Question;
use App\Models\Exam;
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
        return view('exams.create');
    }
    public function destroy(Exam $exam)
    {
    if ($exam->user_id !== auth()->id()) {
        abort(403);
    }
    
    $exam->questions()->delete();
    $exam->delete();
    
    return redirect('/exams')->with('success', 'Exam deleted.');
    }
    public function retry(Exam $exam)
{
    if ($exam->user_id !== auth()->id()) abort(403);
    
    $exam->questions()->delete();
    $exam->update(['status' => 'processing']);
    
    \App\Jobs\GenerateQuestionsJob::dispatch($exam, 'multiple_choice', 10);
    
    return redirect('/exams')->with('success', 'Retrying question generation...');
}

    public function show(Exam $exam)
    {
    $questions = $exam->questions()->get();
    return view('exams.show', compact('exam', 'questions'));
    }
  public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'file' => 'required|file|mimes:pdf,txt|max:10240',
        'question_type' => 'required|string',
        'question_count' => 'required|integer',
    ]);

    $path = $request->file('file')->store('exams', 'public');
    $fullPath = storage_path('app/public/' . $path);

    $extractedText = '';
try {
    if ($request->file('file')->getClientOriginalExtension() === 'pdf') {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($fullPath);
        $extractedText = $pdf->getText();
    } else {
        $extractedText = file_get_contents($fullPath);
    }
} catch (\Exception $e) {
    \Log::error('File extraction failed: ' . $e->getMessage());
    $extractedText = '';
}

    $exam = Exam::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'file_path' => $path,
        'extracted_text' => $extractedText,
        'status' => 'processing',
    ]);

    // Dispatch to background queue
    \App\Jobs\GenerateQuestionsJob::dispatch(
        $exam,
        $request->question_type,
        (int) $request->question_count
    );

    return redirect('/exams')->with('success', 'Exam uploaded! Questions are being generated — this may take a minute.');
}
}