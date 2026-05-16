<x-app-layout>
    <div class="fade-up" style="margin-bottom: 40px;">
        <a href="/exams" style="font-size:13px; color:var(--muted); text-decoration:none;">← Back to My Exams</a>
        <h1 class="page-title" style="margin-top:12px;">{{ $exam->title }}</h1>
        <p class="page-eyebrow" style="margin-top:8px;">{{ $questions->count() }} questions generated</p>
    </div>

    {{-- Start Review Options --}}
    @if($exam->status === 'ready')
    <div class="section-label fade-up-1">Choose Review Mode</div>
    <div class="action-grid fade-up-1" style="margin-bottom: 48px;">
        <a href="/review/{{ $exam->id }}/mcq" class="action-card action-primary" style="text-decoration:none;">
            <div class="action-icon">⊙</div>
            <div>
                <p class="action-title">Multiple Choice</p>
                <p class="action-sub">Answer one by one</p>
            </div>
        </a>
        <a href="/review/{{ $exam->id }}/flashcard" class="action-card" style="text-decoration:none;">
            <div class="action-icon action-icon-muted">⊞</div>
            <div>
                <p class="action-title">Flashcards</p>
                <p class="action-sub">Flip and memorize</p>
            </div>
        </a>
        <a href="/review/{{ $exam->id }}/mock" class="action-card" style="text-decoration:none;">
            <div class="action-icon action-icon-muted">◎</div>
            <div>
                <p class="action-title">Mock Exam</p>
                <p class="action-sub">Timed full exam</p>
            </div>
        </a>
    </div>
    @endif

    {{-- Questions Preview --}}
    <div class="section-label fade-up-2">Questions Preview</div>
    <div class="fade-up-2">
        @foreach($questions as $i => $question)
        <div class="card" style="padding: 24px 28px; margin-bottom: 12px;">
            <p style="font-size:11px; font-weight:500; letter-spacing:1.5px; text-transform:uppercase; color:var(--muted); margin-bottom:8px;">Question {{ $i + 1 }}</p>
            <p style="font-size:15px; font-weight:500; color:var(--text); margin-bottom:12px;">{{ $question->question }}</p>
            @if($question->choices)
                @foreach(json_decode($question->choices) as $choice)
                <p style="font-size:13px; color:var(--muted); padding: 4px 0;">{{ $choice }}</p>
                @endforeach
            @endif
        </div>
        @endforeach
    </div>
    <form method="POST" action="/exams/{{ $exam->id }}" style="margin-top:40px;">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Delete this exam and all its questions?')"
        style="padding:10px 24px;background:none;border:1px solid #fc8181;color:#c53030;border-radius:100px;font-size:13px;cursor:pointer;">
        Delete Exam
    </button>
</form>
</x-app-layout>