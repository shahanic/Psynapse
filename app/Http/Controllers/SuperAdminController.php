<?php
namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Question;
use App\Models\QuestionTemplate;
use App\Models\User;
use App\Services\AITemplateService;
use App\Services\QuestionGeneratorService;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        return view('superadmin.dashboard', [
            'totalUsers' => User::count(),
            'totalTemplates' => QuestionTemplate::count(),
            'totalQuestions' => Question::count(),
            'totalTopics' => Topic::count(),
            'recentTemplates' => QuestionTemplate::with('topic')->latest()->take(5)->get(),
        ]);
    }

    public function templates()
    {
        $templates = QuestionTemplate::with('topic')
            ->withCount('questions')
            ->latest()
            ->paginate(20);
        return view('superadmin.templates', compact('templates'));
    }

    public function uploadExam()
    {
        return view('superadmin.upload');
    }

    public function processExam(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,txt|max:10240',
            'exam_type' => 'required|string',
        ]);

        $file = $request->file('file');
        $fullPath = $file->getRealPath();
        $extractedText = file_get_contents($fullPath);

        $ai = new AITemplateService();
        $templates = $ai->convertToTemplates($extractedText, $request->exam_type);
        $saved = $ai->saveTemplates($templates, $request->exam_type);

        // Auto-generate questions from new templates
        $generator = new QuestionGeneratorService();
        $generated = 0;
        $newTemplates = QuestionTemplate::latest()->take($saved)->get();
        foreach ($newTemplates as $template) {
            $generated += $generator->generateFromTemplate($template, 30);
        }

        return redirect('/superadmin/templates')
            ->with('success', "Created {$saved} templates and {$generated} questions!");
    }

    public function generateQuestions()
    {
        $generator = new QuestionGeneratorService();
        $result = $generator->generateAll(20);
        return redirect('/superadmin/dashboard')
            ->with('success', "Generated {$result['total']} new questions!");
    }

    public function deleteTemplate(QuestionTemplate $template)
    {
        $template->questions()->delete();
        $template->delete();
        return back()->with('success', 'Template deleted.');
    }
}
