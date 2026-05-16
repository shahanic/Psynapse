<x-app-layout>
    <div class="fade-up" style="margin-bottom:40px;">
        <a href="/exams/{{ $exam->id }}" style="font-size:13px;color:var(--muted);text-decoration:none;">← Back</a>
        <h1 class="page-title" style="margin-top:12px;">{{ $exam->title }}</h1>
        <p class="page-eyebrow" style="margin-top:8px;">Multiple Choice — {{ $questions->count() }} questions</p>
    </div>

    <form method="POST" action="/review/{{ $exam->id }}/mcq">
        @csrf
        @foreach($questions as $i => $question)
        <div class="card fade-up-1" style="padding:28px;margin-bottom:16px;">
            <p style="font-size:11px;font-weight:500;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted);margin-bottom:10px;">Question {{ $i + 1 }}</p>
            <p style="font-size:16px;font-weight:500;color:var(--text);margin-bottom:20px;">{{ $question->question }}</p>
            @foreach(json_decode($question->choices) as $choice)
            <label class="choice-label">
                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $choice }}">
                <span class="choice-inner">{{ $choice }}</span>
            </label>
            @endforeach
        </div>
        @endforeach

        <button type="submit" class="btn-primary" style="margin-top:8px;">Submit Answers</button>
    </form>
</x-app-layout>