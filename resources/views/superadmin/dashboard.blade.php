<x-app-layout>
    <div class="fade-up" style="margin-bottom:40px;">
        <p class="page-eyebrow">SuperAdmin</p>
        <h1 class="page-title">System <em>Overview</em></h1>
    </div>

    <div class="stats-row fade-up-1" style="margin-bottom:40px;">
        <div class="stat-card card accent">
            <p class="stat-label">Templates</p>
            <p class="stat-value">{{ $totalTemplates }}</p>
        </div>
        <div class="stat-card card">
            <p class="stat-label">Questions</p>
            <p class="stat-value">{{ $totalQuestions }}</p>
        </div>
        <div class="stat-card card">
            <p class="stat-label">Topics</p>
            <p class="stat-value">{{ $totalTopics }}</p>
        </div>
        <div class="stat-card card">
            <p class="stat-label">Users</p>
            <p class="stat-value">{{ $totalUsers }}</p>
        </div>
    </div>

    <div style="display:flex;gap:12px;margin-bottom:40px;" class="fade-up-2">
        <a href="/superadmin/upload" class="btn-primary">Upload Raw Exam</a>
        <form method="POST" action="/superadmin/generate">
            @csrf
            <button type="submit" style="padding:12px 28px;border:1px solid var(--border);border-radius:100px;font-size:14px;color:var(--muted);background:none;cursor:pointer;">
                Generate More Questions
            </button>
        </form>
        <a href="/superadmin/templates" style="padding:12px 28px;border:1px solid var(--border);border-radius:100px;font-size:14px;color:var(--muted);text-decoration:none;">
            View Templates
        </a>
    </div>

    @if(session('success'))
    <div class="alert-success fade-up">{{ session('success') }}</div>
    @endif

    <div class="section-label fade-up-3">Recent Templates</div>
    <div class="fade-up-3">
        @foreach($recentTemplates as $template)
        <div class="card" style="padding:20px 28px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <p style="font-size:14px;font-weight:500;color:var(--text);">{{ Str::limit($template->template, 80) }}</p>
                <p style="font-size:12px;color:var(--muted);margin-top:4px;">{{ $template->topic->name }} · {{ $template->difficulty }} · {{ $template->exam_type }}</p>
            </div>
            <span class="badge badge-purple">{{ $template->type }}</span>
        </div>
        @endforeach
    </div>
</x-app-layout>