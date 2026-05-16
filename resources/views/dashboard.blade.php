<x-app-layout>
    <div class="fade-up" style="margin-bottom: 52px;">
        <p class="page-eyebrow">Study Dashboard</p>
        <h1 class="page-title">Hello, <em>{{ auth()->user()->name }}.</em><br>Ready to review?</h1>
    </div>

    {{-- Quick Actions --}}
    <div class="section-label fade-up-1">Quick Actions</div>
    <div class="action-grid fade-up-1">
        <button class="action-card action-primary" onclick="window.location='/exams/upload'">
            <div class="action-icon">↑</div>
            <div>
                <p class="action-title">Upload Exam</p>
                <p class="action-sub">PDF or text file</p>
            </div>
        </button>
        <button class="action-card" onclick="window.location='/exams'">
            <div class="action-icon action-icon-muted">▤</div>
            <div>
                <p class="action-title">My Exams</p>
                <p class="action-sub">View uploaded materials</p>
            </div>
        </button>
        <button class="action-card" onclick="window.location='/review'">
            <div class="action-icon action-icon-muted">◎</div>
            <div>
                <p class="action-title">Start Review</p>
                <p class="action-sub">Flashcard, MCQ, Mock</p>
            </div>
        </button>
        <button class="action-card" onclick="window.location='/progress'">
            <div class="action-icon action-icon-muted">↗</div>
            <div>
                <p class="action-title">My Progress</p>
                <p class="action-sub">Scores and weak spots</p>
            </div>
        </button>
    </div>

    {{-- Stats --}}
    <div class="stats-row fade-up-2">
    <div class="stat-card card">
        <p class="stat-label">Exams Taken</p>
        <p class="stat-value">{{ $totalExams }}</p>
    </div>
    <div class="stat-card card">
        <p class="stat-label">Questions Answered</p>
        <p class="stat-value">{{ $results->sum('total') }}</p>
    </div>
    <div class="stat-card card accent">
        <p class="stat-label">Avg. Score</p>
        <p class="stat-value">{{ round($avgScore) }}<span class="stat-unit">%</span></p>
    </div>
    <div class="stat-card card">
        <p class="stat-label">Best Score</p>
        <p class="stat-value">{{ $results->max('percentage') ?? 0 }}<span class="stat-unit">%</span></p>
    </div>
</div>

    {{-- Exam Library --}}
    <div class="section-label fade-up-3" style="margin-top: 48px;">Exam Library</div>
    <div class="library-grid fade-up-3">
        <div class="library-card card">
            <div class="library-tag badge badge-purple">CSC</div>
            <p class="library-title">Civil Service Exam</p>
            <p class="library-sub">Verbal, Numerical, Analytical</p>
            <span class="library-soon">Coming Soon</span>
        </div>
        <div class="library-card card">
            <div class="library-tag badge badge-purple">DOST</div>
            <p class="library-title">DOST Scholarship</p>
            <p class="library-sub">Math, Science, English, Abstract</p>
            <span class="library-soon">Coming Soon</span>
        </div>
        <div class="library-card card library-upload">
            <div class="library-icon">+</div>
            <p class="library-title">Upload Your Own</p>
            <p class="library-sub">Any exam, any subject</p>
        </div>
    </div>
</x-app-layout>