<x-app-layout>
    <div class="fade-up" style="margin-bottom: 40px;">
        <p class="page-eyebrow">My Exams</p>
        <h1 class="page-title">Your <em>materials.</em></h1>
    </div>

    @if(session('success'))
        <div class="alert-success fade-up">{{ session('success') }}</div>
    @endif

    <div class="fade-up-1">
        @if($exams->where('status', 'processing')->count() > 0)
<div style="background:var(--purple-100);border:1px solid var(--purple-200);border-radius:12px;padding:14px 20px;margin-bottom:20px;font-size:13px;color:var(--purple-600);">
    ⏳ Questions are being generated — page will refresh automatically...
</div>
<script>setTimeout(() => location.reload(), 5000);</script>
@endif
        @forelse($exams as $exam)
        <a href="/exams/{{ $exam->id }}" class="exam-row card" style="text-decoration:none;">
    <div>
        <p class="exam-title">{{ $exam->title }}</p>
        <p class="exam-meta">Uploaded {{ $exam->created_at->diffForHumans() }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;">
    <span class="badge {{ $exam->status === 'ready' ? 'badge-purple' : ($exam->status === 'processing' ? 'badge-pill' : 'badge-gray') }}">
        {{ $exam->status }}
    </span>
    @if($exam->status === 'ready')
        <span class="badge badge-pill">Start Review →</span>
    @endif
    @if($exam->status === 'failed')
        <a href="/exams/{{ $exam->id }}/retry" style="font-size:12px;color:var(--purple-600);text-decoration:none;">Retry ↺</a>
    @endif
</div>
</a>
        @empty
        <div class="empty-state card">
            <p class="empty-icon">◎</p>
            <p class="empty-title">No exams yet</p>
            <p class="empty-sub">Upload your first reviewer to get started</p>
            <a href="/exams/upload" class="btn-primary" style="display:inline-block; margin-top: 20px;">Upload Now</a>
        </div>
        @endforelse
    </div>
</x-app-layout>