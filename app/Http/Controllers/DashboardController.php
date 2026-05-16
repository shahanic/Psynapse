<?php
namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $results = Result::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        $avgScore = Result::where('user_id', auth()->id())->avg('percentage') ?? 0;
        $totalExams = Result::where('user_id', auth()->id())->count();

        return view('dashboard', compact('results', 'avgScore', 'totalExams'));
    }

    public function adminDashboard()
    {
        return view('admin.dashboard', [
            'totalUsers' => \App\Models\User::count(),
            'users' => \App\Models\User::all(),
        ]);
    }
}
