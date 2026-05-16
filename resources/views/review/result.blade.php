<x-app-layout>
    <div class="fade-up" style="margin-bottom:40px;">
        <p class="page-eyebrow">Results</p>
        <h1 class="page-title">You scored <em>{{ $percentage }}%</em></h1>
        <p style="font-size:14px;color:var(--muted);margin-top:8px;">{{ $score }} out of {{ count($questions) }} correct</p>
    </div>

    <div class="fade-up-1">
        @foreach($results as $i => $result)
        <div class="card" style="padding:28px;margin-bottom:16px;border-left:3px solid {{ $result['is_correct'] ? '#68d391' : '#fc8181' }};">
            <p style="font-size:11px;font-weight:500;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted);margin-bottom:10px;">Question {{ $i + 1 }}</p>
            <p style="font-size:15px;font-weight:500;color:var(--text);margin-bottom:16px;">{{ $result['question'] }}</p>

            @foreach($result['choices'] as $choice)
            <p style="font-size:13px;padding:8px 14px;border-radius:8px;margin-bottom:6px;
                background:{{ $choice === $result['correct_answer'] ? '#f0fff4' : ($choice === $result['user_answer'] && !$result['is_correct'] ? '#fff5f5' : 'var(--off)') }};
                color:{{ $choice === $result['correct_answer'] ? '#276749' : ($choice === $result['user_answer'] && !$result['is_correct'] ? '#c53030' : 'var(--muted)') }};">
                {{ $choice }}
                @if($choice === $result['correct_answer']) ✓ @endif
                @if($choice === $result['user_answer'] && !$result['is_correct']) ✗ @endif
            </p>
            @endforeach

            @if(!$result['is_correct'] && $result['explanation'])
            <div style="margin-top:14px;padding:14px;background:var(--purple-100);border-radius:10px;">
                <p style="font-size:11px;font-weight:500;letter-spacing:1px;text-transform:uppercase;color:var(--purple-600);margin-bottom:4px;">Explanation</p>
                <p style="font-size:13px;color:var(--text);">{{ $result['explanation'] }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div style="margin-top:24px;display:flex;gap:12px;">
        <a href="/review/{{ $exam->id }}/mcq" class="btn-primary">Try Again</a>
        <a href="/exams/{{ $exam->id }}" style="display:inline-flex;align-items:center;padding:12px 28px;border:1px solid var(--border);border-radius:100px;font-size:14px;color:var(--muted);text-decoration:none;">Back to Exam</a>
    </div>
</x-app-layout>